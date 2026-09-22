<?php

use Illuminate\Support\Facades\Route;
use App\Models\Vecino;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/vecinos/{id}/certificado', function ($id) {
    $vecino = Vecino::findOrFail($id);

    if ($vecino->estado !== 'aprobado') {
        abort(403, 'El certificado de este vecino aún no está aprobado.');
    }

    $pdf = Pdf::loadView('pdf.certificado-residencia', ['vecino' => $vecino]);

    return $pdf->download('Certificado-Residencia-' . $vecino->rut . '.pdf');
})->name('vecino.certificado');
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');