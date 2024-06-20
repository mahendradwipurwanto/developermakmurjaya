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

function ej($params = null, $encode = true){
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
    $template = file_get_contents('template-json/logs.json');

    //use foreach according to the extracted data
    foreach ($data as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
    }

    return $template;
}

//function logs
function logs($data, $filename = 'logs'): bool
{
    // Create a new log file with the current date and time
    $filename = $filename . '_' . date("dmY_His") . '.json';

    // make folder path by parent folder year then month then date make it like logs/Y/m/d
    $folder = '../logs/' . date('Y/m/d/');

    // Check if the logs directory exists
    if (!file_exists($folder)) {
        // If not, create the logs directory
        mkdir($folder, 0777, true);
    }

    // Write the data to the log file
    file_put_contents($folder."/".$filename, $data);

    return true;
}