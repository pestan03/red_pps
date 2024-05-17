<?php
/**
 * FILE: comentar.php
 * DESCRIPTION: Handles commenting on a news article.
 */

include_once './conexion.php';

// Check if the session cookie exists
if (isset($_COOKIE['cookie_session'])) {
    // Get the comment content from the form
    if (isset($_POST['comentario'], $_POST['idnoticia'])) {
        // Get the comment content and the news article ID from the form
        $content = htmlspecialchars($_POST['comentario']);
        $noticia_id = $_POST['idnoticia'];
        // Get the user_id of the commenting user from the session cookie
        $user_id = $_COOKIE['cookie_session'];

        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert the new comment into the database
            $sql = "INSERT INTO comentarios (id_noticia, user_id, contenido, datesent) VALUES (:id_noticia, :user_id, :content, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_noticia', $noticia_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':content', $content);
            $stmt->execute();

            // Redirect back to the previous page after adding the comment
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
