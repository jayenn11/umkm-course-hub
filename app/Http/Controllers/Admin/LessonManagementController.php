<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonManagementController extends Controller
{
    /**
     * Display a listing of the lessons for a specific course.
     */
    public function index(Course $course): View
    {
        $course->load('category');
        $lessons = $course->lessons()->orderBy('order', 'asc')->get();

        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    /**
     * Show the form for creating a new lesson for the specified course.
     */
    public function create(Course $course): View
    {
        $nextOrder = ($course->lessons()->max('order') ?? 0) + 1;

        return view('admin.lessons.create', compact('course', 'nextOrder'));
    }

    /**
     * Store a newly created lesson in storage.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        $nextOrder = ($course->lessons()->max('order') ?? 0) + 1;

        $lesson = $course->lessons()->create([
            'title' => $validated['title'],
            'video_url' => $validated['video_url'] ?? null,
            'content' => $validated['content'] ?? null,
            'order' => $nextOrder,
        ]);

        return redirect()
            ->route('admin.courses.lessons.index', $course)
            ->with('success', 'Materi "'.$lesson->title.'" berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit(Course $course, Lesson $lesson): View
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Update the specified lesson in storage.
     */
    public function update(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        $lesson->update([
            'title' => $validated['title'],
            'video_url' => $validated['video_url'] ?? null,
            'content' => $validated['content'] ?? null,
        ]);

        return redirect()
            ->route('admin.courses.lessons.index', $course)
            ->with('success', 'Materi "'.$lesson->title.'" berhasil diperbarui.');
    }

    /**
     * Reorder lessons asynchronously via Drag & Drop (SortableJS).
     */
    public function reorder(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'orders' => ['required', 'array'],
            'orders.*' => ['required', 'integer', 'exists:lessons,id'],
        ]);

        foreach ($validated['orders'] as $index => $lessonId) {
            Lesson::where('id', $lessonId)
                ->where('course_id', $course->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Urutan materi berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified lesson from storage.
     */
    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $title = $lesson->title;
        $lesson->delete();

        // Rapikan ulang urutan setelah satu materi dihapus
        $remainingLessons = $course->lessons()->orderBy('order', 'asc')->get();
        foreach ($remainingLessons as $idx => $item) {
            $item->update(['order' => $idx + 1]);
        }

        return redirect()
            ->route('admin.courses.lessons.index', $course)
            ->with('success', 'Materi "'.$title.'" berhasil dihapus.');
    }
}
