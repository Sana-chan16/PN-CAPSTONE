<?php

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PNUserController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Training\StudentController;
use App\Http\Controllers\Training\SchoolController;
use App\Http\Controllers\Training\ManageStudentController;
use App\Http\Controllers\Training\GradeSubmissionController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes for CRUD
Route::get('/admin/pnph_users', [PNUserController::class, 'index'])->name('admin.pnph_users.index');
Route::get('/admin/pnph_users/create', [PNUserController::class, 'create'])->name('admin.pnph_users.create');
Route::post('/admin/pnph_users', [PNUserController::class, 'store'])->name('admin.pnph_users.store');
Route::get('/admin/pnph_users/{user_id}/edit', [PNUserController::class, 'edit'])->name('admin.pnph_users.edit');
Route::put('/admin/pnph_users/{user_id}', [PNUserController::class, 'update'])->name('admin.pnph_users.update');
Route::delete('/admin/pnph_users/{user_id}', [PNUserController::class, 'destroy'])->name('admin.pnph_users.destroy');
Route::get('/admin/pnph_users/{user_id}', [PNUserController::class, 'show'])->name('admin.pnph_users.show');

// Direct access to students info
Route::get('/students-info', [StudentController::class, 'index'])->name('students.info');

// Direct access to manage students
Route::get('/manage-students', [ManageStudentController::class, 'index'])->name('manage.students');
Route::get('/manage-students/{id}/edit', [ManageStudentController::class, 'edit'])->name('manage.students.edit');
Route::put('/manage-students/{id}', [ManageStudentController::class, 'update'])->name('manage.students.update');
Route::delete('/manage-students/{id}', [ManageStudentController::class, 'destroy'])->name('manage.students.destroy');

// School management routes
Route::get('/manage-students/schools', [ManageStudentController::class, 'listSchools'])->name('manage.schools');
Route::get('/manage-students/schools/create', [ManageStudentController::class, 'createSchool'])->name('manage.schools.create');
Route::post('/manage-students/schools', [ManageStudentController::class, 'storeSchool'])->name('manage.schools.store');
Route::get('/manage-students/schools/{id}/view', [ManageStudentController::class, 'viewSchool'])->name('manage.schools.view');
Route::get('/manage-students/schools/{id}/edit', [ManageStudentController::class, 'editSchool'])->name('manage.schools.edit');
Route::put('/manage-students/schools/{id}', [ManageStudentController::class, 'updateSchool'])->name('manage.schools.update');
Route::delete('/manage-students/schools/{id}', [ManageStudentController::class, 'destroySchool'])->name('manage.schools.destroy');
Route::post('/manage-students/schools/{school}/classes', [ManageStudentController::class, 'storeClass'])->name('manage.schools.classes.store');
Route::get('/manage-students/schools/{school}/classes/create', [ManageStudentController::class, 'createClass'])->name('manage.schools.classes.create');
Route::get('/manage-students/schools/{school}/classes/{class}/view', [ManageStudentController::class, 'viewClass'])->name('manage.classes.view');
Route::get('/manage-students/schools/{school}/classes/{class}/edit', [ManageStudentController::class, 'editClass'])->name('manage.classes.edit');
Route::put('/manage-students/schools/{school}/classes/{class}', [ManageStudentController::class, 'updateClass'])->name('manage.classes.update');
Route::delete('/manage-students/schools/{school}/classes/{class}', [ManageStudentController::class, 'destroyClass'])->name('manage.classes.destroy');
Route::get('/manage-students/schools/{school}/classes/{class}/select-students', [ManageStudentController::class, 'selectStudents'])->name('manage.classes.select-students');
Route::post('/manage-students/schools/{school}/classes/{class}/select-students', [ManageStudentController::class, 'updateStudents'])->name('manage.classes.update-students');

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Feature Routes (accessible without login)
Route::get('/training-dashboard', function () {
    return view('training.dashboard', ['title' => 'Training Dashboard']);
})->name('training.dashboard');

// Grade Submission Routes
Route::get('/grade-submission', [GradeSubmissionController::class, 'index'])->name('grade.submission');
Route::get('/grade-submission/classes/{class_id}/students', [GradeSubmissionController::class, 'showStudents'])->name('grade.submission.students');
Route::get('/grade-submission/classes/{class_id}/students/{student_id}/form', [GradeSubmissionController::class, 'showForm'])->name('grade.submission.form');
Route::post('/grade-submission/classes/{class_id}/students/{student_id}/submit', [GradeSubmissionController::class, 'store'])->name('grade.submission.store');
Route::get('/grade-submission/classes/{class_id}/monitor', [GradeSubmissionController::class, 'monitorGrades'])->name('grade.submission.monitor');
Route::get('/grade-submission/classes/{class_id}/subjects', [GradeSubmissionController::class, 'viewSubjects'])->name('grade.submission.subjects');

Route::get('/analytics', function () {
    return view('training.analytics', ['title' => 'Analytics']);
})->name('analytics');

Route::get('/intervention-status', function () {
    return view('training.intervention', ['title' => 'Intervention Status']);
})->name('intervention.status');

Route::get('/profile', function () {
    return view('training.profile', ['title' => 'Profile']);
})->name('profile');

Route::middleware('auth')->group(function () {
    // Change Password Routes
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->middleware('auth')->name('change-password');
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->middleware('auth')->name('update-password');

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin routes
    Route::get('/admin-dashboard', function () {
        if (Gate::allows('admin-access')) {
            return view('admin.dashboard', ['title' => 'Admin Dashboard']);
        }
        return redirect('/login')->withErrors(['error' => 'Unauthorized access']);
    })->name('admin.dashboard');    

    // Educator routes
    Route::get('/educator-dashboard', function () {
        if (Gate::allows('educator-access')) {
            return view('educator.dashboard', ['title' => 'Educator Dashboard']);
        }
        return redirect('/login')->withErrors(['error' => 'Unauthorized access']);
    })->name('educator.dashboard');

    // Student routes
    Route::get('/student-dashboard', function () {
        if (Gate::allows('student-access')) {
            return view('student.dashboard', ['title' => 'Student Dashboard']);
        }
        return redirect('/login')->withErrors(['error' => 'Unauthorized access']);
    })->name('student.dashboard');

    Route::get('/training', function () {
        return view('training.dashboard');
    });
});

 

