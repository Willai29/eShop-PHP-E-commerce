<?php
// DB details (Docker)
$dbHost = 'db';             // VERY IMPORTANT (Docker service name)
$dbUsername = 'eshopuser';  // from docker-compose
$dbPassword = 'eshoppass';  // from docker-compose
$dbName = 'eshop';          // must match docker-compose

// Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Unable to connect database: " . $db->connect_error);
}
?>