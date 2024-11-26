<?php

namespace App\Repositories;

use App\Models\PlanTag;
use PDO;

class PlanTagRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function linkTagToPlan(int $planId, int $tagId): PlanTag {
        $stmt = $this->db->prepare("
            INSERT INTO plan_tags (plan_id, tag_id)
            VALUES (:plan_id, :tag_id)
            ON DUPLICATE KEY UPDATE plan_id = plan_id
        ");
        $stmt->execute([
            ':plan_id' => $planId,
            ':tag_id' => $tagId,
        ]);

        return new PlanTag([
            'plan_id' => $planId,
            'tag_id' => $tagId,
        ]);
    }
}
