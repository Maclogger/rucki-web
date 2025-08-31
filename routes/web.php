<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\GithubRecordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [AppController::class, 'index'])->name('app.index');

Route::get('/lietadlo', function () {
    return Inertia::render('Lietadlo/Lietadlo');
})->name('lietadlo');

Route::get('login', [LoginController::class, "index"])->name("loginIndex");
Route::post('login', [LoginController::class, "login"])->name("login");

Route::get('/refresh-github-chart-data', [GithubController::class, "getInitialGithubStoreData"]);

Route::get("/fetch-github-chart-data/{year}", [GithubController::class, "getGithubChartData"]);

Route::middleware('auth')->group(function () {
    Route::get("/home", [HomeController::class, "index"])->name('home');
    Route::post("/logout", [LoginController::class, "logout"])->name('logout');
    Route::post("/fetch-github-contributions", [GithubRecordController::class, "__invoke"]);
    Route::inertia('/photos', "Photos/PhotosPage");
});
