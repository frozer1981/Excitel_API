<?php

namespace App\Repositories;

use App\Models\Tag;
use PDO;

class TagRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function upsertTag(string $tagName): Tag {
        $stmt = $this->db->prepare("
            INSERT INTO tags (name)
            VALUES (:name)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)
        ");
        $stmt->execute([':name' => $tagName]);

        return new Tag([
            'id' => (int)$this->db->lastInsertId(),
            'name' => $tagName,
        ]);
    }
}
