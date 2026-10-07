<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Vecino;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});
Route::redirect('/register', '/admin/login');
Route::get('/vecinos/{id}/certificado', function ($id) {
    $vecino = Vecino::findOrFail($id);
    $usuario = Auth::user();

    $esDueno = $vecino->user_id === $usuario->id;
    $esAdminCentral = $usuario->esAdminCentral();
    $esDirectivaDeSuOrganizacion = in_array($usuario->role, User::ROLES_DIRECTIVA)
        && $vecino->user?->organizacion_id === $usuario->organizacion_id;

    if (! $esDueno && ! $esAdminCentral && ! $esDirectivaDeSuOrganizacion) {
        abort(403, 'No tienes permiso para descargar este certificado.');
    }

    if ($vecino->estado !== 'aprobado') {
        abort(403, 'El certificado de este vecino aún no está aprobado.');
    }

    $pdf = Pdf::loadView('pdf.certificado-residencia', ['vecino' => $vecino]);

    return $pdf->download('Certificado-Residencia-' . $vecino->rut . '.pdf');
})->middleware('auth')->name('vecino.certificado');
Route::get('/salir', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
});
