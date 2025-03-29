<?php
require_once __DIR__ . '/loadEnv.php';
loadEnv(__DIR__ . '/.env');

# Secret key from Google reCAPTCHA
$secret = getenv("GOOGLE_RECAPTCHA_PASS"); // Replace with your secret key