<?php

use App\Http\Controllers\AbsensiController;

Route::post('/absen-masuk', [AbsensiController::class, 'absenMasuk']);
Route::post('/absen-keluar', [AbsensiController::class, 'absenKeluar']);
Route::get('/absensi/{user_id}', [AbsensiController::class, 'getAbsensiByUser']);

