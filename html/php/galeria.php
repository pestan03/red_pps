<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Imágenes</title>
</head>
<body>
    <h1>Galería de Imágenes</h1>
    
    <!-- Formulario para subir imágenes -->
    <form action="subirimagen.php" method="post" enctype="multipart/form-data">
        <label for="imagen">Selecciona una imagen:</label>
        <input type="file" name="imagen" id="imagen">
        <input type="submit" value="Subir Imagen" name="submit">
    </form>

    <hr>

    <!-- Mostrar imágenes del usuario -->
    <h2>Tus Imágenes</h2>
    <div class="galeria">
        <?php
        // Conexión a la base de datos
        include_once 'conexion.php';

        // Obtener el usuario ID de la cookie de sesión
        $usuario_id = $_COOKIE['cookie_session'];

        // Consulta SQL para obtener las imágenes del usuario
        $sql = "SELECT imagen FROM galeria WHERE usuario_id = :usuario_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();

        // Mostrar las imágenes del usuario
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['imagen']) . '" alt="Imagen">';
        }
        ?>
    </div>
</body>
</html>
