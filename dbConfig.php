<?php
// DB details (Docker)
$dbHost = 'db';
$dbUsername = 'eshopuser';
$dbPassword = 'eshoppass';
$dbName = 'eshop';

// Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Unable to connect database: " . $db->connect_error);
}
?>