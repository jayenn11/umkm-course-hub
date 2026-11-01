<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    /**
     * Store a newly created course enrollment for the authenticated peserta.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'peserta') {
            abort(403, 'Akses Ditolak: Hanya peserta yang dapat mendaftar kursus.');
        }

        if (! $course->is_published) {
            abort(404, 'Kursus tidak ditemukan atau belum dipublikasikan.');
        }

        Enrollment::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'enrolled_at' => now(),
                'status' => 'active',
            ]
        );

        $firstLesson = $course->lessons()->orderBy('order')->first();

        if ($firstLesson) {
            return redirect()
                ->route('lessons.player', [$course->slug, $firstLesson->order])
                ->with('success', 'Selamat! Anda berhasil terdaftar pada kursus "'.$course->title.'". Selamat belajar!');
        }

        return redirect()
            ->route('courses.show', $course->slug)
            ->with('success', 'Selamat! Anda berhasil terdaftar pada kursus "'.$course->title.'".');
    }

    /**
     * Display the list of courses enrolled by the authenticated peserta.
     */
    public function myCourses(): View
    {
        $enrollments = Auth::user()
            ->enrollments()
            ->with(['course.category', 'course.lessons' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->latest('enrolled_at')
            ->paginate(6);

        return view('courses.my_courses', compact('enrollments'));
    }
}
