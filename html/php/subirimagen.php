<?php
// Conexión a la base de datos
include_once 'conexion.php';

// Verificar si se ha enviado el formulario de manera segura
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Obtener el usuario ID de la cookie de sesión
    $usuario_id = $_COOKIE['cookie_session'];

    // Verificar si se ha seleccionado una imagen
    if (!empty($_FILES['imagen']['tmp_name']) && is_uploaded_file($_FILES['imagen']['tmp_name'])) {
        // Obtener el contenido de la imagen y convertirlo a binario
        $imagen_temp = file_get_contents($_FILES['imagen']['tmp_name']);

        // Consulta SQL para insertar la imagen en la base de datos
        $sql = "INSERT INTO galeria (usuario_id, imagen) VALUES (:usuario_id, :imagen)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':imagen', $imagen_temp, PDO::PARAM_LOB);
        $stmt->execute();

        // Redirigir de vuelta a la página principal
        header("Location: ../../galeria.php");
        exit();
    } else {
        echo "Por favor, selecciona una imagen.";
    }
}
?>
