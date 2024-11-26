<?php

namespace App\Repositories;

use App\Models\Category;
use PDO;

class CategoryRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function upsertCategory(string $categoryName): Category {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name)
            VALUES (:name)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)
        ");
        $stmt->execute([':name' => $categoryName]);

        return new Category([
            'id' => (int)$this->db->lastInsertId(),
            'name' => $categoryName,
        ]);
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM categories");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($results as $row) {
            $categories[] = new Category($row);
        }

        return $categories;
    }
}
