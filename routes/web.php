<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/address', [EmployeeController::class, 'showLocation'])->name('address');
Route::resource('/employee', EmployeeController::class);
Route::get('/absensi/{id}', [EmployeeController::class, 'absensi'])->name('absensi');
Route::post('/insert-absensi', [EmployeeController::class, 'insertAbsensi'])->name('insert-absensi');
Route::post('/insert-late-absensi', [EmployeeController::class, 'lateAbsensi'])->name('late-absensi');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
