<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;

Route::get('/', function () {
    return view('/pendaftaran/form');
});

Route::get('/pendaftaran/form', [PendaftaranController::class, 'create'])->name('pendaftaran.form');
Route::post('/pendaftaran/form', [PendaftaranController::class, 'store'])->name('pendaftaran.store');