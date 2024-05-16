<?php
// Incluir el archivo de conexión a la base de datos
include_once './conexion.php';

// Verificar si se recibió una consulta de búsqueda
if (isset($_GET['q'])) {
    // Obtener la consulta de búsqueda y limpiarla para evitar inyección de SQL
    $consulta = $_GET['q'];

    // Preparar la consulta SQL para buscar coincidencias en la columna 'user'
    $sql = "SELECT * FROM usuarios WHERE user LIKE :consulta";
    $stmt = $conn->prepare($sql);

    // Agregar los caracteres '%' para buscar coincidencias parciales
    $parametro = '%' . $consulta . '%';
    $stmt->bindParam(':consulta', $parametro, PDO::PARAM_STR);

    // Ejecutar la consulta preparada
    $stmt->execute();
    // Verificar si se encontraron resultados
    if ($stmt->rowCount() > 0) {
        // Construir el HTML para mostrar los resultados
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idBusqueda = htmlspecialchars($row['id']);
            echo '<div class="resultados-individuales">';
            echo '<figure>';
            if (!empty($row['foto_perfil'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['foto_perfil']) . '" class="foto-perfil">';
            } else {
                echo '<img src="./fotos/userblanco.png" class="foto-perfil">';
            }
            echo '<figcaption class="bus" data-user-id="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['user']) . '</figcaption>';
            echo '</figure>';
            echo '</div>';
        }

    } else {
        // Si no se encontraron resultados, mostrar un mensaje
        echo '<div class="resultados">No se encontraron resultados.</div>';
    }
} else {
    // Si no se recibió una consulta de búsqueda, mostrar un mensaje
    echo '<div class="resultados">Ingrese un término de búsqueda.</div>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/buscar.css">
    <title>Document</title>
</head>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="index.js"></script>
</body>

</html>