<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use ZipArchive;

class DapodikClassroomImporter
{
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

    protected static function parseZipFile(string $path): array
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return [];
        }

        $temp = sys_get_temp_dir().'/dapodik_classroom_import_'.uniqid();
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

    protected static function parseJsonFile(string $path): array
    {
        $content = file_get_contents($path);
        $rows = json_decode($content, true);

        if (!is_array($rows)) {
            return [];
        }

        return self::normalizeRows($rows);
    }

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

        return self::normalizeRows($rows);
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

    protected static function normalizeRows(array $rows): array
    {
        $classrooms = [];

        foreach ($rows as $row) {
            $row = array_change_key_case($row, CASE_LOWER);

            if (!empty($row['name'])) {
                $name = trim((string) $row['name']);
                if ($name === '') {
                    continue;
                }

                $classrooms[$name] = [
                    'name' => $name,
                    'grade' => trim((string) ($row['grade'] ?? $row['tingkat'] ?? '')) ?: null,
                    'major' => trim((string) ($row['major'] ?? $row['jurusan'] ?? '')) ?: null,
                    'academic_year' => trim((string) ($row['academic_year'] ?? $row['tahun_ajaran'] ?? '')) ?: null,
                    'description' => trim((string) ($row['description'] ?? '')) ?: null,
                ];

                continue;
            }

            $name = trim((string) ($row['classroom_name'] ?? $row['kelas'] ?? ''));
            if ($name === '') {
                continue;
            }

            $classrooms[$name] = [
                'name' => $name,
                'grade' => trim((string) ($row['classroom_grade'] ?? $row['tingkat'] ?? $row['grade'] ?? '')) ?: null,
                'major' => trim((string) ($row['classroom_major'] ?? $row['jurusan'] ?? $row['major'] ?? '')) ?: null,
                'academic_year' => trim((string) ($row['classroom_academic_year'] ?? $row['tahun_ajaran'] ?? $row['academic_year'] ?? '')) ?: null,
                'description' => null,
            ];
        }

        return array_values($classrooms);
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
}
