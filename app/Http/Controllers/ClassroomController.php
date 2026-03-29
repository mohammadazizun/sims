<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Services\DapodikClassroomImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::orderBy('name')->simplePaginate(20);

        return view('classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('classrooms.create');
    }

    public function importForm()
    {
        return view('classrooms.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'data_file' => 'required|file|mimes:csv,txt,json,zip',
        ]);

        $rows = DapodikClassroomImporter::parseFile($request->file('data_file'));
        if (empty($rows)) {
            return Redirect::back()->with('error', 'Tidak ada data kelas valid di file import. Periksa kembali header dan format file.')->withInput();
        }

        $imported = 0;

        foreach ($rows as $row) {
            $classroom = Classroom::updateOrCreate(
                ['name' => $row['name']],
                Arr::except($row, ['name'])
            );

            if ($classroom->wasRecentlyCreated) {
                $imported++;
            }
        }

        if ($imported === 0) {
            return Redirect::back()->with('error', 'Tidak ada kelas valid yang berhasil diimpor. Pastikan kolom name atau kelas diisi dengan benar.')->withInput();
        }

        return Redirect::route('classrooms.index')->with('success', "Berhasil mengimpor $imported kelas dari Dapodik.");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:2000',
        ]);

        $classroom = Classroom::create($request->only(['name', 'grade', 'major', 'academic_year', 'description']));

        return Redirect::route('classrooms.show', $classroom)->with('success', 'Kelas berhasil dibuat.');
    }

    public function show(Classroom $classroom)
    {
        $classroom->load('students');

        return view('classrooms.show', compact('classroom'));
    }

    public function assign(Request $request, Classroom $classroom)
    {
        $classroom->load('students');

        $majorOptions = Student::whereNotNull('major')
            ->orderBy('major')
            ->pluck('major')
            ->unique();

        $majorFilter = $request->query('major', $classroom->major);

        $availableStudents = Student::with('classroom')
            ->where('status', 'Aktif')
            ->when($majorFilter, function ($query, $majorFilter) {
                return $query->where('major', $majorFilter);
            })
            ->where(function ($query) use ($classroom) {
                $query->whereNull('classroom_id')
                    ->orWhere('classroom_id', '!=', $classroom->id);
            })
            ->orderBy('full_name')
            ->get();

        return view('classrooms.assign', compact('classroom', 'majorOptions', 'majorFilter', 'availableStudents'));
    }

    public function edit(Classroom $classroom)
    {
        return view('classrooms.edit', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:2000',
        ]);

        $classroom->update($request->only(['name', 'grade', 'major', 'academic_year', 'description']));

        return Redirect::route('classrooms.show', $classroom)->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function assignStudent(Request $request, Classroom $classroom)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::findOrFail($request->input('student_id'));
        $student->classroom_id = $classroom->id;
        $student->major = $student->major ?: $classroom->major;
        $student->save();

        return Redirect::route('classrooms.show', $classroom)->with('success', "Siswa {$student->full_name} berhasil dimasukkan ke kelas {$classroom->name}.");
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return Redirect::route('classrooms.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
