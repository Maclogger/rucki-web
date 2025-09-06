<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\GithubRecordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhotosController;
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
    Route::get("/photos-show/{fileName}", [PhotosController::class, 'show'])
        ->where('fileName', '.*')
        ->name('photos.show');
    Route::post('photos-upload', [PhotosController::class, "uploadPhotos"]);
    Route::get('/get-photos', [PhotosController::class, "getPhotos"]);
    Route::post('/delete-single-photo', [PhotosController::class, "deleteSinglePhoto"]);
    Route::post('/delete-multiple-photos', [PhotosController::class, "deleteMultiplePhotos"]);
});
