<?php
# Send a confirmation email to the user when they click "Register"...
# This code uses the PHPMailer library with a Gmail account for sending the emails.
# About 500 emails can be sent daily. Upgrade the Gmail account, if needed.

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('PHPMailer/src/Exception.php');
require('PHPMailer/src/PHPMailer.php');
require('PHPMailer/src/SMTP.php');
include('../../config/config.php');

//Create a new PHPMailer instance
$mail = new PHPMailer();

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
//SMTP::DEBUG_OFF = off (for production use)
//SMTP::DEBUG_CLIENT = client messages
//SMTP::DEBUG_SERVER = client and server messages
$mail->SMTPDebug = 0;

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
//Use `$mail->Host = gethostbyname('smtp.gmail.com');`
//if your network does not support SMTP over IPv6,
//though this may cause issues with TLS

//Set the SMTP port number:
// - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
// - 587 for SMTP+STARTTLS
$mail->Port = 587;

//Set the encryption mechanism to use:
// - SMTPS (implicit TLS on port 465) or
// - STARTTLS (explicit TLS on port 587)
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = 'budgetbuilderapp@gmail.com';

//Password to use for SMTP authentication
$mail->Password = $smtp_authentication_password;

//Set who the message is to be sent from
//Note that with gmail you can only use your account address (same as `Username`)
//or predefined aliases that you have configured within your account.
//Do not use user-submitted addresses in here
$mail->setFrom('budgetbuilderapp@gmail.com', 'BudgetBuilder');

// Set who the message is to be sent to by entering the user's email
if (isset($_SESSION['user_data'])) {
    $user_data = $_SESSION['user_data'];

    if (isset($user_data['email'])) {
        $mail->addAddress($user_data['email'], 'User');
    } else {
        die('Email address not found.');
    }
} else {
    die('User data not found.');
}

//Set the subject line
$mail->Subject = 'BudgetBuilder';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('confirmation_email_contents.html'), __DIR__);

$htmlContent = file_get_contents('confirmation_email_contents.html');

# Generate a confirmation code...
$confirmation_code = rand(100000, 999999); // 6-digit code
$timestamp = time(); // Current timestamp (in seconds)
$_SESSION['confirmation_code'] = $confirmation_code; // Store in session
$_SESSION['timestamp'] = $timestamp;

// Replace the placeholder with the PHP variable...
$htmlContent = str_replace('{{confirmation_code}}', $confirmation_code, $htmlContent);

// Now set the modified HTML content for the email...
$mail->msgHTML($htmlContent, __DIR__);

// Replace the plain text body with one created manually
// $mail->AltBody = 'This is a plain-text message body';

// Embed necessary images
$mail->addEmbeddedImage('../../images/budget-builder-logo-horizontal.png', 'image_cid');

// Set the email format to HTML
$mail->isHTML(true);

//send the message, check for errors
if (!$mail->send()) {
    echo "<p>Mailer Error: $mail->ErrorInfo</p>";
} else {
    echo '<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
            <form action="confirm_code.php" method="POST" id="confirmation-form" class="fs-5 p-4 p-sm-5 my-3 bg-light rounded dark-blue">
                <h1 class="pb-3">Validate your account</h1>
                <label for="confirmation_code">Enter the confirmation code sent to your email:</label>
                <div class="mt-3">
                    <input type="text" name="confirmation_code" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill" required>
                </div>
                <div class="mt-3">
                    <input type="submit" value="Confirm code" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                    <button type="button" id="resend-code" class="btn text-primary mt-2 fs-5">Email me another code</button>
                </div>
            </form>
          </main>';
          
          include("../../includes/footer.php");

    echo '<script src="../../js/preserve_scroll_position_onload.js"></script>
          <script src="../../js/resend_registration_confirmation_code.js"></script>
          </body>
          </html>';
}