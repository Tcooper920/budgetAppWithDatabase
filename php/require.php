<?php
# Incorporate the MySQL connection script.
require ('../../../connect_budget_database.php');
# Display MySQL version and host.
if (mysqli_ping($budget_database)) {
    echo 'MySQL Server ' . mysqli_get_server_info($budget_database) .
        ' connected on ' . mysqli_get_host_info($budget_database);
}
?>