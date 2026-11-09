<?php

use App\Models\Emergencia;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;

it('inicia sesión en Filament y reporta una emergencia en el navegador', function () {
    $user = $this->createBrowserUser('vecino');
    $this->logInThroughBrowser($user);

    $this->browser->request('GET', '/admin/emergencias/create');
    $tipo = $this->browser->findElement(WebDriverBy::cssSelector('select[id="form.tipo"]'));
    (new WebDriverSelect($tipo))->selectByValue('corte_servicio');
    $this->fillBrowserInput('input[id="form.ubicacion"]', 'Pasaje E2E 123');
    $this->fillBrowserInput('textarea[id="form.descripcion"]', 'Corte de agua informado en el flujo E2E.');
    $this->captureBrowserScreenshot('emergencia-formulario.png');
    $this->browser->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
    $this->waitForBrowserPath('#^/admin/emergencias/\d+/edit$#');

    $emergencia = Emergencia::query()->where('ubicacion', 'Pasaje E2E 123')->first();
    expect($emergencia)->not->toBeNull();
    expect(Emergencia::query()->where('tipo', 'corte_servicio')->exists())->toBeTrue();

    $this->captureBrowserScreenshot('emergencia-reportada.png');
});

it('carga el formulario de emergencia con los campos obligatorios visibles', function () {
    $user = $this->createBrowserUser('vecino');
    $this->logInThroughBrowser($user);

    $this->browser->request('GET', '/admin/emergencias/create');

    expect($this->browser->executeScript('return !!document.querySelector("select[id=\\"form.tipo\\"]") && !!document.querySelector("input[id=\\"form.ubicacion\\"]") && !!document.querySelector("textarea[id=\\"form.descripcion\\"]")'))->toBeTrue();
    expect($this->browser->executeScript('return document.querySelector("button[type=submit]") !== null'))->toBeTrue();
});

it('mantiene accesible la ruta de creación de emergencias desde el panel', function () {
    $user = $this->createBrowserUser('vecino');
    $this->logInThroughBrowser($user);

    $this->browser->request('GET', '/admin/emergencias/create');

    expect(parse_url($this->browser->getCurrentURL(), PHP_URL_PATH))->toBe('/admin/emergencias/create');
    expect($this->browser->executeScript('return document.body.innerText.includes("Crear Emergencia") || document.title.includes("Emergencia")'))->toBeTrue();
});
