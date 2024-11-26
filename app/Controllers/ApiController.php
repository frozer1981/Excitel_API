<?php

namespace App\Controllers;

use App\Services\PlanSync;

class ApiController {
    private PlanSync $planSync;

    public function __construct(PlanSync $planSync) {
        $this->planSync = $planSync;
    }

    public function sync(): void {
        try {
            $this->planSync->sync();
        } catch (\Exception $e) {
            $errorMessage = [
                'error' => 'Synchronization failed',
                'details' => $e->getMessage()
            ];
            error_log("[ERROR] " . json_encode($errorMessage));
            http_response_code(500);
            echo json_encode($errorMessage);
        }
    }
}
