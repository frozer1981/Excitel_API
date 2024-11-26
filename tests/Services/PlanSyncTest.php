<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use App\Services\PlanSync;
use App\Interfaces\ApiClientInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\PlanRepository;
use App\Repositories\TagRepository;
use App\Repositories\PlanTagRepository;
use App\Interfaces\LoggerInterface;

class PlanSyncTest extends TestCase
{
    private $apiClientMock;
    private $categoryRepositoryMock;
    private $planRepositoryMock;
    private $tagRepositoryMock;
    private $planTagRepositoryMock;
    private $loggerMock;
    private $planSync;

    protected function setUp(): void
    {
        // Mock dependencies
        $this->apiClientMock = $this->createMock(ApiClientInterface::class);
        $this->categoryRepositoryMock = $this->createMock(CategoryRepository::class);
        $this->planRepositoryMock = $this->createMock(PlanRepository::class);
        $this->tagRepositoryMock = $this->createMock(TagRepository::class);
        $this->planTagRepositoryMock = $this->createMock(PlanTagRepository::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        // Instantiate PlanSync with mocked dependencies
        $this->planSync = new PlanSync(
            $this->apiClientMock,
            $this->categoryRepositoryMock,
            $this->planRepositoryMock,
            $this->tagRepositoryMock,
            $this->planTagRepositoryMock,
            $this->loggerMock
        );
    }

    public function testSyncWithValidData(): void {
        $mockData = [
            [
                '_id' => '123',
                'guid' => 'abc',
                'name' => 'Sample Plan',
                'category' => 'Monthly',
                'tags' => ['tag1', 'tag2'],
                'status' => 'Active',
                'price' => '$100.00',
                'type' => 'Fiber'
            ]
        ];

        // Mock the API client to return valid data
        $this->apiClientMock->method('fetchData')->willReturn($mockData);

        // Mock the category repository to return a valid Category model
        $this->categoryRepositoryMock->method('upsertCategory')
            ->willReturn(new \App\Models\Category(['id' => 1, 'name' => 'Monthly']));

        // Expect the plan repository's upsertPlan method to be called once
        $this->planRepositoryMock->expects($this->once())
            ->method('upsertPlan')
            ->with($mockData[0], 1);

        // Execute the sync method
        $this->planSync->sync();

        // Assert that no exceptions were thrown during synchronization
        $this->assertTrue(true, 'Synchronization should complete without errors.');
    }
}
