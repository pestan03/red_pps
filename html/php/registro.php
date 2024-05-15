<?php
include './conexion.php';

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
            if (validar_contraseña($pass)) {
                // Convertir la contraseña a hash
                $hashed_password = hash('sha256', $pass);

                // Comprobar la correspondencia DNI+letra utilizando la función comprobar_dni_letra
                if (comprobar_dni_letra($dni)) {
                    // Preparar consulta para insertar los datos en la base de datos utilizando consultas preparadas
                    $stmt_insert_user = $conn->prepare("INSERT INTO usuarios (user, password, dni, email) VALUES (?, ?, ?, ?)");
                    $stmt_insert_user->execute([$user, $hashed_password, $dni, $email]);
                    header("Location: ../index.php");
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
function validar_contraseña($password) {
    // La contraseña debe tener al menos 8 caracteres
    if (strlen($password) < 8) {
        return false;
    }

    // La contraseña debe contener al menos una letra mayúscula
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }

    // La contraseña debe contener al menos una letra minúscula
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }

    // La contraseña debe contener al menos un dígito numérico
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }

    // La contraseña debe contener al menos un carácter especial
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        return false;
    }

    return true;
}

// Función para comprobar la correspondencia DNI+letra
function comprobar_dni_letra($dni_completo) {
    // Extraer solo los números del DNI
    $numeros_dni = substr($dni_completo, 0, -1);
    $letra_dni = strtoupper(substr($dni_completo, -1));

    // Calcular la letra del DNI
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    $indice = $numeros_dni % 23;
    $letra_calculada = $letras[$indice];

    // Comparar la letra del DNI calculada con la letra proporcionada
    return $letra_calculada === $letra_dni;
}

?>
<script src="index.js"></script>

