<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LessonPlayerController extends Controller
{
    /**
     * Display the interactive course player and learning progress for the participant.
     */
    public function show(Course|string $courseSlug, int $lessonOrder = 1): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'peserta') {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $course = $courseSlug instanceof Course
            ? $courseSlug->load(['category', 'lessons' => fn ($q) => $q->orderBy('order', 'asc')])
            : Course::with(['category', 'lessons' => fn ($q) => $q->orderBy('order', 'asc')])
                ->where('slug', $courseSlug)
                ->firstOrFail();

        // Validasi keikutsertaan peserta pada kursus ini
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrollment) {
            return redirect()
                ->route('courses.show', $course->slug)
                ->with('error', 'Anda harus mendaftar kursus terlebih dahulu.');
        }

        $lessons = $course->lessons;

        // Ambil materi berdasarkan order (default ke materi pertama jika order tidak ditemukan)
        $currentLesson = $lessons->firstWhere('order', (int) $lessonOrder) ?? $lessons->first();

        if (! $currentLesson) {
            return redirect()
                ->route('courses.show', $course->slug)
                ->with('error', 'Kursus ini belum memiliki materi pembelajaran.');
        }

        // Ambil daftar ID materi pada kursus ini yang sudah diselesaikan oleh user
        $completedLessonIds = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $lessons->count();
        $completedCount = count($completedLessonIds);
        $progressPercentage = $totalLessons > 0
            ? (int) round(($completedCount / $totalLessons) * 100)
            : 0;

        // Cari materi sebelumnya dan selanjutnya berdasarkan urutan koleksi lessons
        $currentIndex = $lessons->search(fn (Lesson $item) => $item->id === $currentLesson->id);
        $previousLesson = ($currentIndex !== false && $currentIndex > 0)
            ? $lessons->get($currentIndex - 1)
            : null;
        $nextLesson = ($currentIndex !== false && $currentIndex < $totalLessons - 1)
            ? $lessons->get($currentIndex + 1)
            : null;

        return view('courses.player', compact(
            'course',
            'lessons',
            'currentLesson',
            'completedLessonIds',
            'progressPercentage',
            'completedCount',
            'totalLessons',
            'previousLesson',
            'nextLesson'
        ));
    }

    /**
     * Toggle the completion status of a lesson for the authenticated participant.
     */
    public function toggleComplete(Request $request, Lesson $lesson): RedirectResponse
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'peserta') {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $course = $lesson->course()->with('lessons')->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrollment) {
            return redirect()
                ->route('courses.show', $course->slug)
                ->with('error', 'Anda harus mendaftar kursus terlebih dahulu.');
        }

        $existingCompletion = LessonCompletion::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        $markedAsComplete = false;

        if ($existingCompletion) {
            $existingCompletion->delete();
            $statusMessage = 'Status selesai untuk materi "'.$lesson->title.'" telah dibatalkan.';
        } else {
            LessonCompletion::create([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'completed_at' => now(),
            ]);
            $markedAsComplete = true;
            $statusMessage = 'Materi "'.$lesson->title.'" berhasil ditandai selesai!';
        }

        // Periksa apakah seluruh materi dalam kursus ini sudah selesai
        $courseLessonIds = $course->lessons->pluck('id');
        $totalCourseLessons = $courseLessonIds->count();

        $completedCourseLessonsCount = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $courseLessonIds)
            ->count();

        $allLessonsCompleted = $totalCourseLessons > 0 && $completedCourseLessonsCount >= $totalCourseLessons;

        if ($allLessonsCompleted) {
            $enrollment->update(['status' => 'completed']);
        } else {
            $enrollment->update(['status' => 'active']);
        }

        // Saat user menandai materi selesai, cek materi berikutnya berdasarkan order
        if ($markedAsComplete) {
            $nextLesson = $course->lessons()
                ->where('order', '>', $lesson->order)
                ->orderBy('order', 'asc')
                ->first();

            if ($nextLesson) {
                return redirect()
                    ->route('lessons.player', [
                        'course' => $course->slug,
                        'lessonOrder' => $nextLesson->order,
                    ])
                    ->with('success', 'Materi "'.$lesson->title.'" selesai! Melanjutkan ke materi berikutnya.');
            }

            // Jika tidak ada materi berikutnya (materi terakhir telah selesai)
            return redirect()
                ->route('lessons.player', [
                    'course' => $course->slug,
                    'lessonOrder' => $lesson->order,
                ])
                ->with('success', $statusMessage)
                ->with('course_completed', true);
        }

        return redirect()
            ->route('lessons.player', [
                'course' => $course->slug,
                'lessonOrder' => $lesson->order,
            ])
            ->with('success', $statusMessage);
    }
}
