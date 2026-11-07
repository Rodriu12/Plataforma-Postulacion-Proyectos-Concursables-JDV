<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Symfony\Component\Process\Process;

Schedule::command('app:sincronizar-fondos-gob')->dailyAt('00:00');
Schedule::command('app:enviar-novedades-proyectos')->dailyAt('10:00');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test {--suite=all : Suite a ejecutar: all, Unit, Feature, E2E} {--filter= : Filtrar por nombre del test} {--compact : Ejecuta sin salida detallada}', function (): int {
    $suite = $this->option('suite');
    $filter = $this->option('filter');
    $compact = $this->option('compact');
    $command = [PHP_BINARY, 'vendor/bin/pest'];

    if ($suite !== 'all') {
        $command[] = '--testsuite='.$suite;
    }

    if ($filter !== null && $filter !== '') {
        $command[] = '--filter='.$filter;
    }

    if (! $compact) {
        $this->newLine();
        $this->info('▶ Ejecutando pruebas '.($suite === 'all' ? 'globales' : 'de la suite '.$suite));
        $this->newLine();
    }

    $process = new Process($command, base_path());
    $process->setTty(Process::isTtySupported());
    $process->run(function ($type, $buffer): void {
        if ($type === Process::OUT) {
            $this->output->write($buffer);
        }
    });

    if ($process->getExitCode() !== 0) {
        return $process->getExitCode();
    }

    if (! $compact) {
        $this->newLine();
        $this->info('Finalizaron las pruebas de '.$suite);
    }

    return 0;
})->purpose('Run the test suites with explicit suite output and messages');

Artisan::command('test:unit', function () {
    $this->call('test', ['--suite' => 'Unit']);
})->purpose('Run only the Unit tests');

Artisan::command('test:feature', function () {
    $this->call('test', ['--suite' => 'Feature']);
})->purpose('Run only the Feature tests');

Artisan::command('test:e2e', function () {
    $this->call('test', ['--suite' => 'E2E']);
})->purpose('Run only the E2E tests');

Artisan::command('test:all', function () {
    $this->call('test', ['--suite' => 'all']);
})->purpose('Run all suites');
