<?php
/* Database connection settings */
$host = 'db';
$user = 'eshopuser';
$pass = 'eshoppass';
$db = 'eshop';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}
?>