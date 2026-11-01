<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseManagementController extends Controller
{
    /**
     * Display a listing of all courses (published & draft) with filtering and search.
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $query = Course::with('category')
            ->withCount(['lessons', 'enrollments']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'published') {
                $query->where('is_published', true);
            } elseif ($request->input('status') === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $courses = $query->latest()->paginate(10)->withQueryString();

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.courses.create', compact('categories'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $course = Course::create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'thumbnail' => $validated['thumbnail'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kursus "'.$course->title.'" berhasil dibuat.');
    }

    /**
     * Redirect show to lessons management for convenience.
     */
    public function show(Course $course): RedirectResponse
    {
        return redirect()->route('admin.courses.lessons.index', $course);
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $course->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'thumbnail' => $validated['thumbnail'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kursus "'.$course->title.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        if ($course->lessons()->exists()) {
            return redirect()
                ->route('admin.courses.index')
                ->with('error', 'Kursus tidak dapat dihapus karena masih memiliki materi pembelajaran. Hapus materi terlebih dahulu.');
        }

        $title = $course->title;
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kursus "'.$title.'" berhasil dihapus.');
    }
}

