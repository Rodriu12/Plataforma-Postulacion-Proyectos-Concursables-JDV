<?php

use App\Models\Emergencia;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;

it('logs in through Filament and reports an emergency in the browser', function () {
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
