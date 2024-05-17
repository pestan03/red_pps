<?php
include_once './conexion.php';

// Crear conexión
try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
}

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_COOKIE['cookie_session'])) {
        // Obtener el ID de sesión de la cookie
        $idsession = $_COOKIE['cookie_session'];
        // Obtener el valor del título y contenido de la noticia y limpiarlos para evitar inyección de SQL
        $titulo = htmlspecialchars($_POST['titulo']);
        $contenido = htmlspecialchars($_POST['contenido']);
        
        // Verificar si se ha enviado una imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['size'] > 0) {
            // Verificar si el archivo es JPEG y no contiene código malicioso
            $nombreTempArchivo = $_FILES['imagen']['tmp_name'];
            $tipoArchivo = $_FILES['imagen']['type'];
            $tamanoArchivo = $_FILES['imagen']['size'];
            $extensionArchivo = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            
            // Verificar la extensión del archivo
            $extensionesValidas = array("jpeg", "jpg");
            if (!in_array($extensionArchivo, $extensionesValidas)) {
                echo "Error: El archivo debe tener extensión .jpeg o .jpg";
                exit();
            }
            
            // Verificar el tipo MIME del archivo
            if ($tipoArchivo !== "image/jpeg") {
                echo "Error: El archivo no es un JPEG válido.";
                exit();
            }
            
            // Verificar si el archivo contiene código malicioso
            $contenidoArchivo = file_get_contents($nombreTempArchivo);
            if (preg_match("/<\?php|<\?|<script|<html|<iframe/i", $contenidoArchivo)) {
                echo "Error: El archivo parece contener código malicioso.";
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
    } else {
        echo "Error: No se encontró la cookie de sesión.";
    }
}
?>
<script src="index.js"></script>
