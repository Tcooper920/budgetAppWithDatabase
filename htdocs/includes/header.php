<?php
// Define the base URL relative to the site's root to create dynamic links...
$base_url = "/budget_app_with_database/htdocs/"; // Replace 'your_project_folder' with your actual project name if needed
// Set the X-Frame-Options header to prevent embedding in an iframe (for security purposes)
header("X-Frame-Options: DENY");
// Content Security Policy (CSP) for security
header(
  "Content-Security-Policy: " .
  "default-src 'self' https://www.google.com; " .
  "script-src 'self' https://apis.google.com https://fonts.googleapis.com https://fonts.gstatic.com https://www.google.com https://cdn.jsdelivr.net https://code.jquery.com https://www.gstatic.com; " .
  "style-src 'self' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
  "img-src 'self' https://cdn.jsdelivr.net data:; " .
  "connect-src 'self'; " .
  "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
  "object-src 'none'; " .
  "frame-src 'self' https://www.google.com; " .
  "upgrade-insecure-requests;"
);
?>

<!DOCTYPE html>
<html class="min-vh-100">

  <head>
    <!-- Standard 16x16 -->
    <link rel="icon" href="<?php echo $base_url ?>/images/favicon/favicon-16x16.png" sizes="16x16" type="image/png">
    <!-- Standard 32x32 -->
    <link rel="icon" href="<?php echo $base_url ?>/images/favicon/favicon-32x32.png" sizes="32x32" type="image/png">
    <!-- Android Chrome (192x192) -->
    <link rel="icon" href="<?php echo $base_url ?>/images/favicon/android-chrome-192x192.png" sizes="192x192"
      type="image/png">
    <!-- Android Chrome (512x512) -->
    <link rel="icon" href="<?php echo $base_url ?>/images/favicon/android-chrome-512x512.png" sizes="512x512"
      type="image/png">
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="<?php echo $base_url ?>/images/favicon/apple-touch-icon.png">
    <!-- Standard ICO file -->
    <link rel="icon" href="<?php echo $base_url ?>/images/favicon/favicon.ico" type="image/x-icon">

    <title>Start Building a Budget</title>
    <meta name="description" content="A free application for building a budget and tracking your expenses." />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url ?>css/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <!-- Google reCAPTCHA script -->
    <link rel="preconnect" href="https://www.google.com">
    <link rel="preconnect" href="https://www.gstatic.com" crossorigin>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>