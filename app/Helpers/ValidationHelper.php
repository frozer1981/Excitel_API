<?php

namespace App\Helpers;

class ValidationHelper {
    /**
     * Validate a plan's data.
     *
     * @param array $data
     * @return bool
     */
    public static function validatePlan(array $data): bool {
        $requiredKeys = ['_id', 'guid', 'name', 'status', 'price', 'type', 'category', 'tags'];

        // Check for missing keys
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                error_log("[ERROR] Missing required key: $key");
                return false;
            }
        }

        // Validate data types
        if (!is_string($data['_id']) || !is_string($data['guid']) || !is_string($data['name'])) {
            error_log("[ERROR] Invalid string fields in plan data: " . json_encode($data));
            return false;
        }

        // Validate status
        if (!in_array($data['status'], ['Active', 'Inactive'])) {
            error_log("[ERROR] Invalid status value: {$data['status']}");
            return false;
        }

        // Validate price (should be numeric after removing non-numeric characters)
        $price = preg_replace('/[^\d.]/', '', $data['price']);
        if (!is_numeric($price)) {
            error_log("[ERROR] Invalid price: {$data['price']}");
            return false;
        }

        // Validate type
        if (!in_array($data['type'], ['Lan', 'Fiber'])) {
            error_log("[ERROR] Invalid type value: {$data['type']}");
            return false;
        }

        // Validate category
        if (!is_string($data['category'])) {
            error_log("[ERROR] Invalid category: {$data['category']}");
            return false;
        }

        // Validate tags
        if (!is_array($data['tags'])) {
            error_log("[ERROR] Tags must be an array: " . json_encode($data['tags']));
            return false;
        }

        return true;
    }
}
