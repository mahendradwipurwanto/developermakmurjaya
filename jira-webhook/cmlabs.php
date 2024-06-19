<?php

// Include the functions.php file
require_once '../helpers/functions.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the incoming data from the POST request
    $incomingData = file_get_contents('php://input');

    // Decode the incoming JSON data to a PHP array
    $data = json_decode($incomingData, true);

    // Extract the required fields from the data
    $extractedData = array(
        'issue.key' => $data['issue.key'] ?? '',
        'issue.google.id' => $data['issue.google.id'] ?? '',
        'issue.assignee.displayName' => $data['issue.assignee.displayName'] ?? '',
        'summary' => $data['issue.summary'] ?? '',
        'reporter.display.displayName' => $data['issue.reporter.displayName'] ?? '',
        'issue.status.name' => $data['issue.status.name'] ?? '',
        'issue.url' => $data['issue.url'] ?? ''
    );

    //use json template-json/assignee-notification.json and replace the values with the extracted data
    $template = file_get_contents('template-json/assignee-notification.json');

    //use foreach according to the extracted data
    foreach ($extractedData as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
    }

    header('Content-Type: application/json');
    ej($template, false);

    // URL to which the data will be sent via cURL
//    $targetUrl = 'https://example.com/target-endpoint';
//
//    // Initialize cURL session
//    $ch = curl_init($targetUrl);
//
//    // Configure cURL options
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_POST, true);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
//
//    // Execute cURL request and get the response
//    $response = curl_exec($ch);
//
//    // Check for cURL errors
//    if (curl_errno($ch)) {
//        $error_msg = curl_error($ch);
//        // Handle error - for example, log it or send it back in the response
//        echo json_encode(array('error' => $error_msg));
//    } else {
//        // Send the response from the target URL back to the client
//        echo $response;
//    }
//
//    // Close cURL session
//    curl_close($ch);
} else {
    // Handle the case where the request method is not POST
    echo json_encode(array('error' => 'Invalid request method. Only POST requests are allowed.'));
}
?>