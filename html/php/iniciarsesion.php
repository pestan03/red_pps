<?php
/**
 * FILE: iniciarsesion.php
 * DESCRIPTION: Handles user login.
 */

// Include the database connection file
include_once './conexion.php';

// Create connection
// Set PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the username and password fields are not empty
    if (!empty($_POST['user']) && !empty($_POST['pass'])) {
        $user = $_POST['user'];
        $pass = $_POST['pass'];

        // Query to retrieve the password hash from the database
        $sql = "SELECT id, user, password FROM usuarios WHERE user = :user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $user);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['password'];

            // Calculate the SHA-256 hash of the entered password
            $hashed_password = hash('sha256', $pass);

            // Check if the hashes match
            if ($hashed_password === $stored_password) {
                // Start session and redirect the user to a welcome page
                session_start();
                setcookie("cookie_session", $row['id'], time() + 3600, "/");
                header("Location: ../index.php");
            } else {
                // If the credentials are incorrect, display an error message
                header("Location: {$_SERVER['HTTP_REFERER']}");
            }
        } else {
            // If the user does not exist in the database, display an error message
            header("Location: {$_SERVER['HTTP_REFERER']}");
        }
    } else {
        // If the fields are empty, display an error message
        echo "Por favor, complete todos los campos.";
    }
}

// If there is no active session, display the login form
?>
