<?php

namespace App\Services;

use App\Interfaces\ApiClientInterface;

class ApiClient implements ApiClientInterface {
    private string $realApiUrl;
    private string $mockApiUrl;
    private bool $useMockApi;

    public function __construct(string $realApiUrl, string $mockApiUrl, bool $useMockApi) {
        $this->realApiUrl = $realApiUrl;
        $this->mockApiUrl = $mockApiUrl;
        $this->useMockApi = $useMockApi;
    }

    public function fetchData(): ?array {
        if ($this->useMockApi) {
            return $this->fetchMockData();
        }

        if (!$this->isUrlValid($this->realApiUrl)) {
            error_log("[ERROR] Invalid or unreachable Real API URL: {$this->realApiUrl}");
            return $this->fetchMockData();
        }

        try {
            $response = file_get_contents($this->realApiUrl);
            return json_decode($response, true);
        } catch (\Exception $e) {
            error_log("[ERROR] Failed to fetch data from Real API: " . $e->getMessage());
            return $this->fetchMockData();
        }
    }

    private function isUrlValid(string $url): bool {
        return filter_var($url, FILTER_VALIDATE_URL) && @file_get_contents($url) !== false;
    }

    private function fetchMockData(): ?array {
        try {
            $mockData = file_get_contents($this->mockApiUrl);
            return json_decode($mockData, true);
        } catch (\Exception $e) {
            error_log("[ERROR] Failed to fetch data from Mock API: " . $e->getMessage());
            return null;
        }
    }
}
