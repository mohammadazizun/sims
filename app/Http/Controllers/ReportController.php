<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;

class ReportController extends Controller
{
    public function students()
    {
        $totalStudents = Student::count();
        $byGender = Student::selectRaw('gender, count(*) as total')->groupBy('gender')->get();
        $byStatus = Student::selectRaw('status, count(*) as total')->groupBy('status')->get();
        $byClassroom = Classroom::withCount('students')->orderByDesc('students_count')->get();
        $latest = Student::with('classroom')->orderByDesc('created_at')->limit(20)->get();

        return view('reports.students', compact('totalStudents', 'byGender', 'byStatus', 'byClassroom', 'latest'));
    }
}
