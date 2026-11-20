<?php
// ============================================================
// Database Connection Configuration (Core PHP - MySQLi)
// ============================================================

$host     = "localhost";
$username = "root";       // Default XAMPP MySQL username
$password = "";           // Default XAMPP MySQL password is empty
$dbname   = "student_db"; // Name of our database

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set character set to utf8mb4 for proper character encoding
$conn->set_charset("utf8mb4");
?>
