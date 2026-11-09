<?php

use App\Models\Proyecto;
use Facebook\WebDriver\WebDriverBy;

it('inicia sesión en Filament y crea un proyecto en el navegador', function () {
    $user = $this->createBrowserUser('presidente');
    $this->logInThroughBrowser($user);

    $this->browser->request('GET', '/admin/proyectos/create');
    $this->fillBrowserInput('input[id="form.titulo"]', 'Proyecto E2E de mejoramiento de sede');
    $this->fillBrowserInput('input[id="form.monto_solicitado"]', '1500000');
    $this->captureBrowserScreenshot('proyecto-formulario.png');
    $this->browser->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
    $this->browser->waitForElementToContain('body', 'Proyecto E2E de mejoramiento de sede', 15);

    expect(Proyecto::query()->where('titulo', 'Proyecto E2E de mejoramiento de sede')->exists())->toBeTrue();
    expect(Proyecto::query()->where('monto_solicitado', 1_500_000)->exists())->toBeTrue();

    $this->captureBrowserScreenshot('proyecto-creado.png');
});

it('carga el formulario de creación de proyecto con los campos requeridos visibles', function () {
    $user = $this->createBrowserUser('presidente');
    $this->logInThroughBrowser($user);

    $this->browser->request('GET', '/admin/proyectos/create');

    expect($this->browser->executeScript('return !!document.querySelector("input[id=\"form.titulo\"]") && !!document.querySelector("input[id=\"form.monto_solicitado\"]")'))->toBeTrue();
    expect($this->browser->executeScript('return document.querySelector("button[type=submit]") !== null'))->toBeTrue();
});

it('mantiene accesible la ruta de creación de proyectos desde el panel', function () {
    $user = $this->createBrowserUser('presidente');
    $this->logInThroughBrowser($user);

    $this->browser->request('GET', '/admin/proyectos/create');

    expect(parse_url($this->browser->getCurrentURL(), PHP_URL_PATH))->toBe('/admin/proyectos/create');
    expect($this->browser->executeScript('return document.title.includes("Proyecto") || document.body.innerText.includes("Crear")'))->toBeTrue();
});
