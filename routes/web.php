<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;



Route::get('students',              [StudentController::class, 'index']);
Route::get('/fetch-students',       [StudentController::class, 'fetchstudent']);
Route::post('students',             [StudentController::class, 'store']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
