<?php
    $servername = "db";
    $username = "id22220192_pablo";
    $password = "pabloESP03@"; // Sin contraseña
    $database = "id22220192_red";
    $port= 3306;
    try {
        $conn = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
        // Establecer el modo de error PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        // Manejo de errores de conexión
        error_log("Error de conexión: " . $e->getMessage());
        die("Error de conexión a la base de datos. Por favor, inténtalo de nuevo más tarde.");
    }
