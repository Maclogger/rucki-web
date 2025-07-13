<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\GithubRecordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [AppController::class, 'index'])->name('app.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/lietadlo', function () {
    return Inertia::render('Lietadlo/Lietadlo');
})->name('lietadlo');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::post("/fetch-github-contributions", [GithubRecordController::class, "__invoke"]);

Route::get("/fetch-githubchartdata/{year}", [GithubController::class, "getGithubChartData"]);

require __DIR__.'/auth.php';
