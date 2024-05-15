<?php
include './conexion.php';

// Verificar si la cookie de sesión existe
if (isset($_COOKIE['cookie_session'])) {
    // Obtener el contenido del comentario desde el formulario
    if (isset($_POST['comentario'], $_POST['idnoticia'])) {
        // Obtener el contenido del comentario y el ID de la noticia desde el formulario
        $content = htmlspecialchars($_POST['comentario']);
        $noticia_id = $_POST['idnoticia'];
        // Obtener el user_id del usuario que está comentando desde la cookie de sesión
        $user_id = $_COOKIE['cookie_session'];
        
        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insertar el nuevo comentario en la base de datos
            $sql = "INSERT INTO comentarios (id_noticia, user_id, contenido, datesent) VALUES (:id_noticia, :user_id, :content, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_noticia', $noticia_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':content', $content);
            $stmt->execute();

            // Redirigir de vuelta a la página anterior después de agregar el comentario
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: El contenido del comentario o el ID de la noticia no se han recibido.";
    }
} else {
    echo "Error: No se ha iniciado sesión.";
}
?>
<script src="index.js"></script>
