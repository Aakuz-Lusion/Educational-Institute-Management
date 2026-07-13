<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return view('admin.dashboard');
    }

    if ($user->role === 'teacher') {
        return view('teacher.dashboard');
    }

    if ($user->role === 'student') {
        return view('student.dashboard');
    }

    return view('staff.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('homeworks', [App\Http\Controllers\Student\HomeworkController::class, 'index'])->name('homeworks.index');
    Route::get('homeworks/{homework}', [App\Http\Controllers\Student\HomeworkController::class, 'show'])->name('homeworks.show');
    Route::post('homeworks/submit/{homework}', [App\Http\Controllers\Student\HomeworkController::class, 'submit'])->name('homeworks.submit');
    Route::get('invoices', [App\Http\Controllers\Student\InvoiceController::class, 'index'])->name('invoices.index');
});
