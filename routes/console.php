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

    $displaySuite = match ($suite) {
        'all' => 'globales',
        'Unit' => 'unitarias',
        'Feature' => 'de integración',
        'E2E' => 'E2E',
        default => 'de la suite '.$suite,
    };

    $globalStartMessage = $suite === 'all'
        ? 'Ejecutando tests globales'
        : 'Ejecutando test '.$displaySuite;

    $filterLabel = $filter !== null && $filter !== '' ? ' (filtro: '.$filter.')' : '';

    $command = [PHP_BINARY, 'vendor/bin/pest'];

    if ($suite !== 'all') {
        $command[] = '--testsuite='.$suite;
    }

    if ($filter !== null && $filter !== '') {
        $command[] = '--filter='.$filter;
    }

    if (! $compact) {
        $this->output->writeln('');
        $this->output->writeln('<info>'.$globalStartMessage.$filterLabel.'</info>');
        $this->output->writeln('<comment>Comando: '.implode(' ', array_map('escapeshellarg', $command)).'</comment>');
        $this->output->writeln('');
    }

    $process = new Process($command, base_path());
    $process->setTty(Process::isTtySupported());
    $process->setTimeout(0);
    $process->setIdleTimeout(0);
    $process->run(function ($type, $buffer): void {
        if ($type === Process::OUT || $type === Process::ERR) {
            $this->output->write($buffer);
        }
    });

    $exitCode = $process->getExitCode();

    if ($exitCode !== 0) {
        if (! $compact) {
            $this->output->writeln('');
            $this->output->writeln('<error>Fallaron los tests '.$displaySuite.$filterLabel.'</error>');
        }

        return $exitCode;
    }

    if (! $compact) {
        $this->output->writeln('');
        $this->output->writeln('<info>Finalizaron los tests '.$displaySuite.$filterLabel.'</info>');
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
