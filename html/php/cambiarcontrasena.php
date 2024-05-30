<?php
session_start();
// Conexión a la base de datos
include_once './conexion.php';

// Verifica si se ha enviado el formulario de manera segura
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_COOKIE['cookie_session'])) {
        echo "No se ha iniciado sesión.";
        exit();
    }
    // Verifica si los campos de contraseña no están vacíos
    if (!empty($_POST['pass_antigua']) && !empty($_POST['pass_nueva']) && !empty($_POST['confirmar_pass_nueva'])) {
        $pass_antigua = htmlspecialchars($_POST['pass_antigua'], ENT_QUOTES, 'UTF-8');
        $pass_nueva = htmlspecialchars($_POST['pass_nueva'], ENT_QUOTES, 'UTF-8');
        $confirmar_pass_nueva = htmlspecialchars($_POST['confirmar_pass_nueva'], ENT_QUOTES, 'UTF-8');

        // Verifica si la nueva contraseña y su confirmación coinciden
        if ($pass_nueva !== $confirmar_pass_nueva) {
            echo "Las contraseñas nuevas no coinciden.";
            exit();
        }

        // Verifica si la nueva contraseña cumple con los requisitos de complejidad
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/', $pass_nueva)) {
            echo "La nueva contraseña debe tener al menos 12 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos.";
            exit();
        }

        // Prepara la consulta para obtener la contraseña almacenada desde la base de datos
        $sql = "SELECT password FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $_COOKIE['cookie_session']);
        $stmt->execute();

        // Verifica si se encontró el usuario
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['password'];

            // Calcula el hash SHA-256 de la contraseña antigua
            $hashed_password_antigua = hash('sha256', $pass_antigua);

            // Verifica si la contraseña antigua es válida comparando los hashes
            if ($hashed_password_antigua === $stored_password) {
                // Calcula el hash SHA-256 de la nueva contraseña
                $hashed_password_nueva = hash('sha256', $pass_nueva);

                // Actualiza la contraseña en la base de datos
                $sql_update = "UPDATE usuarios SET password = :password WHERE id = :id";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bindParam(':password', $hashed_password_nueva);
                $stmt_update->bindParam(':id', $_COOKIE['cookie_session']);
                $stmt_update->execute();

                // Redirige al usuario a una página de éxito o a su perfil, por ejemplo
                header("Location: ../index.php");
                
                exit();
            } else {
                // Si las contraseñas antiguas no coinciden, muestra un mensaje de error
                echo "La contraseña antigua es incorrecta.";
            }
        } else {
            // Si el usuario no existe en la base de datos, muestra un mensaje de error
            echo "Error: Usuario no encontrado.";
        }
    } else {
        // Si los campos están vacíos, muestra un mensaje de error
        echo "Por favor, complete todos los campos.";
    }
}
?>
