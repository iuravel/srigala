<?php

use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     //return view('welcome');
//     return redirect()->route('dashboard');
// });
Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return response()->json([
        'output' => Artisan::output(),
        'exit_code' => $exitCode,
    ]);
});
Route::get('/view-cache', function () {
    $exitCode = Artisan::call('view:cache');
    return response()->json([
        'output' => Artisan::output(),
        'exit_code' => $exitCode,
    ]);
});
Route::get('/event-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return response()->json([
        'output' => Artisan::output(),
        'exit_code' => $exitCode,
    ]);
});
Route::get('/link-storage', function () {
    $exitCode = Artisan::call('storage:link');
    return response()->json([
        'output' => Artisan::output(),
        'exit_code' => $exitCode,
    ]);
});
Route::get('/migrate-fresh-seed', [ArtisanController::class, 'runMigrateFreshSeed'])->name('migrate.fresh.seed');
Route::get('/key-generate', [ArtisanController::class, 'runKeyGenerate'])->name('key.generate');
Route::get('/config-clear', [ArtisanController::class, 'runConfigClear'])->name('config.clear');
Route::get('/config-cache', [ArtisanController::class, 'runConfigCache'])->name('config.cache');
Route::get('/route-clear', [ArtisanController::class, 'runRouteClear'])->name('route.clear');
Route::get('/view-clear', [ArtisanController::class, 'runViewClear'])->name('view.clear');
Route::get('/cache-clear', [ArtisanController::class, 'runCacheClear'])->name('cache.clear');
Route::get('/event-clear', [ArtisanController::class, 'runEventClear'])->name('event.clear');
Route::get('/optimize', [ArtisanController::class, 'runOptimize'])->name('optimize');
Route::get('/storage-link', [ArtisanController::class, 'runStorageLink'])->name('storage.link');
Route::get('/filament-clear-cached-components', [ArtisanController::class, 'runFilamentClearCachedComponents'])->name('filament.clear.cached.components');
Route::get('/filament-cache-components', [ArtisanController::class, 'runFilamentCacheComponents'])->name('filament.cache.components');
Route::get('/icon-cache', [ArtisanController::class, 'runIconCache'])->name('icon.cache');

Route::get('/pdf/kgb-militer-pdf/{record}', [PdfController::class, 'previewKgbMil'])->name('kgb.militer.preview');
Route::get('/pdf/kgb-asn-pdf/{record}', [PdfController::class, 'previewKgbAsn'])->name('kgb.asn.preview');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//require __DIR__.'/auth.php';
