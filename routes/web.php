<?php

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PNUserController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Training\StudentController;
use App\Http\Controllers\Training\SchoolController;
use App\Http\Controllers\Training\ManageStudentController;

Route::get('/', function () {
    return view('welcome');
});

// Direct access to students info
Route::get('/students-info', [StudentController::class, 'index'])->name('students.info');

// Direct access to manage students
Route::get('/manage-students', [ManageStudentController::class, 'index'])->name('manage.students');
Route::get('/manage-students/{id}/edit', [ManageStudentController::class, 'edit'])->name('manage.students.edit');
Route::put('/manage-students/{id}', [ManageStudentController::class, 'update'])->name('manage.students.update');
Route::delete('/manage-students/{id}', [ManageStudentController::class, 'destroy'])->name('manage.students.destroy');

// School management routes
Route::get('/manage-schools', [ManageStudentController::class, 'listSchools'])->name('manage.schools');
Route::get('/manage-schools/create', [ManageStudentController::class, 'createSchool'])->name('manage.schools.create');
Route::post('/manage-schools', [ManageStudentController::class, 'storeSchool'])->name('manage.schools.store');
Route::get('/manage-schools/{id}/view', [ManageStudentController::class, 'viewSchool'])->name('manage.schools.view');
Route::get('/manage-schools/{id}/edit', [ManageStudentController::class, 'editSchool'])->name('manage.schools.edit');
Route::put('/manage-schools/{id}', [ManageStudentController::class, 'updateSchool'])->name('manage.schools.update');
Route::delete('/manage-schools/{id}', [ManageStudentController::class, 'destroySchool'])->name('manage.schools.destroy');
Route::post('/manage-schools/{school}/classes', [ManageStudentController::class, 'storeClass'])->name('manage.schools.classes.store');
Route::get('/manage-schools/{school}/classes/create', [ManageStudentController::class, 'createClass'])->name('manage.schools.classes.create');
Route::get('/manage-schools/{school}/classes/{class}/select-students', [ManageStudentController::class, 'selectStudents'])->name('manage.classes.select-students');
Route::post('/manage-schools/{school}/classes/{class}/select-students', [ManageStudentController::class, 'updateStudents'])->name('manage.classes.update-students');

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {

        // Change Password Routes
        Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->middleware('auth')->name('change-password');
        Route::post('/update-password', [AuthController::class, 'updatePassword'])->middleware('auth')->name('update-password');


        // Logout Route
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Admin Routes for CRUD
        
        


        

        // Admin routes
        Route::get('/admin-dashboard', function () {
            if (Gate::allows('admin-access')) {
                return view('admin.dashboard', ['title' => 'Admin Dashboard']); // Corrected view path
            }

            return redirect('/login')->withErrors(['error' => 'Unauthorized access']);
        })->name('admin.dashboard');    

        // Educator routes
        Route::get('/educator-dashboard', function () {
            if (Gate::allows('educator-access')) {
                return view('educator.dashboard', ['title' => 'Educator Dashboard']); // Corrected view path
            }

            return redirect('/login')->withErrors(['error' => 'Unauthorized access']);
        })->name('educator.dashboard');

        // Training routes
        Route::get('/training-dashboard', function () {
            if (Gate::allows('training-access')) {
                return view('training.dashboard', ['title' => 'Training Dashboard']); // Corrected view path
            }

            return redirect('/login')->withErrors(['error' => 'Unauthorized access']);
        })->name('training.dashboard');

        // Student routes
        Route::get('/student-dashboard', function () {
            if (Gate::allows('student-access')) {
                return view('student.dashboard', ['title' => 'Student Dashboard']); // Corrected view path
            }

            return redirect('/login')->withErrors(['error' => 'Unauthorized access']);
        })->name('student.dashboard');


            












        Route::get('/training', function () {
            return view('training.dashboard');
        });


 });

 

