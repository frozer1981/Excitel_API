<?php
namespace App\Services;

use App\Interfaces\LoggerInterface;

class FileLogger implements LoggerInterface {
    private $logFilePath;

    public function __construct(string $logFilePath) {
        $this->logFilePath = $logFilePath;
    }

    public function log(string $message, string $level = 'INFO'): void {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] [$level] $message" . PHP_EOL;
        file_put_contents($this->logFilePath, $formattedMessage, FILE_APPEND);
    }
}
