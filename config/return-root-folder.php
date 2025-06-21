<?php
/* This file dynamically changes the base URL for all links on the site. */
/* This simplifies the process so that links don't need to be updated after being pushed to the server */

define('ROOT_PATH', dirname(__DIR__));
define('ROOT_FOLDER', basename(ROOT_PATH));
define('BASE_URL', '/' . ROOT_FOLDER);

$root_path = ROOT_PATH; # i.e. /Applications/Abyss Web Server/public_html/htdocs/budget_app_with_database
$root_folder = ROOT_FOLDER; # i.e. budget_app_with_database

// Detect if running locally
$is_local = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);

// Set base URL accordingly depending on if files are being served locally or remotely...
if ($is_local) {
    // Assuming local URL is like http://localhost/budget_app_with_database/
    $base_url = "/$root_folder/public_html/htdocs/"; // Set base URL
} else {
    // On production, app is likely in the root or a subdirectory
    $base_url = '/'; // adjust if hosted in a subfolder like '/myapp/'
}
