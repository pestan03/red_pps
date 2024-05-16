<?php
// noticias_usuario.php

// Incluir el archivo de conexión a la base de datos
include_once './conexion.php';

// Verificar si se recibió un ID de usuario
if (isset($_GET['usuario_id'])) {
    // Obtener el ID de usuario y limpiarlo para evitar inyección de SQL
    $usuario_id = $_GET['usuario_id'];

    // Preparar la consulta SQL para recuperar las noticias del usuario
    $sql = "SELECT noticias.id_noticia, noticias.titulo_noticia, noticias.contenido_noticia, noticias.fecha_publicacion, usuarios.user, usuarios.foto_perfil
            FROM noticias 
            INNER JOIN usuarios ON noticias.id_usuario = usuarios.id
            WHERE usuarios.id = :usuario_id
            ORDER BY noticias.fecha_publicacion DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener las noticias del usuario
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir las noticias a formato JSON y enviarlas de vuelta al cliente
    echo json_encode($noticias);
} else {
    // Si no se recibió un ID de usuario, retornar un mensaje de error
    echo json_encode(array('error' => 'No se proporcionó un ID de usuario.'));
}
?>
