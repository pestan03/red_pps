<?php
// Conexión a la base de datos (modifica estos valores según tu configuración)
include_once './conexion.php'; 

// Crear conexión
// Establecer el modo de error PDO a excepción
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si los campos de usuario y contraseña no están vacíos
    if (!empty($_POST['user']) && !empty($_POST['pass'])) {
        $user = $_POST['user'];
        $pass = $_POST['pass'];

        // Consulta para obtener el hash de la contraseña desde la base de datos
        $sql = "SELECT id, user, password FROM usuarios WHERE user = :user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $user);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['password'];

            // Calcula el hash SHA-256 de la contraseña ingresada
            $hashed_password = hash('sha256', $pass);

            // Verifica si los hashes coinciden
            if ($hashed_password === $stored_password) {
                // Iniciar sesión y redirigir al usuario a una página de bienvenida
                session_start();

                // Configurar las opciones de la cookie
                $options = [
                    'expires' => time() + 3600, // 1 hora de expiración
                    'path' => '/',
                    'domain' => '', // Dominio actual
                    'secure' => true, // Solo para HTTPS
                    'httponly' => true, // Solo accesible a través de HTTP
                    'samesite' => 'Strict' // Política SameSite Strict
                ];
                // creacion de token
                $session_token = bin2hex(random_bytes(32));
                setcookie("session_token", $session_token, $options);
                $_SESSION['session_token'] = $session_token;
                // Establecer la cookie
                setcookie("cookie_session", $row['id'], $options);           
                header("Location: ../index.php");
            } else {
                // Si las credenciales son incorrectas, muestra un mensaje de error
                header("Location:  {$_SERVER['HTTP_REFERER']}");
            }
        } else {
            // Si el usuario no existe en la base de datos, muestra un mensaje de error
            header("Location:  {$_SERVER['HTTP_REFERER']}");
        }
    } else {
        // Si los campos están vacíos, muestra un mensaje de error
        echo "Por favor, complete todos los campos.";
    }
}



// Si no hay sesión iniciada, mostrar el formulario de inicio de sesión
?>
