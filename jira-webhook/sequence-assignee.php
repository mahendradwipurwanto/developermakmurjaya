<?php

date_default_timezone_set('Asia/Jakarta');
header('Content-Type: application/json');

require_once '../helpers/functions.php';
require_once 'templates/webhook.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the incoming data from the POST request
    $incomingData = file_get_contents('php://input');

    // Decode the incoming JSON data to a PHP array
    $data = json_decode($incomingData, true);

    // get a value from .env file
    $env = parse_ini_file('../.env');

    $webhook_data = [
        'type' => 'assignee',
        'project' => 'sequence',
        'url' => $env['SEQUENCE_WEBHOOK_URL'] ?? null,
        'logo' => $env['SEQUENCE_WEBHOOK_LOGO'] ?? null,
        'template' => file_get_contents('../data/template-json/assignee-notification.json')
    ];

    $result = webhook($data, $webhook_data);

    ej([
        'status' => 'success',
        'data' => $result
    ]);

} else {
    // Handle the case where the request method is not POST
    ej([
        'status' => 'failed',
        'error' => 'Invalid request method. Only POST requests are allowed.'
    ]);
}