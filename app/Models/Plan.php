<?php

namespace App\Models;

class Plan {
    private int $id;
    private string $externalId;
    private string $guid;
    private string $name;
    private string $status;
    private float $price;
    private string $type;
    private int $categoryId;
    private float $priceWithoutMRP;
    private float $mrpAmount;

    private bool $isUnchanged = false; // Default to false

    public function __construct(array $data) {
        $missingFields = [];

        if (!isset($data['external_id'])) {
            $missingFields[] = 'external_id';
        }
        if (!isset($data['guid'])) {
            $missingFields[] = 'guid';
        }
        if (!isset($data['name'])) {
            $missingFields[] = 'name';
        }

        if (!empty($missingFields)) {
            throw new \InvalidArgumentException(
                "Missing required fields for Plan: " . implode(', ', $missingFields) .
                ". Provided data: " . json_encode($data)
            );
        }

        $this->id = $data['id'] ?? 0;
        $this->externalId = $data['external_id'];
        $this->guid = $data['guid'];
        $this->name = $data['name'];
        $this->status = $data['status'] ?? 'Inactive';
        $this->price = isset($data['price']) ? (float)preg_replace('/[^\d.]/', '', $data['price']) : 0.0;
        $this->type = $data['type'] ?? 'Unknown';
        $this->categoryId = $data['category_id'] ?? 0;
        $this->priceWithoutMRP = $data['price_without_mrp'] ?? 0.0;
        $this->mrpAmount = $data['mrp_amount'] ?? 0.0;
    }

    // Getter for ID
    public function getId(): int {
        return $this->id;
    }

    // Getter for Name
    public function getName(): string {
        return $this->name;
    }

    // Getter for Category ID
    public function getCategoryId(): int {
        return $this->categoryId;
    }

    // Convert Plan object to array
    public function toArray(): array {
        return [
            'id' => $this->id,
            'external_id' => $this->externalId,
            'guid' => $this->guid,
            'name' => $this->name,
            'status' => $this->status,
            'price' => $this->price,
            'type' => $this->type,
            'category_id' => $this->categoryId,
            'price_without_mrp' => $this->priceWithoutMRP,
            'mrp_amount' => $this->mrpAmount,
        ];
    }

    // Getter and Setter for isUnchanged
    public function setUnchanged(bool $isUnchanged): self {
        $this->isUnchanged = $isUnchanged;
        return $this;
    }

    public function isUnchanged(): bool {
        return $this->isUnchanged;
    }

    public function getPriceWithoutMRP(): float {
        return $this->priceWithoutMRP;
    }

    public function getMRPAmount(): float {
        return $this->mrpAmount;
    }
}
