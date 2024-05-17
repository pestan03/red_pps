<?php
/**
 * FILE: publicarnoticia.php
 * DESCRIPTION: Handles publishing a news article.
 */

include_once './conexion.php';

// Create connection
try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
}

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_COOKIE['cookie_session'])) {
        // Get the session ID from the cookie
        $idsession = $_COOKIE['cookie_session'];
        // Get the title and content values of the news article and sanitize them to prevent SQL injection
        $titulo = htmlspecialchars($_POST['titulo']);
        $contenido = htmlspecialchars($_POST['contenido']);

        // Check if an image has been submitted
        if (isset($_FILES['imagen']) && $_FILES['imagen']['size'] > 0) {
            // Check if the file is JPEG and does not contain malicious code
            $nombreTempArchivo = $_FILES['imagen']['tmp_name'];
            $tipoArchivo = $_FILES['imagen']['type'];
            $tamanoArchivo = $_FILES['imagen']['size'];
            $extensionArchivo = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

            // Check the file extension
            $extensionesValidas = array("jpeg", "jpg");
            if (!in_array($extensionArchivo, $extensionesValidas)) {
                echo "Error: El archivo debe tener extensión .jpeg o .jpg";
                exit();
            }

            // Check the MIME type of the file
            if ($tipoArchivo !== "image/jpeg") {
                echo "Error: El archivo no es un JPEG válido.";
                exit();
            }

            // Check if the file contains malicious code
            $contenidoArchivo = file_get_contents($nombreTempArchivo);
            if (preg_match("/<\?php|<\?|<script|<html|<iframe/i", $contenidoArchivo)) {
                echo "Error: El archivo parece contener código malicioso.";
                exit();
            }

            // Save the image in the database
            $imagenData = file_get_contents($nombreTempArchivo);
            // Prepare the SQL query to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO noticias (titulo_noticia, contenido_noticia, imagen, id_usuario) VALUES (:titulo, :contenido, :imagen, :id_usuario)");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':imagen', $imagenData, PDO::PARAM_LOB);
            $stmt->bindParam(':id_usuario', $idsession);
            $stmt->execute();
        } else {
            // If no image was submitted, save only the title and content of the news article
            // Prepare the SQL query to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO noticias (titulo_noticia, contenido_noticia, id_usuario) VALUES (:titulo, :contenido, :id_usuario)");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':id_usuario', $idsession);
            $stmt->execute();
        }
        // Redirect after insertion
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        echo "Error: No se encontró la cookie de sesión.";
    }
}
?>
<script src="index.js"></script>
