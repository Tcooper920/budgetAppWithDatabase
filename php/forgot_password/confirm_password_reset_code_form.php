<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
    <form action="process_password_reset_code.php" method="POST" id="confirmation-form"
        class="fs-5 p-4 p-sm-5 my-3 bg-light rounded dark-blue">
        <h1 class="pb-3">Validate your account</h1>
        <label for="confirmation_code">Enter the confirmation code sent to your email:</label>
        <div class="mt-3">
            <!-- Error messages will display below -->
            <?php

            if (isset($errors['incorrectOrExpiredVerificationCode']) || isset($errors['codeVerificationAttemptsExceeded'])) {
                echo '<p class="error-msg text-danger mb-0">';
            }

            if (isset($errors['incorrectOrExpiredVerificationCode']))
                echo $errors['incorrectOrExpiredVerificationCode'];

            if (isset($errors['codeVerificationAttemptsExceeded']))
                echo $errors['codeVerificationAttemptsExceeded'];

            echo '</p>';

            ?>
            <input type="text" name="confirmation_code" id="confirmation_code"
                class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill" required>
        </div>
        <!-- Honeypot field -->
        <input type="text" name="honeypot" value="" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
        <!-- CSRF token field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"
            class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
        <div class="mt-3">
            <input type="submit" value="Confirm code" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
            <button type="button" id="resend-code" class="btn text-button text-primary mt-2 fs-5">Email me another code</button>
        </div>
    </form>
</main>