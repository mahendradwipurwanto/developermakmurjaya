<?php
function base_url($path = ''): string
{
    // Determine if the connection is secure
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Get the host name
    $host = $_SERVER['HTTP_HOST'];

    // if localhost add the project folder
    if ($host == 'localhost') {
        $host .= '/developermakmurjaya';
    }

    // Concatenate the protocol and host with the given path
    return $protocol . $host . '/' . ltrim($path, '/');
}

// get active path
function active($path): string
{
    // Get the current path without the query string and the base URL
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        $currentPath = str_replace('/developermakmurjaya', '', $currentPath);
    }

    // Check if the current path is equal to the given path
    if ($currentPath == $path) {
        // If true, return active
        return 'active';
    }

    // If false, return an empty string
    return '';
}

function ej($params = null, $encode = true)
{
    if ($encode) {
        echo json_encode($params);
    } else {
        echo $params;
    }
    exit;
}

//function replace template log
function replaceTemplate($data): string
{
    //use json template-json/assignee-notification.json and replace the values with the extracted data
    $template = file_get_contents('../data/template-json/logs.json');

    //use foreach according to the extracted data
    foreach ($data as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
    }

    return $template;
}

//function logs
function logs($data, $project, $filename = 'logs'): bool
{
    // Create a new log file with the current date and time
    $filename = $filename . '_' . date("His") . '.json';

    // make folder path by parent folder year then month then date make it like logs/Y/m/d
    $folder = "../logs/{$project}/" . date('Y/m/d/') . $filename . '/';

    // Check if the logs directory exists
    if (!file_exists($folder)) {
        // If not, create the logs directory
        mkdir($folder, 0777, true);
    }

    // Write the data to the log file
    file_put_contents("{$folder}/{$filename}", $data);

    return true;
}

function replaceAttrJira($extractedData, $template, $webhook_data = [])
{

    $template = str_replace('{{project.logo}}', $webhook_data['logo'], $template);

    //use foreach according to the extracted data
    foreach ($extractedData as $key => $value) {
        // check if $key is issue.duedate then format the date
        if ($key == 'issue.duedate') {
            if (empty($value)) {
                $template = str_replace('{{' . $key . '}}', "ASAP", $template);
            }

            $value = date('d F Y', strtotime($value));
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        // check if $key is issue.Story Points estimate then replace 0 as default state
        if ($key == 'issue.Story Points estimate' && empty($value)) {
            $template = str_replace('{{' . $key . '}}', 0, $template);
        }
        // check if $value is empty then replace - as default state
        if (empty($value)) {
            $value = "-";
        }

        $template = str_replace('{{' . $key . '}}', $value, $template);
    }

    return $template;
}

function mentionUsers($value)
{
    $users = explode(', ', $value);
    $google_id = '';
    foreach ($users as $name) {
        $google_id .= getGoogleId($name);
        if (count($users) > 1 and $name != end($users)) {
            $google_id .= ', ';
        }

        if (end($users) == $name) {
            $google_id .= ' - ';
        }
    }

    return rtrim($google_id, ', ');
}

//function to extract google_id from teams.json by jira_name
function getGoogleId($jira_name): string
{
    // Get the contents of the teams.json file
    $teams = file_get_contents('../data/teams.json');

    // Decode the JSON data to a PHP array
    $teams = json_decode($teams, true);

    // Loop through the teams array
    foreach ($teams as $team) {
        // Check if the jira_name matches the given jira_name
        if ($team['jira_name'] == $jira_name) {

            if (is_null($team['google_id'])) {
                return "@{$team['name']}";
            }

            return "<users/{$team['google_id']}>";
        }

        // If false, return an empty string
    }

    // If false, return an empty string
    return ' - ';
}

function curlGoogleChat($targetUrl, $template)
{
    // Initialize cURL session
    $ch = curl_init($targetUrl);

    // Configure cURL options
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $template);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Execute the cURL session
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        // Handle error - for example, log it or send it back in the response
        // log
        logs($error_msg, 'error');
    }

    // Close the cURL session
    curl_close($ch);

    return $response;
}

// create function to store key value to a json file with format key: value
function storeThreadIdByJiraKey($key, $value): bool
{
    $filename = "notifications_data.json";

    // make folder path by parent folder year then month then date make it like logs/Y/m/d
    $folder = "../data/" . date('Y/m/d/') .$filename;

    // Check if the logs directory exists
    if (!file_exists($folder)) {
        // If not, create the logs directory
        mkdir($folder, 0777, true);
    }

    // input key value to json file with new line after previous data but check if key already exist skip if not exist then input new line
    file_put_contents($folder, $key . ': ' . $value . PHP_EOL, FILE_APPEND);

    return true;
}

// create function to get value by key from json file
function getThreadIdByJiraKey($key): string
{
    $filename = "notifications_data.json";

    // make folder path by parent folder year then month then date make it like logs/Y/m/d
    $folder = "../data/" . date('Y/m/d/') .$filename;

    // Get the contents of the notifications_data.json file
    $data = file_get_contents($folder);

    // Explode the data by new line
    $data = explode(PHP_EOL, $data);

    // Loop through the data array
    foreach ($data as $line) {
        // Explode the line by colon
        $line = explode(': ', $line);

        // Check if the key matches the given key
        if ($line[0] == $key) {
            // If true, return the value
            return $line[1];
        }
    }

    // If false, return an empty string
    return '';
}