<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TipoAuditoriaController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');


Route::get('/auditorias', [TipoAuditoriaController::class, 'index'])->name('tipos-auditorias-index');
Route::post('/auditorias', [TipoAuditoriaController::class, 'store'])->name('tipos-auditorias-create');
Route::put('auditorias/{id}', [TipoAuditoriaController::class, 'update'])->name('tipos-auditorias-edit');
