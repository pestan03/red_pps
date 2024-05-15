<?php
    $servername = "db";
    $username = "root";
    $password = "root_password"; // Sin contraseña
    $database = "red";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // Establecer el modo de error PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        // Manejo de errores de conexión
        error_log("Error de conexión: " . $e->getMessage());
        die("Error de conexión a la base de datos. Por favor, inténtalo de nuevo más tarde.");
    }
?>