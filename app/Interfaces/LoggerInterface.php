<?php
namespace App\Interfaces;

interface LoggerInterface {
    public function log(string $message, string $level = 'INFO'): void;
}
