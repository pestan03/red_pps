<?php
function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception("El archivo .env no existe.");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Ignorar comentarios
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Cargar las variables de entorno desde el archivo .env
loadEnv(__DIR__ . '/.env');

// Obtener las variables de entorno desde $_ENV
$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_NAME'];
$port = $_ENV['DB_PORT'];

try {
    // Crear una instancia de PDO para la conexión a la base de datos
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
    // Establecer el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Manejo de errores de conexión
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión a la base de datos. Por favor, inténtalo de nuevo más tarde.");
}
?>
