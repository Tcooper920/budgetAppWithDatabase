<?php
# Incorporate the MySQL connection script.
require ('../../../connect_budget_database.php');
# Display MySQL version and host.
// if (mysqli_ping($budget_database)) {
//     echo 'MySQL Server ' . mysqli_get_server_info($budget_database) .
//         ' connected on ' . mysqli_get_host_info($budget_database);
// }

/* 

Outside the htdocs folder, connect_budget_database.php file is structured like this: 

<?php

$budget_database = mysqli_connect
('localhost', '{ user }', '{ password }', '{ database name }')
    or die;
# Set encoding to match PHP script encoding.
mysqli_set_charset($budget_database, 'utf8');
?>

*/


