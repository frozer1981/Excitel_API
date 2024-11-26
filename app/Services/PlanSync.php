<?php
namespace App\Services;

use App\Helpers\ValidationHelper;
use App\Interfaces\ApiClientInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\PlanRepository;
use App\Repositories\TagRepository;
use App\Repositories\PlanTagRepository;
use App\Interfaces\LoggerInterface;
use App\Helpers\Config;

class PlanSync {
    private ApiClientInterface $apiClient;
    private CategoryRepository $categoryRepository;
    private PlanRepository $planRepository;
    private TagRepository $tagRepository;
    private PlanTagRepository $planTagRepository;
    private LoggerInterface $logger;

    public function __construct(
        ApiClientInterface $apiClient,
        CategoryRepository $categoryRepository,
        PlanRepository $planRepository,
        TagRepository $tagRepository,
        PlanTagRepository $planTagRepository,
        LoggerInterface $logger
    ) {
        $this->apiClient = $apiClient;
        $this->categoryRepository = $categoryRepository;
        $this->planRepository = $planRepository;
        $this->tagRepository = $tagRepository;
        $this->planTagRepository = $planTagRepository;
        $this->logger = $logger;
    }

    public function sync(): void {
        $processedCount = 0;
        $unchangedCount = 0;
        $invalidCount = 0;

        try {
            // Fetch data from API
            $data = $this->apiClient->fetchData();

            if (!$data) {
                $this->logger->log("API is unavailable or returned no data.", "ERROR");
                return;
            }

            $externalIds = []; // List of `_id` for soft delete

            foreach ($data as $item) {
                if(Config::get('debug',true))
                    $this->logger->log("Processing plan: " . json_encode($item), "DEBUG");

                // Validate plan data
                if (!ValidationHelper::validatePlan($item)) {
                    $this->logger->log("Skipping invalid plan data: " . json_encode($item), "ERROR");
                    $invalidCount++;
                    continue; // Skip invalid entries
                }

                // Add `_id` to external IDs for soft delete
                $externalIds[] = $item['_id'];

                // Process category
                $category = $this->categoryRepository->upsertCategory($item['category']);
                $plan = $this->planRepository->upsertPlan($item, $category->getId());

                if ($plan->isUnchanged()) {
                    // Plan is valid but did not require updates
                    $unchangedCount++;
                    continue;
                }

                // Process tags
                foreach ($item['tags'] as $tagName) {
                    $tag = $this->tagRepository->upsertTag($tagName);
                    $this->planTagRepository->linkTagToPlan($plan->getId(), $tag->getId());
                }

                $processedCount++;
            }

            // Perform soft delete for missing records
            $this->planRepository->softDeleteMissing($externalIds);

            // Log result
            $summary = [
                'processed' => $processedCount,
                'unchanged' => $unchangedCount,
                'invalid' => $invalidCount,
                'message' => 'Synchronization completed'
            ];
            $this->logger->log(json_encode($summary), "INFO");
        } catch (\Exception $e) {
            $this->logger->log("Synchronization failed: " . $e->getMessage(), "ERROR");
        }
    }
}
