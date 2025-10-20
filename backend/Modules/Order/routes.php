<?php
use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;
Route::middleware(['api'])->prefix('order')->group(function () {
    Route::resource('/', OrderController::class)->except(['create', 'edit']);
    Route::get('/{id}/eta', [OrderController::class, 'eta'])->middleware('auth:sanctum');
});
