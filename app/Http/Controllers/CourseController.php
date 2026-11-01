<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of published courses with filtering and search.
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $query = Course::with(['category', 'lessons'])
            ->where('is_published', true);

        // Filter berdasarkan kategori (category_id atau slug)
        if ($request->filled('category')) {
            $categoryFilter = $request->input('category');
            $query->where(function ($q) use ($categoryFilter) {
                $q->where('category_id', $categoryFilter)
                    ->orWhereHas('category', function ($catQuery) use ($categoryFilter) {
                        $catQuery->where('slug', $categoryFilter);
                    });
            });
        } elseif ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Pencarian teks judul atau deskripsi
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $courses = $query->latest()->paginate(6)->withQueryString();

        $enrolledCourseIds = [];
        if (Auth::check() && Auth::user()->role === 'peserta') {
            $enrolledCourseIds = Auth::user()->enrollments()->pluck('course_id')->toArray();
        }

        return view('courses.index', compact('courses', 'categories', 'enrolledCourseIds'));
    }

    /**
     * Display the specified course detail and syllabus.
     */
    public function show(string $slug): View
    {
        $course = Course::with([
            'category',
            'lessons' => function ($query) {
                $query->orderBy('order', 'asc');
            },
        ])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = $course->enrollments()
                ->where('user_id', Auth::id())
                ->exists();
        }

        return view('courses.show', compact('course', 'isEnrolled'));
    }
}
