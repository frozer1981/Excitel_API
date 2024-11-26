<?php


namespace App\Services;

use App\Interfaces\ApiClientInterface;

class ApiClientManager implements ApiClientInterface
{
    private ApiClientInterface $realClient;
    private ApiClientInterface $mockClient;
    private bool $useMockApi;

    public function __construct(ApiClientInterface $realClient, ApiClientInterface $mockClient, bool $useMockApi)
    {
        $this->realClient = $realClient;
        $this->mockClient = $mockClient;
        $this->useMockApi = $useMockApi;
    }

    public function fetchData(): ?array
    {
        if ($this->useMockApi) {
            return $this->mockClient->fetchData();
        }

        $data = $this->realClient->fetchData();
        return $data ?? $this->mockClient->fetchData();
    }
}
