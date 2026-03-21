<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\StudentController;


/*
|--------------------------------------------------------------------------
| Web Routes
|---------------------------------------------*-
-----------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/index', function () {
    return view('welcome');
});

Route::get('/', [MainController::class, 'main'])->name('login');



Route::get('/logout', [MainController::class, 'logout'])->name('logout');
Route::post('/authenticate', [MainController::class, 'auth_user'])->name('auth_user');


Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
    // Place all admin routes here
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin_profile', [MainController::class, 'admin_profile'])->name('admin_profile');
    Route::get('/schedule', [MainController::class, 'schedule'])->name('schedule');
    Route::get('/teacher_loadView', [MainController::class, 'teacher_loadView'])->name('teacher_loadView');
    Route::get('/section_loadView', [MainController::class, 'section_loadView'])->name('section_loadView');
    Route::get('/teacher_loads', [MainController::class, 'teacher_loads'])->name('teacher_loads');
    Route::get('/print_teacher_load/{id}/{year}', [MainController::class, 'print_teacher_load'])->name('teacher.print_pdf');
    Route::get('/section_loads', [MainController::class, 'section_loads'])->name('section_loads');
    Route::get('/faculty_list', [MainController::class, 'faculty_list'])->name('faculty_list');
    Route::get('/activity-log', [MainController::class, 'activityLogPage']);
    Route::get('/subject', [MainController::class, 'subject'])->name('subject');
    Route::get('/view_section', [MainController::class, 'view_section'])->name('view_section');
    Route::get('/view_subject', [MainController::class, 'view_subject'])->name('view_subject');
    Route::get('/view_student', [MainController::class, 'view_student'])->name('view_student');
    Route::get('/teachers', [MainController::class, 'teachers'])->name('teachers');
    Route::get('/view_teachers', [MainController::class, 'view_teachers'])->name('view_teachers');
    Route::get('/view_schedule', [MainController::class, 'view_schedule'])->name('view_schedule');
    Route::get('/view_grade1', [MainController::class, 'view_grade1'])->name('view_grade1');
    Route::get('/view_grade2', [MainController::class, 'view_grade2'])->name('view_grade2');
    Route::get('/teacher_status', [MainController::class, 'teacher_status'])->name('teacher_status');
    Route::get('/updateTeacherStatus', [MainController::class, 'updateTeacherStatus'])->name('updateTeacherStatus');
    Route::post('/save_teacher', [MainController::class, 'save_teacher'])->name('save_teacher');
    Route::post('/save_schedule', [MainController::class, 'save_schedule'])->name('save_schedule');
    Route::post('/save_subjects', [MainController::class, 'save_subjects'])->name('save_subjects');
    Route::post('/save_student', [MainController::class, 'save_student'])->name('save_student');
    Route::post('/save_section', [MainController::class, 'save_section'])->name('save_section');
    Route::post('/deact_teacher', [MainController::class, 'deact_teacher'])->name('deact_teacher');
    Route::post('/delete_schedule', [MainController::class, 'delete_schedule'])->name('delete_schedule');
    Route::post('/update_schedule', [MainController::class, 'update_schedule'])->name('update_schedule');
    Route::post('/update_subject', [MainController::class, 'update_subject'])->name('update_subject');
    Route::post('/update_section', [MainController::class, 'update_section'])->name('update_section');
    Route::post('/delete-schedule', [MainController::class, 'delete_schedule'])->name('delete_schedule');

   // Add {id} to the URL
    Route::post('/admin-profile/{id}', [MainController::class, 'adminProfile'])->name('adminProfile');

    Route::post('/update_teacher/{teachers_id}', [MainController::class, 'update_teacher'])->name('update_teacher');
    Route::post('/system/set-schoolyear', [MainController::class, 'set_system_schoolyear'])->name('system.setSchoolYear');
});

// Route::group(['prefix' => 'Teacher', 'middleware' => ['role:Teacher']], function () {
    // Place all teacher routes here
    Route::get('/TeacherUI', [MainController::class, 'TeacherUI'])->name('Teacher.TeacherUI');
    Route::post('/teacher/update/{id}', [MainController::class, 'Update_teacherProfile'])->name('Update_teacherProfile'); 




// });  