<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\dashboard\Admin\DashboardController as AdminDashboardController;


// Petani 
use App\Http\Controllers\dashboard\Petani\DashboardController as PetaniDashboardController;
use App\Http\Controllers\dashboard\Petani\ProfileController as PetaniProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});


// Route Auth  
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'registerUser'])->name('register.submit');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'loginUser'])->name('login.submit');

//Dashboard 
Route::middleware(['sessionuser', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});
// Route Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware(['sessionuser', 'role:petani'])->prefix('petani')->group(function () {
    Route::get('dashboard', [PetaniDashboardController::class, 'index'])->name('petani.dashboard');
    Route::get('profile', [PetaniProfileController::class, 'edit'])->name('petani.profile.edit');
    Route::post('profile', [PetaniProfileController::class, 'update'])->name('petani.profile.update');
});
