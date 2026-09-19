<?php

use Illuminate\Support\Facades\Route;
use App\Models\Vecino;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/vecinos/{id}/certificado', function ($id) {
    $vecino = Vecino::findOrFail($id);

    // Verificación de seguridad: si por algún motivo intentan entrar sin estar aprobado, se bloquea
    if ($vecino->estado !== 'aprobado') {
        abort(403, 'El certificado de este vecino aún no está aprobado.');
    }

    // Cargamos la vista que hiciste antes y le inyectamos los datos
    $pdf = Pdf::loadView('pdf.certificado-residencia', ['vecino' => $vecino]);

    // Forzamos la descarga del archivo con un nombre formal
    return $pdf->download('Certificado-Residencia-' . $vecino->rut . '.pdf');
})->name('vecino.certificado');
