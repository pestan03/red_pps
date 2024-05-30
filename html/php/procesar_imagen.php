<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/perfil.css">
    <title>Actualización de Foto de Perfil</title>
</head>
<body>
<?php
// Conectar a la base de datos
include_once './conexion.php';

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ID del usuario al que deseas asociar la nueva imagen
    $id_usuario = htmlspecialchars($_COOKIE['cookie_session']); // Obtén el ID del usuario de la cookie de sesión

    // Verificar si se ha enviado un archivo
    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Obtener información sobre el archivo enviado
        $imagen_temporal = $_FILES['imagen']['tmp_name'];
        $tipo_imagen = mime_content_type($imagen_temporal);
        $ext_imagen = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

        // Comprobar si el archivo es una imagen JPEG o PNG y tiene la extensión correcta
        $valid_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $valid_extensions = ['jpeg', 'png', 'jpg'];

        if (in_array($tipo_imagen, $valid_types) && in_array($ext_imagen, $valid_extensions)) {
            // Leer el contenido del archivo en forma de flujo de bytes
            $imagen_contenido = file_get_contents($imagen_temporal);

            // Preparar la sentencia SQL para actualizar la foto de perfil
            $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = :nueva_imagen WHERE id = :id_usuario");

            // Vincular parámetros
            $stmt->bindParam(':nueva_imagen', $imagen_contenido, PDO::PARAM_LOB);
            $stmt->bindParam(':id_usuario', $id_usuario);

            // Ejecutar la sentencia
            $stmt->execute();

            echo '<script>window.location.href = "/perfil.php";</script>';
            exit();
        } else {
            echo "<h2>Error: Se permiten solo archivos JPG, JPEG o PNG con extensiones correctas.</h2>";
        }
    } else {
        echo "<h2>Error al cargar la imagen: " . htmlspecialchars($_FILES['imagen']['error']) . "</h2>";
    }
} catch(PDOException $e) {
    echo "<h2>Error: " . htmlspecialchars($e->getMessage()) . "</h2>";
}
?>
</body>
</html>
