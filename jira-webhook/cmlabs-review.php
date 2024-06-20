<?php

date_default_timezone_set('Asia/Jakarta');
header('Content-Type: application/json');

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
        'issue.assignee.displayName' => $data['issue.assignee.displayName'] ?? '',
        'issue.summary' => $data['issue.summary'] ?? '',
        'issue.reporter.displayName' => $data['issue.reporter.displayName'] ?? '',
        'issue.status.name' => $data['issue.status.name'] ?? '',
        'issue.url' => $data['issue.url'] ?? '',
        'issue.QA.displayName' => $data['issue.QA.displayName'] ?? '',
        'issue.Lead / Reviewers.displayName' => $data['issue.Lead / Reviewers.displayName'] ?? '',
        'issue.duedate' => $data['issue.duedate'] ?? '',
        'issue.Story Points estimate' => $data['issue.Story Points estimate'] ?? '',
        'issue.issueType.name' => $data['issue.issueType.name'] ?? '',
        'issue.project.name' => $data['issue.project.name'] ?? ''
    );

    //use json template-json/assignee-notification.json and replace the values with the extracted data
    $template = file_get_contents('../data/template-json/review-notification.json');

    //use foreach according to the extracted data
    foreach ($extractedData as $key => $value) {

        // check if $key is issue.duedate then format the date
        $template = replaceAttrJira($extractedData, $template);

        // replace key issue.google.id with get google id, if there is 2 name split by comma, then get google id by name of each name get from issue.assignee.displayName
        if ($key == 'issue.Lead / Reviewers.displayName') {
            $template = str_replace('{{issue.google.id}}', mentionUsers($value), $template);
        }

        $template = str_replace('{{' . $key . '}}', $value, $template);
    }

    logs(replaceTemplate($extractedData), 'review');

    // URL to which the data will be sent via cURL
    $targetUrl = 'https://chat.googleapis.com/v1/spaces/AAAAXz0fDmY/messages?key=AIzaSyDdI0hCZtE6vySjMm-WEfRq3CPzqKqqsHI&token=4yiIfP11dtsBHbS_Sri8DPt68BnL_7TBNXvd8fNiKcU';

    $response = curlGoogleChat($targetUrl, $template);

    ej($response, false);
} else {
    // Handle the case where the request method is not POST
    echo json_encode(array('error' => 'Invalid request method. Only POST requests are allowed.'));
}


