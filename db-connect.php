<?php
$host     = 'localhost';
$username = 'root';
$password = '09102445986';
$dbname   ='dummy_db';

$conn = new mysqli($host, $username, $password, $dbname);
if(!$conn){
    die("Cannot connect to the database.". $conn->error);
}
$sql = "SELECT * FROM register";