<?php
use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;
Route::middleware(['api'])->prefix('dashboard')->group(function () {
    Route::resource('/', DashboardController::class)->except(['create', 'edit']);
    
});
