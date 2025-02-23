<?php
// Define the base URL relative to the site's root to create dynamic links...
$base_url = "/budget_app_with_database/"; // Replace 'your_project_folder' with your actual project name if needed
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url ?>css/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <!-- Google reCAPTCHA script -->
    <link rel="preconnect" href="https://www.google.com">
    <link rel="preconnect" href="https://www.gstatic.com" crossorigin>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>