<?php

namespace Tests\Support;

use PHPUnit\Event\TestSuite\Started as TestSuiteStarted;
use PHPUnit\Event\TestSuite\StartedSubscriber as TestSuiteStartedSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

final class AnunciadorPruebasExtension implements Extension, TestSuiteStartedSubscriber
{
    private static array $suitesAnunciadas = [];

    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber($this);
    }

    public function notify(TestSuiteStarted $event): void
    {
        $nombre = trim((string) $event->testSuite()->name());

        $mapa = [
            'unit' => 'pruebas unitarias',
            'feature' => 'pruebas de integración',
            'e2e' => 'pruebas E2E',
        ];

        $clave = strtolower($nombre);

        if (! isset($mapa[$clave]) || isset(self::$suitesAnunciadas[$clave])) {
            return;
        }

        self::$suitesAnunciadas[$clave] = true;

        $mensaje = sprintf(
            "%sEjecución de %s%s",
            PHP_EOL,
            $mapa[$clave],
            PHP_EOL.PHP_EOL,
        );

        fwrite(STDOUT, $mensaje);
    }
}
