<?php

// Set the X-Frame-Options header to prevent embedding in an iframe (for security purposes)
header("X-Frame-Options: DENY");
// Content Security Policy (CSP) for security
header(
    "Content-Security-Policy: " .
        "default-src 'self' https://www.google.com; " .
        "script-src 'self' https://apis.google.com https://fonts.googleapis.com https://fonts.gstatic.com https://www.google.com https://cdn.jsdelivr.net https://code.jquery.com https://www.gstatic.com; " .
        "style-src 'self' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
        "img-src 'self' https://cdn.jsdelivr.net data:; " .
        "connect-src 'self' https://www.google.com https://www.gstatic.com; " .
        "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
        "object-src 'none'; " .
        "frame-src 'self' https://www.google.com; " .
        "upgrade-insecure-requests;"
);
header("Referrer-Policy: no-referrer-when-downgrade");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
// Disable geolocation, camera, and microphone (these aren't needed for this web app)
header("Permissions-Policy: geolocation=(), camera=(), microphone=()");
