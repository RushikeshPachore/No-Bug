<?php
$servername = "localhost";
$username = "root";
$password = "rushikesh@1998";
$dbname = "nobug";

// new mysqli() is a built-in function in PHP for establishing MySQL connections in object-oriented style.
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully";
session_start();
