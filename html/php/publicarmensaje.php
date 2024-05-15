<?php
// Conexión a la base de datos (modifica estos valores según tu configuración)
$servername = "db";
$username = "root";
$database = "red";
$password = "root_password";

// Crear conexión
$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_COOKIE['cookie_session'])) {
        //obtener el valor de content
        $content = $_POST['content'];
        // Obtener el ID de sesión de la cookie
        $idsession = $_COOKIE['cookie_session'];
        
        // Consulta para insertar el mensaje en la base de datos
        $insercion = "INSERT INTO mensajes (user_id, message, datesent) VALUES (:user_id, :message, NOW())";

        // Preparar la consulta
        $stmt = $conn->prepare($insercion);
        
        // Asignar valores a los marcadores de posición y ejecutar la consulta
        $stmt->execute(array(':user_id' => $idsession, ':message' => $content));
        header("Location: '../index.php'");
    } else {
        echo "Error: No se encontró la cookie de sesión.";
    }
}
?>
