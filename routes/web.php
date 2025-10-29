<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TipoAuditoriaController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\NaoConformidadeController;
use App\Http\Controllers\NaoConformidadeControllerController;
use App\Http\Controllers\RedmineController;



Route::get('/tipo-auditorias', [TipoAuditoriaController::class, 'index'])->name('tipos-auditorias-index');
Route::post('/tipo-auditorias', [TipoAuditoriaController::class, 'store'])->name('tipos-auditorias-create');
Route::put('/tipo-auditorias/{id}', [TipoAuditoriaController::class, 'update'])->name('tipos-auditorias-edit');
Route::delete('/tipo-auditorias/{id}', [TipoAuditoriaController::class, 'destroy'])->name('tipos-auditorias-destroy');

Route::get('/auditorias', [AuditoriaController::class, 'index'])->name('auditorias-index');
Route::post('/auditorias', [AuditoriaController::class, 'store'])->name('auditorias-create');
Route::put('/auditorias/{id}', [AuditoriaController::class, 'update'])->name('auditorias-update');
Route::delete('/auditorias/{id}', [AuditoriaController::class, 'destroy'])->name('auditorias-destroy');


Route::get('/nao-conformidades', [NaoConformidadeController::class, 'index'])->name('nao-conformidades-index');
Route::post('/nao-conformidades', [NaoConformidadeController::class, 'store'])->name('nao-conformidades-create');
Route::put('/nao-conformidades/{id}', [NaoConformidadeController::class, 'update'])->name('nao-conformidades-update');
Route::delete('/nao-conformidades/{id}', [NaoConformidadeController::class, 'destroy'])->name('nao-conformidades-destroy');






// Rotas para integração com Redmine
Route::get('/redmine', [RedmineController::class, 'index'])->name('redmine');
