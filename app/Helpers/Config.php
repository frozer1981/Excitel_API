<?php
namespace App\Helpers;

class Config {
    private static array $config = [];

    public static function load(string $configPath): void {
        if (!file_exists($configPath)) {
            throw new \RuntimeException("Config file not found: $configPath");
        }
        self::$config = require $configPath;
    }

    public static function get(string $key, $default = null) {
        return self::$config[$key] ?? $default;
    }
}
