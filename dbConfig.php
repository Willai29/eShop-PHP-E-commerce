<?php
<<<<<<< HEAD
// DB details (Docker)
$dbHost = 'db';             // VERY IMPORTANT (Docker service name)
$dbUsername = 'eshopuser';  // from docker-compose
$dbPassword = 'eshoppass';  // from docker-compose
$dbName = 'eshop';          // must match docker-compose

// Create connection and select DB
=======
//DB details
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'cart1';

//Create connection and select DB
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Unable to connect database: " . $db->connect_error);
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
