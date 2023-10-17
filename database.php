<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "09102445986";
$dbName = "dummy_db";

// Create a connection
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the character set
mysqli_set_charset($conn, "utf8");
?>