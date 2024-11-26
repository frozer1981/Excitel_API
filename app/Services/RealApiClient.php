<?php

namespace App\Services;

use App\Interfaces\ApiClientInterface;

class RealApiClient implements ApiClientInterface {
    private string $apiUrl;

    /**
     * Constructor for RealApiClient
     *
     * @param string $apiUrl - The URL of the real API endpoint
     */
    public function __construct(string $apiUrl) {
        $this->apiUrl = $apiUrl;
    }

    /**
     * Fetch data from the real API
     *
     * @return array|null - Returns the data as an associative array or null if an error occurs
     */
    public function fetchData(): ?array {
        try {
            $response = file_get_contents($this->apiUrl);

            // Check if the response is valid JSON
            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("[ERROR] Invalid JSON response from Real API: {$this->apiUrl}");
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            // Log the error if the request fails
            error_log("[ERROR] Failed to fetch data from Real API: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the API URL
     *
     * @return string
     */
    public function getUrl(): string {
        return $this->apiUrl;
    }
}
