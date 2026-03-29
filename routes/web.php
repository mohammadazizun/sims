<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DapodikController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('students.index') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('students/check', [StudentController::class, 'check'])->name('students.check');
    Route::get('students/check/{student}', [StudentController::class, 'checkShow'])->name('students.public.show');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('students/import', [StudentController::class, 'importForm'])->name('students.import.form');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('classrooms/import', [ClassroomController::class, 'importForm'])->name('classrooms.import.form');
    Route::post('classrooms/import', [ClassroomController::class, 'import'])->name('classrooms.import');
    Route::get('students/photos', [StudentController::class, 'photoUploadForm'])->name('students.photos.form');
    Route::post('students/photos', [StudentController::class, 'photoUpload'])->name('students.photos.upload');
    Route::get('dapodik/settings', [DapodikController::class, 'settings'])->name('dapodik.settings');
    Route::post('dapodik/settings', [DapodikController::class, 'save'])->name('dapodik.settings.save');
    Route::get('dapodik/sync', [DapodikController::class, 'syncForm'])->name('dapodik.sync.form');
    Route::post('dapodik/sync/fetch', [DapodikController::class, 'fetch'])->name('dapodik.sync.fetch');
    Route::post('dapodik/sync/push', [DapodikController::class, 'push'])->name('dapodik.sync.push');
    Route::get('reports/students', [ReportController::class, 'students'])->name('reports.students');
    Route::get('students/archive', [StudentController::class, 'archive'])->name('students.archive');
    Route::get('students/{student}/promote', [StudentController::class, 'showPromoteForm'])->name('students.promote.form');
    Route::post('students/{student}/promote', [StudentController::class, 'promote'])->name('students.promote');
    Route::post('students/{student}/graduate', [StudentController::class, 'graduate'])->name('students.graduate');
    Route::get('classrooms/{classroom}/assign', [ClassroomController::class, 'assign'])->name('classrooms.assign');
    Route::post('classrooms/{classroom}/assign-student', [ClassroomController::class, 'assignStudent'])->name('classrooms.assign.student');
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('students', StudentController::class);

    Route::middleware([\App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
