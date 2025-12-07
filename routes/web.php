<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BoutiquierController;
use App\Http\Controllers\Boutiquier\DashboardController as BoutiquierDashboardController;
use App\Http\Controllers\Boutiquier\ClientController;
use App\Http\Controllers\Boutiquier\CreditController;
use App\Http\Controllers\ClientAccessController;

/*
|--------------------------------------------------------------------------
| Accueil
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Auth Admin & Boutiquier
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('boutiquiers', BoutiquierController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| BOUTIQUIER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'boutiquier', 'checkStatus'])
    ->prefix('boutiquier')
    ->name('boutiquier.')
    ->group(function () {
        Route::get('/dashboard', [BoutiquierDashboardController::class, 'index'])->name('dashboard');
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
        Route::get('/clients/{client}/credits', [ClientController::class, 'credits'])->name('clients.credits');
        Route::get('/credits/create/{client}', [CreditController::class, 'create'])->name('credits.create');
        Route::post('/credits', [CreditController::class, 'store'])->name('credits.store');
        Route::post('/credits/{credit}/paye', [CreditController::class, 'marquerCommePaye'])->name('credits.paye');
    });

/*
|--------------------------------------------------------------------------
| CLIENT - Connexion par code unique (100 % FONCTIONNEL)
|--------------------------------------------------------------------------
*/
Route::get('/client', [ClientAccessController::class, 'showLoginForm'])->name('client.login');
Route::post('/client', [ClientAccessController::class, 'loginWithCode'])->name('client.login.submit');

Route::middleware('client')->group(function () {
    Route::get('/client/dashboard', [ClientAccessController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/client/logout', [ClientAccessController::class, 'logout'])->name('client.logout');
});