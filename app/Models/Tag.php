<?php

namespace App\Models;

class Tag {
    private int $id;
    private string $name;

    public function __construct(array $data) {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'];
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
