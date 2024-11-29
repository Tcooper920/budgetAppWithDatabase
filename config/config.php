<?php
# Require the file containing the password for SMTP authentication (for gmail/PHPMailer)
# The password is referenced as "$smtp_authentication_password" in this program

$base_path = realpath(dirname(__FILE__));  // Get the current script's directory

require ("$base_path/../../../budget_smtp_authentication_password.php");