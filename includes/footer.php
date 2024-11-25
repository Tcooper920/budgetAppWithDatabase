<?php
// Define the base URL relative to the site's root to create dynamic links...
$base_url = "/budget_app_with_database/";
?>

<div class="container pt-5 pb-3 px-3 px-sm-0">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top mt-5">
        <p class="col-md-4 mb-0 text-light">© BudgetBuilder</p>

        <a href="/"
            class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <img src="<?php echo $base_url; ?>/images/budget-builder-logo.svg" width="40" height="40" alt="Budget Builder logo" title="Budget Builder">
        </a>

        <ul class="nav col-md-4 justify-content-end">
            <li class="nav-item"><a href="#" class="nav-link px-2 text-light">My budget</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-light">Register</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-light">Login</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-light">Delete account</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-light">Logout</a></li>
        </ul>
    </footer>
</div>

</html>