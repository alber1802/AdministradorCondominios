<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Generate\GenerateController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('paginaPrincipal.index');
});

Route::post('/generate-factura', [GenerateController::class, 'generate'])->name('factura.generate');

Route::view('/footer', 'paginaPrincipal.partials.footer')->name('footer');

// Rutas de verificación de email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/intelliTower');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

