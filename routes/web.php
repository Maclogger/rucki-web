<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\GithubRecordController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [AppController::class, 'index'])->name('app.index');

Route::get('/lietadlo', function () {
    return Inertia::render('Lietadlo/Lietadlo');
})->name('lietadlo');

Route::get("/fetch-github-contributions", [GithubRecordController::class, "__invoke"]);

Route::get("/fetch-github-chart-data/{year}", [GithubController::class, "getGithubChartData"]);

require __DIR__.'/auth.php';
