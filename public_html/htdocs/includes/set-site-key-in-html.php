<?php

# Load environmental variables from .env file
require_once __DIR__ . '/../../../config/loadEnv.php';
loadEnv(__DIR__ . '/../../../config/.env');

// Detect if running in local development environment
$is_local = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);

// Set separate keys depending on whether in local dev environment or prod environment...
if ($is_local) {
    # Secret key from Google reCAPTCHA for development environment
    $dataSitekeyInHtml = getenv("DEV_ENVIRONMENT_GOOGLE_RECAPTCHA_DATA_SITE_KEY_IN_HTML");
} else {
    # Secret key from Google reCAPTCHA for production environment
    $dataSitekeyInHtml = getenv("PROD_ENVIRONMENT_GOOGLE_RECAPTCHA_DATA_SITE_KEY_IN_HTML");
}
