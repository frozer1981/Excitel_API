<?php

namespace App\Models;

class PlanTag {
    private int $planId;
    private int $tagId;

    public function __construct(array $data) {
        $this->planId = $data['plan_id'];
        $this->tagId = $data['tag_id'];
    }

    public function getPlanId(): int {
        return $this->planId;
    }

    public function getTagId(): int {
        return $this->tagId;
    }

    public function toArray(): array {
        return [
            'plan_id' => $this->planId,
            'tag_id' => $this->tagId,
        ];
    }
}
