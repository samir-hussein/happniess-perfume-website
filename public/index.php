<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
	require $maintenance;
}

$maxJsonSize = 512; // in KB

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (
	stripos($contentType, 'application/json') === 0 &&
	($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH')
) {
	$contentLength = $_SERVER['CONTENT_LENGTH'] ?? 0;

	if ((int) $contentLength > $maxJsonSize) {
		header('Content-Type: application/json', true, 413);
		echo json_encode(['message' => 'Payload too large']);
		exit;
	}

	// fallback if client omits Content-Length
	$input = file_get_contents('php://input', false, null, 0, $maxJsonSize + 1);
	if (strlen($input) > $maxJsonSize) {
		header('Content-Type: application/json', true, 413);
		echo json_encode(['message' => 'Payload too large']);
		exit;
	}
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
