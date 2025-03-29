<?php
require_once __DIR__ . '/loadEnv.php';
loadEnv(__DIR__ . '/.env');

# Password to use for SMTP authentication (for gmail)...
$smtp_authentication_password = getenv("GMAIL_SMTP_AUTHENTICATION_PASSWORD");
