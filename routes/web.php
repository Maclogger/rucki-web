<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\BufferController;
use App\Http\Controllers\ContactMailController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\GithubRecordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FilesController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', "AppPage");
Route::inertia('/lietadlo', "Lietadlo/Lietadlo");

Route::get('login', [LoginController::class, "index"])->name("loginIndex");
Route::post('login', [LoginController::class, "login"])->name("login");

Route::get('/refresh-github-chart-data', [GithubController::class, "getInitialGithubStoreData"]);
Route::get('/fetch-github-chart-data/{year}', [GithubController::class, "getGithubChartData"]);

// BUFFER
Route::inertia('/buffer', "Buffer/BufferUpload/BufferPage");
Route::get('/buffer/{code}', function ($code) {
    return Inertia::render('Buffer/BufferUpload/BufferPage', [
        'code' => $code,
    ]);
});
Route::post('buffer/files-upload', [BufferController::class, "uploadFiles"]);
Route::get('/fetch-public-store', [AppController::class, "getPublicStoreData"]);

Route::post('/contact', [ContactMailController::class, "sendContactMail"])
    ->middleware('throttle:contact');

// AUTH
Route::middleware('auth')->group(function () {
    Route::inertia('/buffer-codes', "Buffer/BufferCodes/BufferCodes");
    Route::get('/fetch-buffer-codes', [BufferController::class, "getBufferCodes"]);
    Route::post('/new-buffer-code', [BufferController::class, "store"]);
    Route::post('/delete-buffer-code', [BufferController::class, "delete"]);
    Route::post('/update-buffer-code', [BufferController::class, "update"]);
    Route::inertia('/buffer-secured', "Buffer/BufferUpload/SecuredBufferPage");
    Route::inertia('/github-secured', "GitHub/GitHubPage");
    Route::inertia('/home', "Home/Home");
    Route::post("/logout", [LoginController::class, "logout"])->name('logout');
    Route::post("/fetch-github-contributions", [GithubRecordController::class, "__invoke"]);
    Route::inertia('/new_files', "NewFiles/NewFilesPage");
    Route::get("/photos-show/{fileName}", [FilesController::class, 'show'])
        ->where('fileName', '.*')
        ->name('photos.show');
    Route::post('files-upload', [FilesController::class, "uploadFiles"]);
    Route::get('/get-files', [FilesController::class, "getFiles"]);
    Route::post('/delete-single-file', [FilesController::class, "deleteSingleFile"]);
    Route::post('/delete-multiple-files', [FilesController::class, "deleteMultipleFiles"]);
    Route::get('/debug-button-pressed', [FilesController::class, "debugButtonPressed"]);
    Route::get('/download/{fileId}', [FileDownloadController::class, "download"]);
    Route::post('/download-multiple', [FileDownloadController::class, "downloadFilesInZip"]);
});
