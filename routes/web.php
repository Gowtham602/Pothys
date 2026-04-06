<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ImageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//  HOME → MERGE PAGE
Route::get('/', function () {
    // return view('imageupload'); // merge UI
       return redirect()->route('login');
});

//  MERGE PROCESS
Route::post('/process-images', [ImageController::class, 'process'])
    ->name('image.process');

//  SHORT URL (PUBLIC)
Route::get('/s/{code}', [ImageController::class, 'redirect'])
    ->name('short.url');


// merge and short url for 
Route::post('/save-image', [ImageController::class, 'saveImage'])->name('save.image');

// =========================
// AUTH REQUIRED
// =========================
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [ImageController::class, 'index'])
        ->name('dashboard');

    // MOBILE ANALYTICS
    Route::get('/next', [ImageController::class, 'mobile'])
        ->name('next');

    Route::get('/get-images', [ImageController::class, 'getImages'])->name('get.images');

    // UPLOAD (OLD FEATURE)
    Route::post('/upload', [ImageController::class, 'store'])
        ->name('image.upload');
});


// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';