<?php
use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;
Route::middleware(['api'])->prefix('product')->group(function () {
    Route::resource('/', ProductController::class)->except(['create', 'edit']);
    Route::get('/{id}/recommend', [ProductController::class, 'recommend'])->middleware('auth:sanctum');
});
