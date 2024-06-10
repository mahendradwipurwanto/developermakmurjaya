<?php
function base_url($path = '')
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
function active($path)
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

?>