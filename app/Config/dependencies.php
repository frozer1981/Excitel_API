<?php

use DI\ContainerBuilder;
use App\Interfaces\ApiClientInterface;
use App\Interfaces\LoggerInterface;
use App\Services\ApiClientManager;
use App\Services\MockApiClient;
use App\Services\RealApiClient;
use App\Services\FileLogger;

$config = require __DIR__ . '/../config/config.php';

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    PDO::class => function () use ($config) {
        return new PDO(
            "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}",
            $config['db']['user'],
            $config['db']['password']
        );
    },

    ApiClientInterface::class => function () use ($config) {
        return new ApiClientManager(
            new RealApiClient($config['real_api_url']),
            new MockApiClient($config['mock_api_url']),
            $config['use_mock_api'] // Превключване според конфигурацията
        );
    },

    LoggerInterface::class => function () use ($config) {
        return new FileLogger($config['log']);
    },

    App\Repositories\CategoryRepository::class => DI\autowire(App\Repositories\CategoryRepository::class),
    App\Repositories\PlanRepository::class => DI\autowire(App\Repositories\PlanRepository::class),
    App\Repositories\TagRepository::class => DI\autowire(App\Repositories\TagRepository::class),
    App\Repositories\PlanTagRepository::class => DI\autowire(App\Repositories\PlanTagRepository::class),

    App\Services\PlanSync::class => function (DI\Container $container) {
        return new App\Services\PlanSync(
            $container->get(ApiClientInterface::class),
            $container->get(App\Repositories\CategoryRepository::class),
            $container->get(App\Repositories\PlanRepository::class),
            $container->get(App\Repositories\TagRepository::class),
            $container->get(App\Repositories\PlanTagRepository::class),
            $container->get(LoggerInterface::class) // Използване на FileLogger
        );
    }
]);

return $containerBuilder->build();
