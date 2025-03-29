<?php
# Load environmental variables from .env file
require_once __DIR__ . '/loadEnv.php';
loadEnv(__DIR__ . '/.env');

# Connect to database...
$budget_database = mysqli_connect
(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASS"), getenv("DB_NAME"))
    or die;
# Set encoding to match PHP script encoding...
mysqli_set_charset($budget_database, 'utf8');
?>