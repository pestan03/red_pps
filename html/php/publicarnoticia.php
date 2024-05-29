<?php
include_once './conexion.php';

// Crear conexión
try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit(); // Salir del script si hay un error de conexión
}

// Verificar si la sesión existe
if (!isset($_COOKIE['cookie_session'])) {
    header("Location: ../iniciarsesion.html");
    exit();
}

// Obtener el ID de sesión de la cookie
$idsession = $_COOKIE['cookie_session'];

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el valor del título y contenido de la noticia y limpiarlos para evitar inyección de SQL
    $titulo = htmlspecialchars($_POST['titulo']);
    $contenido = htmlspecialchars($_POST['contenido']);

    // Verificar si se ha enviado una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['size'] > 0) {
        // Verificar el tipo MIME del archivo
        $nombreTempArchivo = $_FILES['imagen']['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipoMIME = finfo_file($finfo, $nombreTempArchivo);
        finfo_close($finfo);

        // Lista blanca de tipos MIME permitidos
        $tiposMIMEPermitidos = array("image/jpeg", "image/jpg", "image/png");
        if (!in_array($tipoMIME, $tiposMIMEPermitidos)) {
            echo "Error: El archivo no es un JPEG válido.";
            exit();
        }

        // Guardar la imagen en la base de datos
        $imagenData = file_get_contents($nombreTempArchivo);
        // Preparar la consulta SQL para evitar la inyección de SQL
        $stmt = $conn->prepare("INSERT INTO noticias (titulo_noticia, contenido_noticia, imagen, id_usuario) VALUES (:titulo, :contenido, :imagen, :id_usuario)");
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':contenido', $contenido);
        $stmt->bindParam(':imagen', $imagenData, PDO::PARAM_LOB);
        $stmt->bindParam(':id_usuario', $idsession);
        $stmt->execute();
    } else {
        // Si no se envió una imagen, guardar solo el título y contenido de la noticia
        // Preparar la consulta SQL para evitar la inyección de SQL
        $stmt = $conn->prepare("INSERT INTO noticias (titulo_noticia, contenido_noticia, id_usuario) VALUES (:titulo, :contenido, :id_usuario)");
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':contenido', $contenido);
        $stmt->bindParam(':id_usuario', $idsession);
        $stmt->execute();
    }
    // Redirigir después de la inserción
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit(); 
}
?>
<script src="index.js"></script>
