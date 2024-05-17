<?php
// Header block
/**
 * File: conexion.php
 * Description: Establishes a connection to the database.
 */

// Database credentials
$servername = "db";
$username = "root";
$password = "root_password"; // No password
$database = "red";
$port = 3306;

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Connection error handling
    error_log("Connection error: " . $e->getMessage());
    die("Database connection error. Please try again later.");
}
?>
