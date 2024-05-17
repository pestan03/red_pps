<?php
include_once './conexion.php';

// Código PHP para eliminar la cookie de sesión
if (isset($_COOKIE['cookie_session'])) {
    setcookie('cookie_session', '', time() - 3600, '/');
    header("Location: ../index.php");
    exit;
} else {
    header("Location: ../index.php");
    exit;
}
?>
