<?php

require_once __DIR__ . '/../vendor/autoload.php';
use App\Repositories\PlanRepository;
use App\Repositories\CategoryRepository;

$container = require __DIR__ . '/../app/Config/dependencies.php';
$planRepository = $container->get(App\Repositories\PlanRepository::class);
$categoryRepository = $container->get(App\Repositories\CategoryRepository::class);

// Validate and sanitize input
$name = isset($_GET['name']) ? filter_var($_GET['name'], FILTER_SANITIZE_STRING) : null;
$categoryId = isset($_GET['category_id']) ? filter_var($_GET['category_id'], FILTER_VALIDATE_INT) : null;
$page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch plans and categories
$plans = $planRepository->getAll($name, $categoryId, $offset, $limit);
$categories = $categoryRepository->getAll();

$totalPlans = $planRepository->countAll($name, $categoryId);
$totalPages = ceil($totalPlans / $limit);

$plansJson = json_encode(array_map(fn($plan) => $plan->toArray(), $plans));
$categoriesJson = json_encode(array_map(fn($category) => $category->toArray(), $categories));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excitel Plans</title>
    <link rel="stylesheet" href="/js/assets/index.css"> <!-- Vue.js CSS -->
    <script>
        // Pass sanitized PHP data to Vue.js
        window.PHP_PLANS = <?= $plansJson ?>;
        window.PHP_CATEGORIES = <?= $categoriesJson ?>;
        window.TOTAL_PAGES = <?= $totalPages ?>;
        window.CURRENT_PAGE = <?= $page ?>;
    </script>
</head>
<body>
<div id="app"></div> <!-- Vue.js will mount here -->
<script type="module" src="/js/assets/index.js"></script> <!-- Vue.js JavaScript -->
</body>
</html>
