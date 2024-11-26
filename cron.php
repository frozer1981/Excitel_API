<?php

require __DIR__ . '/vendor/autoload.php';

use App\Services\PlanSync;
use App\Interfaces\LoggerInterface;

// Load the configuration and DI container
$config = require __DIR__ . '/app/Config/config.php';
$container = require __DIR__ . '/app/Config/dependencies.php';

// Retrieve PlanSync and Logger through the DI container
$planSync = $container->get(PlanSync::class);
$logger = $container->get(LoggerInterface::class); // Centralized logger

// Start the synchronization process
try {
    $planSync->sync();
    $logger->log("Synchronization completed successfully.", "INFO");
} catch (\Exception $e) {
    // Log the error using the centralized logger
    $logger->log("Synchronization failed: " . $e->getMessage(), "ERROR");
    echo "Synchronization failed: " . $e->getMessage() . "\n";
}
