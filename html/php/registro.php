<?php
include_once './conexion.php';
include_once './funciones.php';


try {
    // Crear conexión PDO
    // Establecer el modo de error PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los valores enviados por el formulario
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $dni = $_POST['dni'];
        $email=$_POST['email']; 

        // Preparar consulta para verificar si el nombre de usuario ya existe
        $stmt_check_user = $conn->prepare("SELECT * FROM usuarios WHERE user = ?");
        $stmt_check_user->execute([$user]);

        if ($stmt_check_user->rowCount() > 0) {
            echo "El nombre de usuario ya está en uso.";
        } else {
            // Comprobar la fortaleza de la contraseña utilizando la función validar_contraseña
            if (Funciones::validarContrasena($pass)) {
                // Convertir la contraseña a hash
                $hashed_password = hash('sha256', $pass);

                // Comprobar la correspondencia DNI+letra utilizando la función comprobar_dni_letra de la clase Funciones
                if (Funciones::comprobarDniLetra($dni)) {
                    // Preparar consulta para insertar los datos en la base de datos utilizando consultas preparadas
                    $stmt_insert_user = $conn->prepare("INSERT INTO usuarios (user, password, dni, email) VALUES (?, ?, ?, ?)");
                    $stmt_insert_user->bindParam(':user', $user);
                    $stmt_insert_user->bindParam(':password', $hashed_password);
                    $stmt_insert_user->bindParam(':dni', $dni);
                    $stmt_insert_user->bindParam(':email', $email);
                    $stmt_insert_user->execute();
                    echo '<script type="text/javascript">';
                    echo 'window.location.href="../index.php";';
                    echo '</script>';
                    exit();
                } else {
                    echo "La letra del DNI no corresponde al número proporcionado.";
                }
            } else {
                echo "La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, dígitos numéricos y algún carácter especial.";
            }
        }
    }
} catch(PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
}

// Función para validar la fortaleza de la contraseña

?>
<script src="index.js"></script>

