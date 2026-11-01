<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard view with statistical metrics and recent enrollments.
     */
    public function index(): View
    {
        $totalCourses = Course::count();
        $totalLessons = Lesson::count();
        $totalPeserta = User::where('role', 'peserta')->count();
        $totalEnrollments = Enrollment::count();

        $recentEnrollments = Enrollment::with(['user', 'course.category'])
            ->latest('enrolled_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCourses',
            'totalLessons',
            'totalPeserta',
            'totalEnrollments',
            'recentEnrollments'
        ));
    }
}
