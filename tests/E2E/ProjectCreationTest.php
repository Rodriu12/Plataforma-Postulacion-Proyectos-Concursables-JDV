<?php

use App\Models\Proyecto;
use Facebook\WebDriver\WebDriverBy;

it('logs in through Filament and creates a project in the browser', function () {
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
