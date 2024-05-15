<?php
// Conexión a la base de datos (modifica estos valores según tu configuración)
include './conexion.php';

// Verifica si se ha enviado el formulario de manera segura
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si los campos de usuario y contraseña no están vacíos
    if (!empty($_POST['pass_antigua']) && !empty($_POST['pass_nueva'])) {
        $pass_antigua = $_POST['pass_antigua'];
        $pass_nueva = $_POST['pass_nueva'];

        // Prepara la consulta para obtener el hash de la contraseña desde la base de datos
        $sql = "SELECT password FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $_COOKIE['cookie_session']);
        $stmt->execute();

        // Verifica si se encontró el usuario
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['password'];

            // Calcula el hash SHA-256 de la contraseña antigua ingresada
            $hashed_password_antigua = hash('sha256', $pass_antigua);

            // Verifica si los hashes coinciden
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
                header("Location: ../perfil.php");
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
