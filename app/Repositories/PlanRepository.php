<?php

namespace App\Repositories;

use App\Models\Plan;
use PDO;

class PlanRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function upsertPlan(array $planData, int $categoryId): Plan {
        $stmt = $this->db->prepare("
        SELECT * FROM plans WHERE external_id = :external_id
    ");
        $stmt->execute([':external_id' => $planData['_id']]);
        $existingPlan = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingPlan) {
            // Check if any field has changed
            if (
                $existingPlan['guid'] === $planData['guid'] &&
                $existingPlan['name'] === $planData['name'] &&
                $existingPlan['status'] === $planData['status'] &&
                $existingPlan['price'] == preg_replace('/[^\d.]/', '', $planData['price']) &&
                $existingPlan['type'] === $planData['type'] &&
                $existingPlan['category_id'] == $categoryId
            ) {
                return (new Plan($existingPlan))->setUnchanged(true);
            }
        }

        // If no existing plan, insert or update the plan
        $stmt = $this->db->prepare("
        INSERT INTO plans (external_id, guid, name, status, price, type, category_id, updated_at)
        VALUES (:external_id, :guid, :name, :status, :price, :type, :category_id, NOW())
        ON DUPLICATE KEY UPDATE
            guid = VALUES(guid),
            name = VALUES(name),
            status = VALUES(status),
            price = VALUES(price),
            type = VALUES(type),
            category_id = VALUES(category_id),
            updated_at = NOW()
    ");
        $stmt->execute([
            ':external_id' => $planData['_id'],
            ':guid' => $planData['guid'],
            ':name' => $planData['name'],
            ':status' => $planData['status'],
            ':price' => preg_replace('/[^\d.]/', '', $planData['price']),
            ':type' => $planData['type'],
            ':category_id' => $categoryId
        ]);

        // Retrieve the inserted/updated plan
        $stmt = $this->db->prepare("
        SELECT * FROM plans WHERE external_id = :external_id
    ");
        $stmt->execute([':external_id' => $planData['_id']]);
        $newPlan = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$newPlan) {
            throw new \RuntimeException("Failed to retrieve the inserted/updated plan with external_id: {$planData['_id']}");
        }

        return new Plan($newPlan);
    }


    public function softDeleteMissing(array $externalIds): void {
        try {
            if (empty($externalIds)) {
                $stmt = $this->db->prepare("UPDATE plans SET deleted_at = NOW() WHERE deleted_at IS NULL");
                $stmt->execute();
                return;
            }

            $placeholders = rtrim(str_repeat('?,', count($externalIds)), ',');
            $stmt = $this->db->prepare("
            UPDATE plans
            SET deleted_at = NOW()
            WHERE external_id NOT IN ($placeholders) AND deleted_at IS NULL
        ");

            $stmt->execute($externalIds);
        } catch (\Exception $e) {
            error_log("[ERROR] Failed to soft delete missing plans: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAll(): array {
        $sql = "
        SELECT 
            p.*,
            c.name as category_name,
            ROUND(p.price / 1.18, 2) as price_without_mrp,
            ROUND(p.price - (p.price / 1.18), 2) as mrp_amount
        FROM plans p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.deleted_at IS NULL
        LIMIT 0, 10
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $plans = [];
        foreach ($results as $row) {
            $plans[] = new Plan($row);
        }
        return $plans;
    }

    public function countAll(?string $name = null, ?int $categoryId = null): int {
        $sql = "
        SELECT COUNT(*) as total
        FROM plans p
        WHERE p.deleted_at IS NULL
    ";

        $params = [];

        if ($name) {
            $sql .= " AND p.name LIKE :name";
            $params[':name'] = '%' . $name . '%';
        }

        if ($categoryId) {
            $sql .= " AND p.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn();
    }
}
