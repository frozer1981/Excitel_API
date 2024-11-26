<?php

namespace Tests\Helpers;

use PHPUnit\Framework\TestCase;
use App\Helpers\ValidationHelper;

class ValidationHelperTest extends TestCase
{
    public function testValidatePlanWithValidData(): void
    {
        $validPlan = [
            '_id' => '123456',
            'guid' => 'abc123',
            'name' => 'Valid Plan',
            'status' => 'Active',
            'price' => '$100.00',
            'type' => 'Fiber',
            'category' => 'Monthly',
            'tags' => ['tag1', 'tag2']
        ];

        // Validate the plan with all required fields
        $result = ValidationHelper::validatePlan($validPlan);

        // Assert that validation passes for valid data
        $this->assertTrue($result, 'Validation should pass for valid data.');
    }

    public function testValidatePlanWithMissingFields(): void
    {
        $invalidPlan = [
            'guid' => 'abc123',
            'name' => 'Invalid Plan',
            'status' => 'Active',
            'price' => '$100.00',
            'type' => 'Fiber',
            'category' => 'Monthly',
            'tags' => ['tag1', 'tag2']
        ];

        // Validate the plan with a missing '_id' field
        $result = ValidationHelper::validatePlan($invalidPlan);

        // Assert that validation fails if '_id' is missing
        $this->assertFalse($result, 'Validation should fail if _id is missing.');
    }

    public function testValidatePlanWithInvalidStatus(): void
    {
        $invalidPlan = [
            '_id' => '123456',
            'guid' => 'abc123',
            'name' => 'Invalid Plan',
            'status' => 'Unknown', // Invalid status
            'price' => '$100.00',
            'type' => 'Fiber',
            'category' => 'Monthly',
            'tags' => ['tag1', 'tag2']
        ];

        // Validate the plan with an invalid 'status'
        $result = ValidationHelper::validatePlan($invalidPlan);

        // Assert that validation fails for invalid status
        $this->assertFalse($result, 'Validation should fail for invalid status.');
    }

    public function testValidatePlanWithInvalidPrice(): void
    {
        $invalidPlan = [
            '_id' => '123456',
            'guid' => 'abc123',
            'name' => 'Invalid Plan',
            'status' => 'Active',
            'price' => 'INVALID_PRICE', // Invalid price
            'type' => 'Fiber',
            'category' => 'Monthly',
            'tags' => ['tag1', 'tag2']
        ];

        // Validate the plan with an invalid 'price'
        $result = ValidationHelper::validatePlan($invalidPlan);

        // Assert that validation fails for invalid price
        $this->assertFalse($result, 'Validation should fail for invalid price.');
    }
}
