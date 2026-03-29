<?php

namespace App\Services;

use App\Models\DapodikSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use SimpleXMLElement;

class DapodikWebService
{
    protected DapodikSetting $setting;

    protected array $fetchPaths = [
        'webservice/peserta_didik',
        'webservice/peserta_didik.php',
        'ws/peserta_didik',
        'ws/peserta_didik.php',
        'webservice/students',
        'ws/students',
        'students',
        'students/all',
    ];

    protected array $pushPaths = [
        'webservice/peserta_didik/upload',
        'webservice/students/upload',
        'ws/peserta_didik/upload',
        'ws/students/upload',
        'students/upload',
        'students',
    ];

    public function __construct(DapodikSetting $setting)
    {
        $this->setting = $setting;
    }

    public function fetchStudents(): array
    {
        $paths = $this->setting->fetch_endpoint ? $this->expandEndpointCandidates([$this->setting->fetch_endpoint]) : $this->fetchPaths;
        $errors = [];

        foreach ($paths as $path) {
            foreach (['get', 'post'] as $method) {
                try {
                    $response = $this->sendRequest($method, $path);
                    if (is_array($response) && !empty($response)) {
                        return $response;
                    }
                } catch (\Exception $e) {
                    $errors[] = "{$method} {$path}: {$e->getMessage()}";
                }
            }
        }

        throw new \RuntimeException(
            'Tidak dapat mengambil data siswa dari Dapodik. Pastikan pengaturan Web Service benar dan endpoint tersedia. Coba periksa Base URL dan Fetch Endpoint.' .
            (count($errors) ? ' Detail: '.implode(' | ', array_unique($errors)) : '')
        );
    }

    public function pushStudents(array $students): array
    {
        $paths = $this->setting->push_endpoint ? $this->expandEndpointCandidates([$this->setting->push_endpoint]) : $this->pushPaths;
        $errors = [];

        foreach ($paths as $path) {
            try {
                $response = $this->sendRequest('post', $path, ['students' => $students]);

                if (is_array($response) && !empty($response)) {
                    return $response;
                }
            } catch (\Exception $e) {
                $errors[] = "post {$path}: {$e->getMessage()}";
            }
        }

        throw new \RuntimeException(
            'Tidak dapat mengunggah data siswa ke Dapodik. Periksa kembali pengaturan dan endpoint Web Service.' .
            (count($errors) ? ' Detail: '.implode(' | ', array_unique($errors)) : '')
        );
    }

    protected function expandEndpointCandidates(array $paths): array
    {
        $candidates = [];

        foreach ($paths as $path) {
            $path = trim($path);
            if ($path === '') {
                continue;
            }

            $candidates[] = $path;

            if (Str::startsWith($path, '#')) {
                $resource = trim(ltrim($path, '#/'));
                if ($resource !== '') {
                    $candidates[] = "ws/{$resource}";
                    $candidates[] = "webservice/{$resource}";
                    $candidates[] = Str::snake($resource);
                    $candidates[] = "ws/".Str::snake($resource);
                    $candidates[] = "webservice/".Str::snake($resource);
                }
            }
        }

        return array_values(array_unique($candidates));
    }

    protected function sendRequest(string $method, string $path, array $payload = []): ?array
    {
        $url = $this->buildUrl($path);
        $options = ['verify' => false, 'timeout' => 20];
        $query = ['key' => $this->setting->api_key];
        $headers = [
            'Accept' => 'application/json',
            'X-API-KEY' => $this->setting->api_key,
        ];

        $request = Http::withHeaders($headers)->timeout(20)->withOptions(['verify' => false]);

        if ($method === 'get') {
            $response = $request->get($url, $query);
        } else {
            $response = $request->post($url, array_merge($payload, ['key' => $this->setting->api_key]));
        }

        if (!$response->successful()) {
            throw new \RuntimeException("Endpoint {$url} merespons status {$response->status()}");
        }

        return $this->decodeResponse($response->body());
    }

    protected function buildUrl(string $path): string
    {
        $path = trim($path);

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return rtrim($path, '/');
        }

        if (Str::startsWith($path, '#')) {
            $path = ltrim($path, '#/');
        }

        $base = trim($this->setting->base_url);

        if (Str::startsWith($base, ['http://', 'https://'])) {
            $url = rtrim($base, '/');
        } else {
            $url = 'http://'.rtrim($base, '/');
        }

        if (parse_url($url, PHP_URL_PORT) === null) {
            $url .= ':5774';
        }

        return rtrim($url, '/').'/'.ltrim($path, '/');
    }

    protected function decodeResponse(string $body): ?array
    {
        $json = json_decode($body, true);

        if (is_array($json)) {
            return $json;
        }

        if (Str::contains($body, '<?xml') || Str::contains($body, '<')) {
            $xml = @simplexml_load_string($body, SimpleXMLElement::class, LIBXML_NOCDATA);
            if ($xml !== false) {
                return json_decode(json_encode($xml), true) ?: null;
            }
        }

        return null;
    }
}
