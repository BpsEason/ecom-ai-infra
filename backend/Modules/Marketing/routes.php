<?php
use Illuminate\Support\Facades\Route;
use Modules\Marketing\Http\Controllers\MarketingController;
Route::middleware(['api'])->prefix('marketing')->group(function () {
    Route::resource('/', MarketingController::class)->except(['create', 'edit']);
    Route::post('/generate-copy', [MarketingController::class, 'generateCopy'])->middleware('auth:sanctum');
});
