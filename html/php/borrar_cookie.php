<?php
// Establece el valor de la cookie a uno vacío
setcookie('cookie_session', '', time() - 3600, '/'); // Establece el tiempo de expiración en el pasado

// Responde con un estado HTTP 200 para indicar que la operación se realizó correctamente
http_response_code(200);
?>
