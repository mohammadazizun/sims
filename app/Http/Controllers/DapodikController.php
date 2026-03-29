<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\DapodikSetting;
use App\Models\Student;
use App\Services\DapodikStudentImporter;
use App\Services\DapodikWebService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;

class DapodikController extends Controller
{
    public function settings()
    {
        $setting = DapodikSetting::first();

        return view('dapodik.settings', compact('setting'));
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'base_url' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'fetch_endpoint' => 'nullable|string|max:255',
            'push_endpoint' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
            'public_search_enabled' => 'sometimes|boolean',
        ]);

        $data['active'] = $request->boolean('active');
        $data['public_search_enabled'] = $request->boolean('public_search_enabled');

        DapodikSetting::updateOrCreate(
            ['id' => 1],
            $data
        );

        return Redirect::route('dapodik.settings')->with('success', 'Pengaturan Web Service Dapodik berhasil disimpan.');
    }

    public function syncForm()
    {
        $setting = DapodikSetting::first();

        return view('dapodik.sync', compact('setting'));
    }

    public function fetch(Request $request)
    {
        $setting = DapodikSetting::firstOrFail();
        $service = new DapodikWebService($setting);

        $rows = $service->fetchStudents();
        $students = DapodikStudentImporter::parseRows($rows);
        $imported = 0;

        foreach ($students as $row) {
            if (empty($row['full_name'])) {
                continue;
            }

            $key = !empty($row['nisn']) ? ['nisn' => $row['nisn']] : ['nik' => $row['nik']];
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
            }

            $imported++;
        }

        return Redirect::route('dapodik.sync.form')->with('success', "Berhasil menyinkronkan $imported data siswa dari Dapodik.");
    }

    public function push(Request $request)
    {
        $setting = DapodikSetting::firstOrFail();
        $service = new DapodikWebService($setting);

        $students = Student::with('classroom')->get()->map(function (Student $student) {
            return [
                'nisn' => $student->nisn,
                'nis' => $student->nis,
                'nik' => $student->nik,
                'nama' => $student->full_name,
                'jenis_kelamin' => $student->gender,
                'tempat_lahir' => $student->birth_place,
                'tanggal_lahir' => optional($student->birth_date)->format('Y-m-d'),
                'agama' => $student->religion,
                'golongan_darah' => $student->blood_type,
                'alamat' => $student->address,
                'rt' => $student->rt,
                'rw' => $student->rw,
                'kelurahan' => $student->village,
                'kecamatan' => $student->district,
                'kabupaten' => $student->city,
                'provinsi' => $student->province,
                'kode_pos' => $student->postal_code,
                'jenis_tinggal' => $student->residence_type,
                'alat_transportasi' => $student->transportation,
                'no_hp' => $student->phone,
                'email' => $student->email,
                'hp_ortu' => $student->parent_phone,
                'no_kk' => $student->family_card_number,
                'anak_ke' => $student->child_order,
                'nama_ayah' => $student->father_name,
                'nik_ayah' => $student->father_nik,
                'pekerjaan_ayah' => $student->father_occupation,
                'nama_ibu' => $student->mother_name,
                'nik_ibu' => $student->mother_nik,
                'pekerjaan_ibu' => $student->mother_occupation,
                'nama_wali' => $student->guardian_name,
                'nik_wali' => $student->guardian_nik,
                'pekerjaan_wali' => $student->guardian_occupation,
                'jenis_bantuan' => $student->assistance_type,
                'nomor_bantuan' => $student->assistance_number,
                'nama_sekolah_asal' => $student->previous_school,
                'tahun_lulus' => $student->graduation_year,
                'tanggal_masuk' => optional($student->entry_date)->format('Y-m-d'),
                'status' => $student->status,
                'kelas' => optional($student->classroom)->name,
                'tingkat' => optional($student->classroom)->grade,
                'jurusan' => optional($student->classroom)->major,
                'tahun_ajaran' => optional($student->classroom)->academic_year,
            ];
        })->toArray();

        $response = $service->pushStudents($students);

        return Redirect::route('dapodik.sync.form')->with('success', 'Data siswa berhasil diunggah ke Dapodik.')->with('dapodik_response', json_encode($response));
    }
}
