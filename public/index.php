<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// When Apache rewrites /shivir2026/* into this public/index.php, SCRIPT_NAME
// becomes /shivir2026/public/index.php while REQUEST_URI stays /shivir2026/*.
// Laravel then looks for a /shivir2026 route and returns 404. Pretend the
// front controller lives at the subdirectory root so pathInfo is correct.
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
if (str_ends_with($scriptName, '/public/index.php')) {
    $detectedBase = substr($scriptName, 0, -strlen('/public/index.php'));
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if ($detectedBase !== '' && str_starts_with($requestPath, $detectedBase)) {
        $_SERVER['SCRIPT_NAME'] = $detectedBase.'/index.php';
        $_SERVER['PHP_SELF'] = $detectedBase.'/index.php';
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
