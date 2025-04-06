<?php

use App\Http\Controllers\BeamController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('BeamCalculator');
})->name('home');


Route::post('/calculate', [BeamController::class, 'calculate']);








require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
