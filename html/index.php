<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/inicio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Inicio</title>
</head>

<body>
    <nav class="barra">
        <div class="logo">
            <a href="index.php"><img src="../fotos/logazo.png" alt="Logo"></a>
        </div>
        <div class="buscar">
            <form id="search-form" action="./php/buscar.php" method="GET">
                <input class="buscador" type="text" id="busqueda" name="q" placeholder="&#xf002; Buscar usuarios...">
            </form>
        </div>


        <div id="botonesSesion" class="botones-sesion">
            <?php
            // Incluye el archivo de conexión a la base de datos aquí
            include './php/conexion.php';
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Evita la inyección SQL preparando la consulta
            if (isset($_COOKIE['cookie_session'])) {
                $valor_cookie = $_COOKIE['cookie_session'];
                $sql_session = "SELECT * FROM usuarios WHERE id = :id";
                $stmt = $conn->prepare($sql_session);
                $stmt->bindParam(':id', $valor_cookie);
                $stmt->execute();

                if ($stmt->rowCount() == 1) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $nombre_usuario = htmlspecialchars($row['user']);
                    echo '<div class="dropdown">';
                    echo '<button onmouseenter="showDropdown()" onmouseleave="hideDropdown()" class="dropbtn">' . $nombre_usuario . '</button>';
                    echo '<div id="myDropdown" class="dropdown-content">';
                    echo '<a href="perfil.php">Ver Perfil</a>';
                    echo '<a href="./php/cerrar_sesion.php" onClick="borrarCookie()">Cerrar sesión</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<a href="iniciarsesion.html" class="botonInicio"><i class="fas fa-lock-close"></i>Iniciar Sesión</a>';
                echo '<a href="registro.html" class="botonRegistro">Crear cuenta</a>';
            }
            ?>
        </div>
    </nav>
    <div id="resultados-busqueda" style="display:none"></div>

    <section class="mensajes">
        <?php
        // mostrar mensajes por busqueda de usuario
        if (isset($_GET['idBusqueda'])) {
            $usuario_id = $_GET['idBusqueda'];
            $sql_busqueda = "SELECT noticias.id_noticia, noticias.titulo_noticia, noticias.contenido_noticia, noticias.fecha_publicacion, usuarios.user, usuarios.id AS usuario_id, usuarios.foto_perfil
                    FROM noticias 
                    INNER JOIN usuarios ON noticias.id_usuario = usuarios.id
                    WHERE usuarios.id = :usuario_id
                    ORDER BY noticias.fecha_publicacion DESC";

            $stmt = $conn->prepare($sql_busqueda);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();

            while ($row_busqueda = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $noticia_id = $row_busqueda['id_noticia'];
                echo '<div class="mensaje" data-noticia-id="' . $noticia_id . '">';
                echo '<div class="div-perfil-mensaje">';
                if (!empty($row_busqueda['foto_perfil'])) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row_busqueda['foto_perfil']) . '" class="foto-perfil">';
                } else {
                    echo '<img src="./fotos/userblanco.png" class="foto-perfil">';
                }

                echo '<p class="nombre-usuario">' . htmlspecialchars($row_busqueda['user']) . '</p>';
                echo '</div>';
                echo '<h3 class="mensaje-texto">' . htmlspecialchars($row_busqueda['titulo_noticia']) . '</h3>';
                echo '<p class="fecha-envio">' . htmlspecialchars($row_busqueda['fecha_publicacion']) . '</p>';
                echo '</div>';
            }
        } else {
            // Mostrar todos los mensajes
            $sql = "SELECT noticias.id_noticia, noticias.titulo_noticia, noticias.contenido_noticia, noticias.fecha_publicacion, usuarios.user, usuarios.id AS usuario_id, usuarios.foto_perfil
        FROM noticias 
        INNER JOIN usuarios ON noticias.id_usuario = usuarios.id
        ORDER BY noticias.fecha_publicacion DESC";

            $result = $conn->query($sql);

            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $noticia_id = $row['id_noticia'];
                    echo '<div class="mensaje" data-noticia-id="' . $noticia_id . '">';
                    echo '<div class="div-perfil-mensaje">';
                    if (!empty($row['foto_perfil'])) {
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['foto_perfil']) . '" class="foto-perfil">';
                    } else {
                        echo '<img src="./fotos/userblanco.png" class="foto-perfil">';
                    }

                    echo '<p class="nombre-usuario">' . htmlspecialchars($row['user']) . '</p>';
                    echo '</div>';
                    echo '<h3 class="mensaje-texto">' . htmlspecialchars($row['titulo_noticia']) . '</h3>';
                    echo '<p class="fecha-envio">' . htmlspecialchars($row['fecha_publicacion']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo "No hay noticias para mostrar.";
            }
        }
        ?>
    </section>
    <div class="overlay-trigger">
        <i class="fas fa-pencil-alt"></i>
    </div>

    <div class="overlay">
        <div class="content">
            <div class="close-button"><i class="material-icons">close</i></div>
            <center>
                <form action="php/publicarnoticia.php" method="post" enctype="multipart/form-data">
                    <h2>Publicar Noticia</h2>
                    <div style="margin-bottom:20px">
                        <input type="text" name="titulo" style="width:600px; overflow-x:hiden" placeholder="Título de la noticia">
                        <textarea name="contenido" placeholder="Contenido de la noticia" style="height:200px; width:100%"></textarea>
                        <input type="file" id="imagen" name="imagen" accept="image/*">
                    </div>
                    <button class="publish-button" type="submit">Publicar</button>
                </form>
            </center>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./index.js" defer></script>

</body>

</html>