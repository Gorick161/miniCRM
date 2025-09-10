<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DealController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController; // <— NEU

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Optional: Startseite direkt aufs Dashboard
Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Deals (Kanban-Board + Stage-Update)
    Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');

    // Companies (Index + Detail)
    Route::resource('companies', CompanyController::class)->only(['index', 'show']);

    // Contacts (Index + Detail)
    Route::resource('contacts', ContactController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
