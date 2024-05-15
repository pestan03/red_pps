<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/perfil.css">
    <title>Perfil de Usuario</title>
</head>

<body>
    <?php
    include './php/conexion.php';
    // Verificar si la cookie de sesión existe
    if (isset($_COOKIE['cookie_session'])) {
        // Obtener el ID del usuario desde la cookie de sesión
        $usuario_id = $_COOKIE['cookie_session'];

        // Conectar a la base de datos
        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Obtener información del usuario
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $usuario_id);
            $stmt->execute();

            // Mostrar información del usuario
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                $imagen = $usuario['foto_perfil'];
                echo '<div class="contenedor-nombre-usuario">';
                echo '<h2 class="nombre-usuario">Nombre de usuario: ' . htmlspecialchars($usuario['user']) . '</h2>';
                if (!empty($imagen)) {
                    echo '<div class="foto-perfil-container">';
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($imagen) . '" style="width:20%; height:auto;"class="foto-perfil" onClick="selectImage()">';
                    echo '</div>';
                } else {
                    echo '<div class="foto-perfil-container">';
                    echo '<img src="./fotos/userblanco.png" class="foto-perfil" onClick="selectImage()">';
                    echo '</div>';
                }
                echo '<p class="texto-dni">DNI:' . htmlspecialchars($usuario['dni']) . '</p>';
                echo '<p class="texto-email">EMAIL:' . htmlspecialchars($usuario['email']) . '</p>';
                echo '</div>';
            } else {
                echo "Error: No se encontró el usuario con el ID proporcionado.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>

    <!-- Formulario para actualizar la foto de perfil -->
    <form action="/php/procesar_imagen.php" method="post" enctype="multipart/form-data">
        <label for="imagen">Seleccionar imagen de perfil:</label><br>
        <!-- Agrega un input file oculto -->
        <input type="file" id="imagen" style="display: none;" name="imagen" accept="image/jpeg, image/png" required>

        <input type="submit" id="confirmar" value="CONFIRMAR CAMBIO">
    </form>
    <CENTER>

        <a href="./cambiarcontrasena.html">Cambiar Contraseña</a>
    </CENTER>
    <script src="index.js"></script>
</body>

</html>
