<?php

namespace App\Services;

use App\Interfaces\ApiClientInterface;

class MockApiClient implements ApiClientInterface {
    private string $mockApiUrl;

    public function __construct(string $mockApiUrl) {
        $this->mockApiUrl = $mockApiUrl;
    }

    public function fetchData(): ?array {
        try {
            $mockData = file_get_contents($this->mockApiUrl);
            return json_decode($mockData, true);
        } catch (\Exception $e) {
            error_log("[ERROR] Failed to fetch data from Mock API: " . $e->getMessage());
            return null;
        }
    }
}

