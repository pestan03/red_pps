<?php
/**
 * File: noticias_usuario.php
 * Description: Retrieves news articles associated with a specific user.
 */

// Include the database connection file
include_once './conexion.php';

// Check if a user ID was received
if (isset($_GET['usuario_id'])) {
    // Get the user ID and sanitize to prevent SQL injection
    $usuario_id = $_GET['usuario_id'];

    // Prepare the SQL query to retrieve news articles from the user
    $sql = "SELECT noticias.id_noticia, noticias.titulo_noticia, noticias.contenido_noticia, noticias.fecha_publicacion, usuarios.user, usuarios.foto_perfil
            FROM noticias
            INNER JOIN usuarios ON noticias.id_usuario = usuarios.id
            WHERE usuarios.id = :usuario_id
            ORDER BY noticias.fecha_publicacion DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    // Get the user's news articles
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert the news articles to JSON format and send them back to the client
    echo json_encode($noticias);
} else {
    // If no user ID was received, return an error message
    echo json_encode(array('error' => 'No se proporcionó un ID de usuario.'));
}
