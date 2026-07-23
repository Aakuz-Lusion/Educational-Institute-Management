<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Teacher\TeacherLoginController;
use App\Http\Controllers\Teacher\HomeworkController as TeacherHomeworkController;
use App\Http\Controllers\Student\HomeworkController as StudentHomeworkController;
use App\Http\Controllers\Student\InvoiceController;


Route::get('/', function () {
    return view('landing');
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default   => view('staff.dashboard'),
        };
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');



Route::get('/teacher/login', [TeacherLoginController::class, 'showLoginForm'])->name('teacher.login');
Route::post('/teacher/login', [TeacherLoginController::class, 'login'])->name('teacher.login.post');



Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/schedule', [AdminController::class, 'schedule'])->name('schedule');
        Route::get('/change-password/{id}', [AdminController::class, 'showChangePassword'])->name('change-password');
        Route::post('/update-password/{id}', [AdminController::class, 'updatePassword'])->name('update-password');
        Route::get('/generate-schedule', [AdminController::class, 'generateSchedule'])->name('generate-schedule');
        Route::get('/replacement/{id}', [AdminController::class, 'showReplacement'])->name('replacement');
        Route::post('/assign-replacement/{id}', [AdminController::class, 'assignReplacement'])->name('assign-replacement');
        Route::get('/schedule/clear', [AdminController::class, 'clearSchedules'])->name('schedule.clear');
        Route::get('/schedule/generate/{grade}', [AdminController::class, 'generateGradeSchedule'])->name('schedule.generate-grade');

        Route::resource('users', UserController::class);
        Route::get('users/{user}/edit-password', [UserController::class, 'editPassword'])->name('users.edit-password');
        Route::put('users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');

        Route::resource('classes', ClassController::class);
        Route::resource('sections', SectionController::class);
    });


Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        Route::get('/dashboard', [TeacherLoginController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [TeacherLoginController::class, 'logout'])->name('logout');
        Route::post('/mark-unavailable', [TeacherLoginController::class, 'markUnavailable'])->name('mark-unavailable');
        Route::post('/mark-available', [TeacherLoginController::class, 'markAvailable'])->name('mark-available');
        Route::get('/profile', [TeacherLoginController::class, 'profile'])->name('profile');
        Route::post('/profile', [TeacherLoginController::class, 'updateProfile'])->name('profile.update');

        Route::resource('homeworks', TeacherHomeworkController::class);
        Route::get('submissions/{homework}', [TeacherHomeworkController::class, 'submissions'])->name('homeworks.submissions');
        Route::post('submissions/grade/{submission}', [TeacherHomeworkController::class, 'grade'])->name('submissions.grade');
    });


Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('homeworks', [StudentHomeworkController::class, 'index'])->name('homeworks.index');
        Route::get('homeworks/{homework}', [StudentHomeworkController::class, 'show'])->name('homeworks.show');
        Route::post('homeworks/submit/{homework}', [StudentHomeworkController::class, 'submit'])->name('homeworks.submit');
        Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    });

Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
Route::get('/teachers/search', [TeacherController::class, 'search'])->name('teachers.search');
Route::get('/teachers/toggle/{id}', [TeacherController::class, 'toggleStatus'])->name('teachers.toggle');

Route::get('/test', function() {
    return 'Hello World!';
});

require __DIR__.'/auth.php';