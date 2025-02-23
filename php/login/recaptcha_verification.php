<?php
require_once("../../../../connect_recaptcha_secret_key.php");

// Get the response from reCAPTCHA
$recaptchaResponse = $_POST['gRecaptchaResponse'];

// User's IP address (optional but recommended for security purposes)
$userIP = $_SERVER['REMOTE_ADDR'];

// Verify the reCAPTCHA response by making a request to Google's API
$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

// Prepare the data to send
$data = [
    'secret' => $secret,
    'response' => $recaptchaResponse,
    'remoteip' => $userIP
];

// Use cURL to send the request to Google's reCAPTCHA API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verifyUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Get the response from Google
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    die('Error: "' . curl_error($ch) . '"');
}

curl_close($ch);

// Decode the JSON response from Google
$responseKeys = json_decode($response, true);

// Check if the reCAPTCHA verification was unsuccessful...
if (intval($responseKeys['success']) !== 1) {
    header("Location: ../error_pages/form_verification_error.php");
    exit();
}