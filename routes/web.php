<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PNUserController;
use App\Http\Controllers\Training\StudentController;
use App\Http\Controllers\Training\SchoolController;
use App\Http\Controllers\Training\ManageStudentController;
use App\Http\Controllers\Training\GradeSubmissionController;

// Welcome Route
Route::get('/', fn () => view('welcome'));

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('update-password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboards based on roles
    Route::get('/admin-dashboard', function () {
        return Gate::allows('admin-access') 
            ? view('admin.dashboard', ['title' => 'Admin Dashboard']) 
            : redirect('/login')->withErrors(['error' => 'Unauthorized access']);
    })->name('admin.dashboard');

    Route::get('/educator-dashboard', function () {
        return Gate::allows('educator-access') 
            ? view('educator.dashboard', ['title' => 'Educator Dashboard']) 
            : redirect('/login')->withErrors(['error' => 'Unauthorized access']);
    })->name('educator.dashboard');

    Route::get('/student-dashboard', function () {
        return Gate::allows('student-access') 
            ? view('student.dashboard', ['title' => 'Student Dashboard']) 
            : redirect('/login')->withErrors(['error' => 'Unauthorized access']);
    })->name('student.dashboard');

    // Training fallback route
    Route::get('/training', fn () => view('training.dashboard'));
});

// Admin: PNPH Users CRUD
Route::prefix('admin/pnph_users')->name('admin.pnph_users.')->group(function () {
    Route::get('/', [PNUserController::class, 'index'])->name('index');
    Route::get('/create', [PNUserController::class, 'create'])->name('create');
    Route::post('/', [PNUserController::class, 'store'])->name('store');
    Route::get('/{user_id}/edit', [PNUserController::class, 'edit'])->name('edit');
    Route::put('/{user_id}', [PNUserController::class, 'update'])->name('update');
    Route::delete('/{user_id}', [PNUserController::class, 'destroy'])->name('destroy');
    Route::get('/{user_id}', [PNUserController::class, 'show'])->name('show');
});

// Training Feature Views (no login required)
Route::view('/training-dashboard', 'training.dashboard', ['title' => 'Training Dashboard'])->name('training.dashboard');
Route::view('/analytics', 'training.analytics', ['title' => 'Analytics'])->name('analytics');
Route::view('/intervention-status', 'training.intervention', ['title' => 'Intervention Status'])->name('intervention.status');
Route::view('/profile', 'training.profile', ['title' => 'Profile'])->name('profile');

// Student Info
Route::get('/students-info', [StudentController::class, 'index'])->name('students.info');

// Manage Students
Route::prefix('manage-students')->name('manage.students.')->group(function () {
    Route::get('/', [ManageStudentController::class, 'index'])->name('index');
    Route::get('/{id}/edit', [ManageStudentController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ManageStudentController::class, 'update'])->name('update');
    Route::delete('/{id}', [ManageStudentController::class, 'destroy'])->name('destroy');

    // Schools
    Route::prefix('schools')->name('schools.')->group(function () {
        Route::get('/', [ManageStudentController::class, 'listSchools'])->name('index');
        Route::get('/create', [ManageStudentController::class, 'createSchool'])->name('create');
        Route::post('/', [ManageStudentController::class, 'storeSchool'])->name('store');
        Route::get('/{id}/view', [ManageStudentController::class, 'viewSchool'])->name('view');
        Route::get('/{id}/edit', [ManageStudentController::class, 'editSchool'])->name('edit');
        Route::put('/{id}', [ManageStudentController::class, 'updateSchool'])->name('update');
        Route::delete('/{id}', [ManageStudentController::class, 'destroySchool'])->name('destroy');

        // Classes under a school
        Route::prefix('{school}/classes')->name('classes.')->group(function () {
            Route::get('/create', [ManageStudentController::class, 'createClass'])->name('create');
            Route::post('/', [ManageStudentController::class, 'storeClass'])->name('store');
            Route::get('/{class}/view', [ManageStudentController::class, 'viewClass'])->name('view');
            Route::get('/{class}/edit', [ManageStudentController::class, 'editClass'])->name('edit');
            Route::put('/{class}', [ManageStudentController::class, 'updateClass'])->name('update');
            Route::delete('/{class}', [ManageStudentController::class, 'destroyClass'])->name('destroy');
            Route::get('/{class}/select-students', [ManageStudentController::class, 'selectStudents'])->name('select-students');
            Route::post('/{class}/select-students', [ManageStudentController::class, 'updateStudents'])->name('update-students');
        });
    });
});

// Grade Submission
Route::prefix('grade-submission')->name('grade.submission.')->group(function () {
    Route::get('/', [GradeSubmissionController::class, 'index'])->name('index');
    Route::get('/classes/{class_id}/students', [GradeSubmissionController::class, 'showStudents'])->name('students');
    Route::get('/classes/{class_id}/students/{student_id}/form', [GradeSubmissionController::class, 'showForm'])->name('form');
    Route::post('/classes/{class_id}/students/{student_id}/submit', [GradeSubmissionController::class, 'store'])->name('store');
    Route::get('/classes/{class_id}/monitor', [GradeSubmissionController::class, 'monitorGrades'])->name('monitor');
    Route::get('/classes/{class_id}/subjects', [GradeSubmissionController::class, 'viewSubjects'])->name('subjects');
});
