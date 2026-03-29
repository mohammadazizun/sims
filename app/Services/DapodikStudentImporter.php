<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use ZipArchive;

class DapodikStudentImporter
{
    /**
     * Parse a Dapodik export file and normalize into student rows.
     *
     * @param  UploadedFile  $file
     * @return array<int, array<string, mixed>>
     */
    public static function parseFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'zip') {
            return self::parseZipFile($file->getRealPath());
        }

        if ($extension === 'json') {
            return self::parseJsonFile($file->getRealPath());
        }

        return self::parseCsvFile($file->getRealPath());
    }

    /**
     * @param  string  $path
     * @return array<int, array<string, mixed>>
     */
    protected static function parseZipFile(string $path): array
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return [];
        }

        $temp = sys_get_temp_dir().'/dapodik_import_'.uniqid();
        @mkdir($temp, 0755, true);
        $rows = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if ($name === false) {
                continue;
            }

            $lower = strtolower($name);
            if (!str_ends_with($lower, '.csv') && !str_ends_with($lower, '.json') && !str_ends_with($lower, '.txt')) {
                continue;
            }

            $pathName = $temp.'/'.basename($name);
            file_put_contents($pathName, $zip->getFromIndex($i));

            if (str_ends_with($lower, '.json')) {
                $rows = array_merge($rows, self::parseJsonFile($pathName));
            } else {
                $rows = array_merge($rows, self::parseCsvFile($pathName));
            }
        }

        $zip->close();
        self::cleanupTempDirectory($temp);

        return $rows;
    }

    /**
     * @param  string  $path
     * @return array<int, array<string, mixed>>
     */
    protected static function parseJsonFile(string $path): array
    {
        $content = file_get_contents($path);
        $rows = json_decode($content, true);

        if (!is_array($rows)) {
            return [];
        }

        return self::parseRows($rows);
    }

    /**
     * Normalize rows already loaded from a web service response.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    public static function parseRows(array $rows): array
    {
        return array_values(array_map([self::class, 'normalizeRow'], $rows));
    }

    /**
     * @param  string  $path
     * @return array<int, array<string, mixed>>
     */
    protected static function parseCsvFile(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);
        if ($content === false) {
            return [];
        }

        $lines = preg_split('/\R/', $content);
        $delimiter = ',';
        $header = null;
        $rows = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                continue;
            }

            if (preg_match('/^sep=(.+)$/i', $trimmed, $matches)) {
                $delimiter = $matches[1];
                continue;
            }

            if ($header === null) {
                $header = self::normalizeHeader(str_getcsv($line, $delimiter));

                if (count($header) === 1 && strpos($line, ';') !== false) {
                    $delimiter = ';';
                    $header = self::normalizeHeader(str_getcsv($line, $delimiter));
                }

                if (count($header) === 1 && strpos($line, "\t") !== false) {
                    $delimiter = "\t";
                    $header = self::normalizeHeader(str_getcsv($line, $delimiter));
                }

                if (count($header) === 1 && strpos($line, ',') === false && strpos($line, ';') === false) {
                    $header = null;
                }

                continue;
            }

            $values = str_getcsv($line, $delimiter);
            if (count($values) === 1 && strpos($line, ';') !== false) {
                $delimiter = ';';
                $values = str_getcsv($line, $delimiter);
            }

            $values = array_map(fn ($value) => trim((string) $value), $values);
            if (count(array_filter($values, fn ($value) => $value !== '')) === 0) {
                continue;
            }

            if (count($values) < count($header)) {
                $values = array_pad($values, count($header), '');
            } elseif (count($values) > count($header)) {
                $values = array_slice($values, 0, count($header));
            }

            $rows[] = array_combine($header, $values) ?: [];
        }

        if (empty($rows)) {
            return [];
        }

        return array_values(array_map([self::class, 'normalizeRow'], $rows));
    }

    protected static function normalizeHeader(array $header): array
    {
        return array_values(array_map(function ($value) {
            $value = trim((string) $value);
            $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
            $value = str_replace([' ', '.', '-'], '_', $value);
            return strtolower($value);
        }, $header));
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected static function normalizeRow(array $row): array
    {
        $get = fn (array $keys) => trim((string) array_reduce($keys, fn ($carry, $key) => $carry ?? ($row[$key] ?? null), null));
        $gender = strtoupper($get(['jenis_kelamin', 'jk', 'gender']));
        if (!in_array($gender, ['L', 'P'], true)) {
            $gender = null;
        }

        $birthDateRaw = $get(['tanggal_lahir', 'birth_date', 'tgllahir']);
        $birthDate = self::normalizeDate($birthDateRaw);

        if ($birthDate === null) {
            $birthDate = self::buildDateFromParts(
                $get(['birth_year', 'tahun_lahir']),
                $get(['birth_month', 'bulan_lahir']),
                $get(['birth_day', 'tanggal_lahir', 'tanggal_lahir_hari', 'hari_lahir'])
            );
        }

        $entryDate = self::normalizeDate($get(['tanggal_masuk', 'entry_date']));

        return [
            'nisn' => $get(['nisn', 'nis', 'no_nisn', 'no_nisn_siswa']),
            'nis' => $get(['nis', 'no_nis']),
            'nik' => $get(['nik', 'no_ktp', 'no_nik']),
            'full_name' => $get(['nama', 'nama_lengkap', 'full_name']),
            'gender' => $gender,
            'birth_place' => $get(['tempat_lahir', 'birth_place']),
            'birth_date' => $birthDate,
            'religion' => $get(['agama', 'religion']),
            'blood_type' => $get(['golongan_darah', 'blood_type']),
            'address' => $get(['alamat', 'alamat_jalan', 'address']),
            'dusun' => $get(['dusun', 'kampung', 'village_area']),
            'rt' => $get(['rt']),
            'rw' => $get(['rw']),
            'village' => $get(['kelurahan', 'desa', 'village']),
            'district' => $get(['kecamatan', 'district']),
            'city' => $get(['kabupaten', 'kota', 'city']),
            'province' => $get(['provinsi', 'province']),
            'postal_code' => $get(['kode_pos', 'postal_code']),
            'residence_type' => $get(['jenis_tinggal', 'residence_type', 'jenis_tempat_tinggal']),
            'transportation' => $get(['alat_transportasi', 'transportation', 'transportasi']),
            'phone' => $get(['hp_siswa', 'hpsiswa', 'no_hp', 'telp', 'phone']),
            'parent_phone' => $get(['hp_ortu', 'hportu', 'hp_orang_tua', 'no_hp_ortu', 'parent_phone']),
            'email' => $get(['email']),
            'family_card_number' => $get(['no_kk', 'family_card_number']),
            'child_order' => $get(['anak_ke', 'anakkeberapa', 'child_order']),
            'father_name' => $get(['nama_ayah', 'father_name']),
            'father_nik' => $get(['nik_ayah', 'father_nik', 'nikayah']),
            'father_occupation' => $get(['pekerjaan_ayah', 'father_occupation']),
            'mother_name' => $get(['nama_ibu', 'mother_name']),
            'mother_nik' => $get(['nik_ibu', 'mother_nik', 'nikibu']),
            'mother_occupation' => $get(['pekerjaan_ibu', 'mother_occupation']),
            'guardian_name' => $get(['nama_wali', 'guardian_name']),
            'guardian_nik' => $get(['nik_wali', 'guardian_nik', 'nikwali']),
            'guardian_occupation' => $get(['pekerjaan_wali', 'guardian_occupation']),
            'previous_school' => $get(['nama_sekolah_asal', 'asalsekolah', 'previous_school']),
            'graduation_year' => $get(['tahun_lulus', 'graduation_year']),
            'entry_date' => $entryDate,
            'status' => $get(['status', 'status_siswa', 'student_status']),
            'classroom_name' => $get(['kelas', 'rombel', 'nama_kelas', 'nama_rombel', 'classroom', 'class_name']),
            'classroom_grade' => $get(['tingkat', 'grade', 'kelas_tingkat']),
            'classroom_major' => $get(['jurusan', 'major', 'kelas_jurusan']),
            'classroom_academic_year' => $get(['tahun_ajaran', 'academic_year', 'thn_ajaran']),
            'assistance_type' => $get(['jenis_bantuan', 'bantuan', 'jenis_penerima_bantuan', 'assistance_type']),
            'assistance_number' => $get(['nomor_bantuan', 'no_bantuan', 'nomor_kartu', 'assistance_number']),
            'photo_path' => null,
        ];
    }

    protected static function cleanupTempDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $files = scandir($path);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            @unlink($path.'/'.$file);
        }

        @rmdir($path);
    }

    protected static function normalizeDate(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $formats = [
            'Y-m-d',
            'Y/m/d',
            'Y.m.d',
            'd/m/Y',
            'd-m-Y',
            'd.m.Y',
            'j/n/Y',
            'j-n-Y',
            'n/j/Y',
            'n-j-Y',
        ];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        return null;
    }

    protected static function buildDateFromParts(string $year, string $month, string $day): ?string
    {
        $year = trim($year);
        $month = trim($month);
        $day = trim($day);

        if ($year === '' || $month === '' || $day === '') {
            return null;
        }

        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $day = str_pad($day, 2, '0', STR_PAD_LEFT);

        if (checkdate((int) $month, (int) $day, (int) $year)) {
            return "$year-$month-$day";
        }

        return null;
    }
}
