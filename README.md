# Excitel Plans Management

## Description

This application updates the database with data retrieved from an external API or, if the API is unavailable, from local mock data. The mock API is useful for development, testing, and as a fallback mechanism. The mock data is located at `/mock/mock_data.json`.

---

## Features

- Retrieve and update data from an external API.
- Fallback to local mock data (`/mock/mock_data.json`) if the external API is unavailable.
- Centralized configuration for switching between real and mock APIs.
- Database integration with plans, categories, and tags.
- Vue.js frontend for viewing, filtering, and searching plans.
- Regular data updates using a cron job.

---

## Prerequisites

- **PHP**: Version 7.4 or higher.
- **Composer**: Dependency manager for PHP.
- **Node.js and npm**: Required for Vue.js build tools.
- **MySQL/MariaDB**: For database management.
- **php-di/php-di**: Dependency injection library for PHP, installed via Composer.

---

## Installation

### Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/excitel-plans.git
   cd excitel-plans

    Install PHP dependencies using Composer:

composer install

Install JavaScript dependencies:

cd public/frontend
npm install

Build the Vue.js frontend:

npm run build

Configure the application:

    Open /app/Config/config.php and set your database and API details:

    return [
        'debug' => true,
        'use_mock_api' => true, // true to use mock API, false for real API by default
        'mock_api_url' => 'http://127.0.0.1:89/mock_api.php',
        'real_api_url' => 'http://real.api.url/endpoint',
        'db' => [
            'host' => '127.0.0.1',
            'dbname' => 'excitel',
            'user' => 'root',
            'password' => '',
        ],
        'api' => [
            'url' => 'http://real.api.url/endpoint', // Fallback handled programmatically
        ],
        'log' => __DIR__ . '/../../logs/app.log',
    ];

Mock Data:

    Mock data is stored in /mock/mock_data.json.
    Example structure:

    [
        {
            "_id": "123",
            "guid": "abc",
            "name": "Sample Plan",
            "category": "Monthly",
            "tags": ["tag1", "tag2"],
            "status": "Active",
            "price": "$100.00",
            "type": "Fiber"
        }
    ]

Import the database:

    Run the provided SQL script to set up the database:

    mysql -u root -p excitel < database/excitel.sql

Set up the cron job:

    The cron.php script in the root directory synchronizes the database with the API or mock data.
    Add the following entry to your crontab:

    * * * * * /usr/bin/php /path/to/project/cron.php >> /path/to/project/logs/cron.log 2>&1

Start a local development server:

    php -S 127.0.0.1:8000 -t public

Fallback Logic

The application attempts to retrieve data from the external API first. If the API is unavailable, it automatically falls back to using the local mock data (/mock/mock_data.json). This ensures that the database is always updated, even if the external API is temporarily down.
Usage

    Visit the application in your browser:

http://127.0.0.1:8000

Use the filters to search and manage plans.

To manually update the database from the API or mock data, run:

    php cron.php

    The fallback logic is handled automatically. You can control the behavior by setting the use_mock_api flag in /app/Config/config.php.

Testing

    Run PHPUnit tests for backend logic:

    vendor/bin/phpunit

    Ensure you have a valid phpunit.xml configuration.

Folder Structure

    app/: Application logic, including services and repositories.
    mock/: Contains mock_data.json for development and testing.
    public/: Publicly accessible files and Vue.js frontend.
    tests/: Unit tests for the application.
    logs/: Log files for the application and cron jobs.
    cron.php: Script for updating data from the API or mock data.
    vendor/: Composer dependencies.

Troubleshooting
Common Issues

    Database Connection Error:
        Ensure the db section in /app/Config/config.php has the correct database credentials.
        Verify that MySQL is running and the database exists.

    Frontend Issues:
        Ensure npm install and npm run build are executed without errors.
        Check for JavaScript errors in the browser console.

    Mock Data Not Loading:
        Verify that the file /mock/mock_data.json exists and contains valid JSON.
        Ensure the fallback logic is working by setting use_mock_api to true.

    Cron Job Not Running:
        Check the crontab entry by running:

    crontab -l

    Verify that the script cron.php is executable and logs are written to /logs/cron.log.

External API Issues:

    If the external API is down, check the logs at /logs/app.log for fallback to mock data.