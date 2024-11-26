<?php

namespace App\Interfaces;

interface PlanRepositoryInterface {
    public function upsertPlan(array $item, int $categoryId): int;
    public function updateTags(int $planId, array $tags): void;
    public function softDeleteMissing(array $externalIds): void;
    public function upsertCategory(string $categoryName): int;
}
