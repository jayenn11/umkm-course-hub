<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CourseManagementController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LessonManagementController as AdminLessonController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonPlayerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('prevent-back');

// Rute Publik / Katalog Kursus (dengan proteksi prevent-back agar state auth di navbar tidak stale)
Route::middleware('prevent-back')->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
});

// Rute Peserta
Route::middleware(['auth', 'role:peserta', 'prevent-back'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('courses.my_courses');

    Route::get('/learn/{course:slug}/{lessonOrder?}', [LessonPlayerController::class, 'show'])->name('lessons.player');
    Route::post('/lessons/{lesson}/toggle-complete', [LessonPlayerController::class, 'toggleComplete'])->name('lessons.toggle_complete');
});

// Rute Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin', 'prevent-back'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Resource Kategori & Kursus
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('courses', AdminCourseController::class);

    // Nested Routes Materi Pembelajaran per Kursus
    Route::get('/courses/{course}/lessons', [AdminLessonController::class, 'index'])->name('courses.lessons.index');
    Route::get('/courses/{course}/lessons/create', [AdminLessonController::class, 'create'])->name('courses.lessons.create');
    Route::post('/courses/{course}/lessons', [AdminLessonController::class, 'store'])->name('courses.lessons.store');
    Route::post('/courses/{course}/lessons/reorder', [AdminLessonController::class, 'reorder'])->name('courses.lessons.reorder');
    Route::get('/courses/{course}/lessons/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('courses.lessons.edit');
    Route::put('/courses/{course}/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('courses.lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('courses.lessons.destroy');
});

// Rute Profil (Semua Pengguna Terautentikasi)
Route::middleware(['auth', 'prevent-back'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
