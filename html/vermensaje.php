<?php
// Verifica si el messageId está presente en sessionStorage
    // Verifica si messageId está presente en la URL
    if (isset($_GET['messageId'])) {
        // Recoge el valor de messageId desde la URL
        $messageId = $_GET['messageId'];
        echo '<p>' . $messageId .'</p>';
        // Ahora puedes usar $messageId en tu código PHP como lo necesites
        echo "El valor de messageId es: " . $messageId;
    } else {
        // Si messageId no está presente en la URL
        echo "No se proporcionó ningún messageId en la URL.";
    }


    // Obtiene el messageId almacenado en sessionStorage
    // Conexión a la base de datos (modifica estos valores según tu configuración)
    $servername = "db";
    $username = "root";
    $password = "root_password";
    $database = "red";

    // Crear conexión
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

    // Prepara la consulta SQL para seleccionar el mensaje con el messageId especificado
    $sql = "SELECT * FROM mensajes WHERE message_id = :messageId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':messageId', $messageId);

    // Ejecuta la consulta
    $stmt->execute();

    // Verifica si se encontró un mensaje con el messageId especificado
    if ($stmt->rowCount() > 0) {
        // Recupera los datos del mensaje
        $mensaje = $stmt->fetch(PDO::FETCH_ASSOC);

        // Imprime los datos del mensaje
        echo "ID del Mensaje: " . $mensaje['message_id'] . "<br>";
        echo "ID del Usuario: " . $mensaje['user_id'] . "<br>";
        echo "Mensaje: " . $mensaje['message'] . "<br>";
        echo "Fecha de Envío: " . $mensaje['datesent'] . "<br>";
        
        // Puedes continuar imprimiendo otros datos del mensaje si los hay
    } else {
        echo "No se encontró ningún mensaje con el ID especificado.";
    }

?>
<script src="index.js"></script>