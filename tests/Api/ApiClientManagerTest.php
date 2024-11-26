<?php

namespace Tests\Api;

use PHPUnit\Framework\TestCase;
use App\Services\ApiClientManager;
use App\Services\RealApiClient;
use App\Services\MockApiClient;

class ApiClientManagerTest extends TestCase
{
    public function testFallbackToMockApi(): void
    {
        $realApiUrl = 'http://invalid.real.api'; // Simulates an unreachable real API
        $mockApiUrl = 'http://127.0.0.1:89/mock_api.php'; // Local Mock API for testing

        // Create the API client manager
        $apiClient = new ApiClientManager(
            new RealApiClient($realApiUrl),
            new MockApiClient($mockApiUrl),
            false // Real API has priority by default
        );

        // Test the fallback mechanism
        $data = $apiClient->fetchData();

        // Ensure that the fallback to Mock API returns data
        $this->assertNotEmpty($data, 'Fallback to Mock API should return data.');
    }
}
