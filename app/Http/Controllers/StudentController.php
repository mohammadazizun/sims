<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\DapodikSetting;
use App\Models\Student;
use App\Services\DapodikStudentImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('name', ''));
        $classroomId = $request->query('classroom_id');
        $perPage = (int) $request->query('per_page', 20);
        $allowedPerPage = [10, 20, 50, 100];
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 20;
        }

        $classrooms = Classroom::orderBy('name')->get();

        $studentsQuery = Student::with('classroom')
            ->where(function ($queryBuilder) {
                $queryBuilder->whereNull('status')
                    ->orWhere('status', 'Aktif');
            })
            ->orderBy('full_name');

        if ($query !== '') {
            $studentsQuery->where('full_name', 'like', '%'.$query.'%');
        }

        if ($classroomId) {
            $studentsQuery->where('classroom_id', $classroomId);
        }

        $students = $studentsQuery->simplePaginate($perPage)->withQueryString();

        return view('students.index', compact('students', 'classrooms', 'query', 'classroomId', 'perPage', 'allowedPerPage'));
    }

    public function archive(Request $request)
    {
        $query = trim((string) $request->query('name', ''));
        $status = $request->query('status');
        $graduationYear = $request->query('graduation_year');

        $statusOptions = ['Lulus' => 'Lulus', 'Mutasi' => 'Mutasi', 'Keluar' => 'Keluar'];
        $yearOptions = Student::whereNotNull('graduation_year')
            ->orderByDesc('graduation_year')
            ->pluck('graduation_year')
            ->unique();

        $studentsQuery = Student::with('classroom')
            ->whereNotNull('status')
            ->where('status', '!=', 'Aktif')
            ->orderBy('full_name');

        if ($query !== '') {
            $studentsQuery->where('full_name', 'like', '%'.$query.'%');
        }

        if ($status) {
            $studentsQuery->where('status', $status);
        }

        if ($graduationYear) {
            $studentsQuery->where('graduation_year', $graduationYear);
        }

        $students = $studentsQuery->paginate(20)->withQueryString();

        return view('students.archive', compact('students', 'query', 'status', 'graduationYear', 'yearOptions', 'statusOptions'));
    }

    public function showPromoteForm(Student $student)
    {
        if ($student->status && strtolower($student->status) !== 'aktif') {
            return Redirect::back()->with('error', 'Hanya siswa aktif yang dapat dinaikkan kelas.');
        }

        $classrooms = Classroom::orderBy('name')->get();

        return view('students.promote', compact('student', 'classrooms'));
    }

    public function promote(Request $request, Student $student)
    {
        if ($student->status && strtolower($student->status) !== 'aktif') {
            return Redirect::back()->with('error', 'Hanya siswa aktif yang dapat dinaikkan kelas.');
        }

        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        if ($student->classroom_id == $validated['classroom_id']) {
            return Redirect::back()->with('error', 'Silakan pilih kelas yang berbeda untuk menaikkan siswa.');
        }

        $student->classroom_id = $validated['classroom_id'];
        $student->status = 'Aktif';
        $student->save();

        return Redirect::route('students.index')->with('success', "Siswa {$student->full_name} berhasil dinaikkan ke kelas baru.");
    }

    public function graduate(Student $student)
    {
        if ($student->status && strtolower($student->status) !== 'aktif') {
            return Redirect::back()->with('error', 'Hanya siswa aktif yang dapat diluluskan.');
        }

        $student->status = 'Lulus';
        $student->graduation_year = date('Y');
        $student->save();

        return Redirect::back()->with('success', "Siswa {$student->full_name} telah dinyatakan lulus.");
    }

    public function create()
    {
        $classrooms = Classroom::orderBy('name')->get();

        return view('students.create', compact('classrooms'));
    }

    public function check(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $settings = DapodikSetting::first();
        $publicSearchEnabled = $settings ? $settings->public_search_enabled : true;
        $students = collect();

        if ($publicSearchEnabled && $query !== '') {
            $students = Student::with('classroom')
                ->where(function ($q) use ($query) {
                    $q->where('nisn', $query)
                        ->orWhere('nis', $query)
                        ->orWhere('nik', $query)
                        ->orWhere('full_name', 'like', '%'.$query.'%');
                })
                ->orderBy('full_name')
                ->limit(50)
                ->get();
        }

        return view('students.check', compact('students', 'query', 'publicSearchEnabled'));
    }

    public function checkShow(Student $student)
    {
        $settings = DapodikSetting::first();
        if (!($settings->public_search_enabled ?? false)) {
            return redirect()->route('students.check')->with('error', 'Pencarian publik saat ini dinonaktifkan oleh admin.');
        }

        return view('students.show', compact('student'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateStudent($request);
        $photo = $validated['photo'] ?? null;
        unset($validated['photo']);

        $student = Student::create($validated);
        if ($photo) {
            $this->saveStudentPhoto($student, $photo);
        }

        return Redirect::route('students.show', $student)->with('success', 'Data siswa berhasil dibuat.');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::orderBy('name')->get();

        return view('students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $this->validateStudent($request);
        $photo = $validated['photo'] ?? null;
        unset($validated['photo']);

        $student->update($validated);
        if ($photo) {
            $this->saveStudentPhoto($student, $photo);
        }

        return Redirect::route('students.show', $student)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return Redirect::route('students.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function photoUploadForm()
    {
        return view('students.photos');
    }

    public function photoUpload(Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $uploaded = 0;
        $skipped = [];

        foreach ($request->file('photos') as $file) {
            $key = trim(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $cleanKey = strtolower(str_replace(['_', '-'], ' ', $key));

            $student = Student::where('nisn', $key)
                ->orWhere('nik', $key)
                ->orWhereRaw('LOWER(full_name) = ?', [$cleanKey])
                ->first();

            if (!$student) {
                $student = Student::whereRaw('LOWER(full_name) = ?', [str_replace(['_', '-'], ' ', strtolower($key))])->first();
            }

            if (!$student) {
                $skipped[] = $file->getClientOriginalName();
                continue;
            }

            $this->saveStudentPhoto($student, $file);
            $uploaded++;
        }

        $message = "Berhasil mengunggah foto untuk $uploaded siswa.";
        if (!empty($skipped)) {
            $message .= ' File tidak diproses: '.implode(', ', $skipped).'. Pastikan nama file sesuai NISN/NIK/Nama siswa.';
        }

        return Redirect::route('students.index')->with('success', $message);
    }

    public function importForm(Request $request)
    {
        $classroomId = $request->query('classroom_id');
        $classroom = null;

        if ($classroomId) {
            $classroom = Classroom::find($classroomId);
        }

        return view('students.import', compact('classroomId', 'classroom'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'data_file' => 'required|file|mimes:csv,txt,json,zip',
        ]);

        $rows = DapodikStudentImporter::parseFile($request->file('data_file'));
        if (empty($rows)) {
            return Redirect::back()->with('error', 'Tidak ada data siswa valid di file import. Periksa kembali header dan format file.')->withInput();
        }

        $classroomId = $request->input('classroom_id');
        $forcedClassroom = $classroomId ? Classroom::find($classroomId) : null;

        $imported = 0;

        foreach ($rows as $row) {
            if (empty($row['full_name'])) {
                continue;
            }

            if (!empty($row['nisn'])) {
                $key = ['nisn' => $row['nisn']];
            } elseif (!empty($row['nik'])) {
                $key = ['nik' => $row['nik']];
            } else {
                $key = ['full_name' => $row['full_name'], 'birth_date' => $row['birth_date'] ?? null];
            }

            $studentData = Arr::except($row, ['classroom_name', 'classroom_grade', 'classroom_major', 'classroom_academic_year']);
            $student = Student::updateOrCreate($key, $studentData);

            $classroomName = trim($row['classroom_name'] ?? '');
            if ($classroomName === '') {
                $classroomName = trim(implode(' ', array_filter([
                    $row['classroom_grade'] ?? null,
                    $row['classroom_major'] ?? null,
                    $row['classroom_academic_year'] ?? null,
                ])));
            }

            if ($classroomName !== '') {
                $classroom = Classroom::firstOrCreate(
                    ['name' => $classroomName],
                    [
                        'grade' => $row['classroom_grade'] ?? null,
                        'major' => $row['classroom_major'] ?? null,
                        'academic_year' => $row['classroom_academic_year'] ?? null,
                    ]
                );
                $student->classroom()->associate($classroom);
                $student->save();
            } elseif ($forcedClassroom) {
                $student->classroom()->associate($forcedClassroom);
                $student->save();
            }

            $imported++;
        }

        if ($imported === 0) {
            return Redirect::back()->with('error', 'Tidak ada siswa valid yang berhasil diimpor. Pastikan kolom full_name dan birth_date terisi dengan benar.')->withInput();
        }

        return Redirect::route('students.index')->with('success', "Berhasil mengimpor $imported siswa dari Dapodik.");
    }

    protected function validateStudent(Request $request): array
    {
        return $request->validate([
            'nisn' => 'nullable|string|max:20',
            'nis' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:25',
            'full_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'gender' => 'nullable|in:L,P',
            'birth_place' => 'nullable|string|max:120',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'blood_type' => 'nullable|string|max:5',
            'address' => 'nullable|string|max:1000',
            'dusun' => 'nullable|string|max:120',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'village' => 'nullable|string|max:120',
            'district' => 'nullable|string|max:120',
            'city' => 'nullable|string|max:120',
            'province' => 'nullable|string|max:120',
            'postal_code' => 'nullable|string|max:10',
            'residence_type' => 'nullable|string|max:255',
            'transportation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'parent_phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'family_card_number' => 'nullable|string|max:30',
            'child_order' => 'nullable|string|max:20',
            'father_name' => 'nullable|string|max:255',
            'father_nik' => 'nullable|string|max:25',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_nik' => 'nullable|string|max:25',
            'mother_occupation' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_nik' => 'nullable|string|max:25',
            'guardian_occupation' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:30',
            'assistance_type' => 'nullable|string|max:255',
            'assistance_number' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|string|max:4',
            'entry_date' => 'nullable|date',
            'status' => 'nullable|in:Aktif,Mutasi,Lulus',
            'major' => 'nullable|string|max:255',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'photo_path' => 'nullable|string|max:255',
        ]);
    }

    protected function saveStudentPhoto(Student $student, $photo): void
    {
        $path = $photo->store('student_photos', 'public');

        if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
            Storage::disk('public')->delete($student->photo_path);
        }

        $student->photo_path = $path;
        $student->save();
    }
}
