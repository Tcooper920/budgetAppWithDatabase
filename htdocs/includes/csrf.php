<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Ensure the session is started before accessing $_SESSION
}

// Generate a CSRF (Cross-Site Request Forgery) token if one does not exist...
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a secure random token
}