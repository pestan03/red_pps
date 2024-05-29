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
    include_once './php/conexion.php';
    // Verificar si la cookie de sesión existe y está autenticada
    if (isset($_COOKIE['cookie_session']) && isset($_COOKIE['session_token']) && isset($_SESSION['session_token'])) {
        // Obtener el ID del usuario desde la cookie de sesión
        $usuario_id = $_COOKIE['cookie_session'];

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
                ?>
                <div class="contenedor-nombre-usuario">
                    <h2 class="nombre-usuario">Nombre de usuario: <?php echo htmlspecialchars($usuario['user']); ?></h2>
                    <div class="foto-perfil-container">
                        <?php if (!empty($imagen)) { ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($imagen); ?>" class="foto-perfil" onClick="selectImage()">
                        <?php } else { ?>
                            <img src="./fotos/userblanco.png" class="foto-perfil" onClick="selectImage()">
                        <?php } ?>
                    </div>
                    <p class="texto-dni">DNI: <?php echo htmlspecialchars($usuario['dni']); ?></p>
                    <p class="texto-email">EMAIL: <?php echo htmlspecialchars($usuario['email']); ?></p>
                </div>
                <?php
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
        <input type="file" id="imagen" style="display: none;" name="imagen" accept="image/jpeg, image/png" required>
        <input type="submit" id="confirmar" value="CONFIRMAR CAMBIO DE IMAGEN">
    </form>

    <center>
        <a href="./cambiarcontrasena.html">Cambiar Contraseña</a>
    </center>
    <script src="index.js"></script>
</body>
</html>
