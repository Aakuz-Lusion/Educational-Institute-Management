<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Student\HomeworkController;
use App\Http\Controllers\Student\InvoiceController;
use Illuminate\Support\Facades\Route;

// Public home
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Role-based dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role) {
            'admin'   => view('admin.dashboard'),
            'teacher' => view('teacher.dashboard'),
            'student' => view('student.dashboard'),
            default   => view('staff.dashboard'),
        };
    })->name('dashboard');

    // Profile routes (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // User management
        Route::resource('users', UserController::class);
        Route::get('users/{user}/edit-password', [UserController::class, 'editPassword'])->name('users.edit-password');
        Route::put('users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');

        // Class and Section management
        Route::resource('classes', ClassController::class);
        Route::resource('sections', SectionController::class);

        // Fee management (coming later)
        // Route::resource('fee-structures', FeeStructureController::class);
        // Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    });

Route::middleware(['auth', 'verified', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::resource('homeworks', \App\Http\Controllers\Teacher\HomeworkController::class);
        Route::get('submissions/{homework}', [\App\Http\Controllers\Teacher\HomeworkController::class, 'submissions'])->name('homeworks.submissions');
        Route::post('submissions/grade/{submission}', [\App\Http\Controllers\Teacher\HomeworkController::class, 'grade'])->name('submissions.grade');
    });

Route::middleware(['auth', 'verified', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('homeworks', [HomeworkController::class, 'index'])->name('homeworks.index');
        Route::get('homeworks/{homework}', [HomeworkController::class, 'show'])->name('homeworks.show');
        Route::post('homeworks/submit/{homework}', [HomeworkController::class, 'submit'])->name('homeworks.submit');
        Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    });

require __DIR__.'/auth.php';