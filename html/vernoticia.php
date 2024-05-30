<?php
include_once './php/conexion.php';
// Verificar si se proporcionó una cookie de sesión
if (!isset($_COOKIE['cookie_session'])) {
    header('Location: ../iniciarsesion.html');
    exit; // Asegurar que no haya más salida después de la redirección
}

// Verificar si se proporcionó un noticiaId en la URL
if (!isset($_GET['noticiaId'])) {
    echo "No se proporcionó ningún noticiaId en la URL.";
    exit;
}

// Recuperar el noticiaId desde la URL
$noticia_id = $_GET['noticiaId'];

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener la noticia
    $sql = "SELECT noticias.*, usuarios.*
            FROM noticias
            INNER JOIN usuarios ON noticias.id_usuario = usuarios.id
            WHERE noticias.id_noticia = :noticia_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':noticia_id', $noticia_id);
    $stmt->execute();

    // Verificar si se encontró una noticia con el ID especificado
    if ($stmt->rowCount() > 0) {
        $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./css/vernoticia.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <title>Ver Mensajes</title>
        </head>

        <body>
            <nav class="barra">
                <div class="logo">
                    <img src="../fotos/logazo.png" alt="Logo">
                </div>
                <div id="botonesSesion" class="botones-sesion">
                    <?php
                    // Verificar si existe la cookie de sesión del usuario y el token
                    if (isset($_COOKIE['cookie_session'])) {
                        $valor_cookie = $_COOKIE['cookie_session'];
                        $sql_session = "SELECT * FROM usuarios WHERE id = :id";
                        $stmt = $conn->prepare($sql_session);
                        $stmt->bindParam(':id', $valor_cookie);
                        $stmt->execute();
                        if ($stmt->rowCount() == 1) {
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $nombre_usuario = htmlspecialchars($row['user']);

                            // Mostrar el enlace al perfil con el nombre de usuario recuperado
                            echo '<div class="dropdown">';
                            echo '<button onmouseenter="showDropdown()" onmouseleave="hideDropdown()" class="dropbtn">' . $nombre_usuario . '</button>';
                            echo '<div id="myDropdown" class="dropdown-content">';
                            echo '<a href="perfil.php">Ver Perfil</a>';
                            echo '<a href="./php/cerrar_sesion.php" onclick="borrarCookie()">Cerrar sesión</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        // Si no hay sesión iniciada, mostrar los botones de registro e inicio de sesión
                        echo '<a href="iniciarsesion.html" class="botonInicio"><i class="fas fa-lock-close"></i>Iniciar Sesión</a>';
                        echo '<a href="registro.html" class="botonRegistro">Crear cuenta</a>';
                    }
                    ?>
                </div>
            </nav>
            <div class="contenedor">
                <div class="centrar-contenedor">
                    <div class="tamano-contenedor">
                        <div class="noticia">
                            <div class="div-perfil-comentario">
                                <?php
                                // Mostrar la foto de perfil del usuario que publicó la noticia
                                if (!empty($noticia['foto_perfil'])) {
                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($noticia['foto_perfil']) . '" class="foto-perfil">';
                                } else {
                                    echo '<img src="./fotos/userblanco.png" class="foto-perfil">';
                                }
                                ?>
                                <p class="nombre-usuario-noticia"><?php echo htmlspecialchars($noticia['user']) ?></p>
                            </div>
                            <p class="noticia-titulo"><?php echo htmlspecialchars($noticia['titulo_noticia']) ?></p>
                            <p class="noticia-contenido"><?php echo htmlspecialchars($noticia['contenido_noticia']) ?></p>
                            <p class="noticia-fecha"><?php echo htmlspecialchars($noticia['fecha_publicacion']) ?></p>
                            <?php
                            if (!empty($noticia['imagen'])) {
                                echo '<img style="max-width: 300px; max-height:100px; " src="data:image/jpeg;base64,' . base64_encode($noticia['imagen']) . '">';
                            }
                            ?>
                            <form class="form-comentario" action="php/comentar.php" method="post"
                                onsubmit="return agregarIdNoticia()">
                                <input type="hidden" name="idnoticia" id="idnoticia" value="">
                                <div class="contenedor-comentario">
                                    <textarea name="comentario" class="area_comentario" placeholder="Comenta..."></textarea>
                                    <button class="boton-comentario" type="submit"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </form>
                        </div>
                        <!-- PARTE COMENTARIO -->
                        <?php
                        // Consulta para obtener los comentarios
                        $sql_comentario = "SELECT comentarios.*, usuarios.*
                                            FROM comentarios
                                            INNER JOIN usuarios ON comentarios.user_id = usuarios.id
                                            WHERE comentarios.id_noticia = :noticia_id";
                        $stmt_comentario = $conn->prepare($sql_comentario);
                        $stmt_comentario->bindParam(':noticia_id', $noticia_id);
                        $stmt_comentario->execute();
                        if ($stmt_comentario->rowCount() > 0) {
                            while ($row_comentario = $stmt_comentario->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <div class="comentario" data-comentario-id="<? htmlspecialchars($row_comentario['comentario_id']) ?>">
                                    <div class="div-perfil-comentario">
                                        <?php
                                        if (!empty($row_comentario['foto_perfil'])) {
                                            echo '<img src="data:image/jpeg;base64,' . base64_encode($row_comentario['foto_perfil']) . '" class="foto-perfil">';
                                        } else {
                                            echo '<img src="./fotos/userblanco.png" class="foto-perfil">';
                                        }
                                        ?>
                                        <p class="nombre-usuario-comentario"><? htmlspecialchars($row_comentario['user']) ?></p>
                                    </div>
                                    <p class="contenido-comentario"><? htmlspecialchars($row_comentario['contenido']) ?></p>
                                    <p class="fecha-envio-comentario"><? htmlspecialchars($row_comentario['datesent']) ?></p>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <script src="index.js"></script>
        </body>

        </html>
        <?php
    } else {
        echo "No se encontró ninguna noticia con el ID especificado.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>