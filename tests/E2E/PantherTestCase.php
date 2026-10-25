<?php

namespace Tests\E2E;

use App\Models\Organizacion;
use App\Models\User;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Panther\Client;
use Symfony\Component\Process\Process;
use Tests\TestCase;

abstract class PantherTestCase extends TestCase
{
    protected ?Client $browser = null;

    protected ?Process $applicationServer = null;

    protected string $databasePath;

    protected string $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $tempDir = storage_path('framework/testing');
        if (! is_dir($tempDir)) {
            @mkdir($tempDir, 0777, true);
        }
        $databasePath = $tempDir . DIRECTORY_SEPARATOR . 'e2e-' . uniqid('', true) . '.sqlite';
        touch($databasePath);

        $this->databasePath = $databasePath;
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => $this->databasePath,
            'database.connections.sqlite.url' => null,
        ]);
        DB::purge('sqlite');

        $migrationExitCode = Artisan::call('migrate:fresh', ['--force' => true]);

        if ($migrationExitCode !== 0) {
            throw new \RuntimeException('No se pudieron aplicar las migraciones a la base E2E.');
        }

        $this->startApplicationServer();

        $chromeArguments = [
            '--headless=new',
            '--disable-gpu',
            '--log-level=3',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--disable-extensions',
            '--window-size=1920,1080',
            '--disable-background-networking',
            '--disable-background-timer-throttling',
            '--disable-backgrounding-occluded-windows',
            '--disable-breakpad',
            '--disable-client-side-phishing-detection',
            '--disable-default-apps',
            '--disable-hang-monitor',
            '--disable-popup-blocking',
            '--disable-prompt-on-repost',
            '--disable-sync',
            '--metrics-recording-only',
            '--no-first-run',
            '--remote-debugging-port=0',
        ];

        $driverBinary = $this->resolveChromeDriverBinary();

        $this->browser = Client::createChromeClient(
            $driverBinary,
            $chromeArguments,
            [],
            $this->baseUrl,
        );
    }

    protected function resolveChromeDriverBinary(): ?string
    {
        $candidates = [
            base_path('drivers/chromedriver.exe'),
            base_path('drivers/chromedriver'),
            base_path('vendor/bin/chromedriver.exe'),
            base_path('vendor/bin/chromedriver'),
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    protected function getCleanTestName(): string
    {
        $raw = (string) $this->name();
        $raw = str_replace(['__pest_evaluable_', '__pest_evaluable'], '', $raw);
        $clean = preg_replace('/[^a-zA-Z0-9_\-]+/', '-', $raw);

        return trim(strtolower($clean), '-');
    }

    protected function onNotSuccessfulTest(\Throwable $t): never
    {
        if ($this->browser) {
            $cleanName = $this->getCleanTestName();

            try {
                $this->captureBrowserScreenshot("{$cleanName}-fallo.png");
            } catch (\Throwable) {
            }
        }

        parent::onNotSuccessfulTest($t);
    }

    protected function tearDown(): void
    {
        if ($this->browser) {
            $cleanName = $this->getCleanTestName();

            try {
                $this->captureBrowserScreenshot("{$cleanName}-final.png");
            } catch (\Throwable) {
                // Evitar que un fallo al capturar pantalla interrumpa el cierre de recursos
            }

            $this->browser->quit();
        }

        $this->applicationServer?->stop();
        DB::disconnect('sqlite');

        if (isset($this->databasePath) && is_file($this->databasePath)) {
            @unlink($this->databasePath);
        }

        parent::tearDown();
    }

    protected function createBrowserUser(string $role): User
    {
        $organization = Organizacion::create([
            'nombre' => 'Organización E2E',
        ]);

        return User::factory()->create([
            'organizacion_id' => $organization->id,
            'role' => $role,
            'rut' => fake()->unique()->numerify('########-#'),
        ]);
    }

    protected function logInThroughBrowser(User $user): void
    {
        $browser = $this->browser;
        $this->assertTrue(Hash::check('password', $user->password));
        $browser->request('GET', '/admin/login');
        $browser->waitFor('input[type="email"]', 10);
        $browser->findElement(WebDriverBy::cssSelector('input[type="email"]'))->sendKeys($user->email);
        $browser->findElement(WebDriverBy::cssSelector('input[type="password"]'))->sendKeys('password');
        $this->assertSame(
            $user->email,
            $browser->executeScript('return document.querySelector("input[type=email]").value;'),
        );
        $browser->waitFor('button[type="submit"]', 10);
        $browser->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
        $this->waitForBrowserPath('#^/admin$#');

        $this->assertSame('/admin', parse_url($browser->getCurrentURL(), PHP_URL_PATH));
    }

    protected function fillBrowserInput(string $selector, string $value): void
    {
        $this->browser->waitFor($selector, 10);

        $elements = $this->browser->findElements(WebDriverBy::cssSelector($selector));

        if ($elements === []) {
            $state = $this->browser->executeScript(
                'return JSON.stringify({url: location.href, count: document.querySelectorAll('.json_encode($selector).').length, body: document.body.innerText.slice(0, 800)});',
            );

            throw new \RuntimeException("No se encontró {$selector} en el navegador: {$state}");
        }

        $element = $elements[0];
        $element->clear();
        $element->sendKeys($value);

        // Si el DOM o Livewire alteraron el valor por un refresco reactivo, forzar el valor y disparar eventos
        $currentValue = $this->browser->executeScript(
            'return document.querySelector('.json_encode($selector).')?.value;',
        );

        if ($currentValue !== $value) {
            $this->browser->executeScript(
                'let el = document.querySelector('.json_encode($selector).'); if (el) { el.value = '.json_encode($value).'; el.dispatchEvent(new Event("input", {bubbles: true})); el.dispatchEvent(new Event("change", {bubbles: true})); }',
            );
        }

        $this->assertSame(
            $value,
            $this->browser->executeScript(
                'return document.querySelector('.json_encode($selector).')?.value;',
            ),
        );
    }

    protected function waitForBrowserPath(string $pathPattern): void
    {
        try {
            $this->browser->wait(10)->until(
                fn ($driver): bool => preg_match(
                    $pathPattern,
                    parse_url($driver->getCurrentURL(), PHP_URL_PATH),
                ) === 1,
            );
        } catch (TimeoutException $exception) {
            $state = $this->browser->executeScript(
                'return JSON.stringify({url: location.href, body: document.body.innerText.slice(0, 1200)});',
            );

            throw new \RuntimeException(
                "La ruta del navegador no coincide con {$pathPattern}: {$state}",
                previous: $exception,
            );
        }
    }

    protected function captureBrowserScreenshot(string $filename): string
    {
        $directory = storage_path('app/e2e-screenshots');

        if (! is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }

        $path = $directory.DIRECTORY_SEPARATOR.$filename;
        $this->browser->takeScreenshot($path);

        return $path;
    }

    private function startApplicationServer(): void
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $errorCode, $errorMessage);

        if ($socket === false) {
            throw new \RuntimeException("No se pudo reservar un puerto para el servidor E2E: {$errorMessage}");
        }

        $address = stream_socket_get_name($socket, false);
        fclose($socket);

        if ($address === false) {
            throw new \RuntimeException('No se pudo determinar el puerto del servidor E2E.');
        }

        $port = (int) substr(strrchr($address, ':'), 1);
        $this->baseUrl = "http://127.0.0.1:{$port}";
        $this->applicationServer = new Process(
            [
                PHP_BINARY,
                '-S',
                "127.0.0.1:{$port}",
                '-t',
                public_path(),
                public_path('index.php'),
            ],
            base_path(),
            [
                'APP_ENV' => 'testing',
                'APP_KEY' => (string) config('app.key'),
                'APP_URL' => $this->baseUrl,
                'DB_CONNECTION' => 'sqlite',
                'DB_DATABASE' => $this->databasePath,
                'DB_URL' => '',
                'SESSION_DRIVER' => 'database',
                'CACHE_STORE' => 'array',
                'QUEUE_CONNECTION' => 'sync',
                'MAIL_MAILER' => 'array',
            ],
        );
        $this->applicationServer->start();

        for ($attempt = 0; $attempt < 50; $attempt++) {
            $connection = @stream_socket_client("tcp://127.0.0.1:{$port}", $errorCode, $errorMessage, 0.1);

            if ($connection !== false) {
                fclose($connection);

                return;
            }

            if (! $this->applicationServer->isRunning()) {
                throw new \RuntimeException(
                    'El servidor E2E terminó durante el inicio: '.$this->applicationServer->getErrorOutput(),
                );
            }

            usleep(100_000);
        }

        throw new \RuntimeException('El servidor HTTP E2E no respondió antes del tiempo límite.');
    }
}
