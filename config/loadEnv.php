<?php
/* 
This file reads the .env file and sets the variables in PHP.
This simple function loads the file and populates the $_ENV or $_SERVER superglobals. 

The .env file is formatted like this:
DB_HOST=
DB_USER=
DB_PASS=
DB_NAME=
GMAIL_SMTP_AUTHENTICATION_PASSWORD=
GOOGLE_RECAPTCHA_PASS=
*/
function loadEnv($file)
{
    if (!file_exists($file)) {
        throw new Exception("Environment file does not exist: " . $file);
    }

    // Read the file
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Process each line
    foreach ($lines as $line) {
        // Ignore comments (lines starting with # or ;)
        if (strpos(trim($line), '#') === 0 || strpos(trim($line), ';') === 0) {
            continue;
        }

        // Split the line by the first '='
        $parts = explode('=', $line, 2);

        if (count($parts) == 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);

            // Set the value as an environment variable
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value; // Optional, if you want it to be available in the $_SERVER superglobal
        }
    }
}
