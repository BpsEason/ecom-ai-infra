<?php
use Illuminate\Support\Facades\Route;
use Modules\CustomerService\Http\Controllers\CustomerServiceController;
Route::middleware(['api'])->prefix('customerservice')->group(function () {
    Route::resource('/', CustomerServiceController::class)->except(['create', 'edit']);
    Route::get('/{id}/wave-suggestion', [CustomerServiceController::class, 'suggestWave'])->middleware('auth:sanctum');
});
