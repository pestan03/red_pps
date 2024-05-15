<?php
use PHPUnit\Framework\TestCase;

// Incluir el archivo de conexión a la base de datos
include './conexion.php';

class InicioTest extends TestCase
{
    // Prueba para verificar si se muestra correctamente el formulario de búsqueda
    public function testFormularioDeBusqueda()
    {
        ob_start(); // Capturar la salida HTML
        include './index.php'; // Incluir el archivo que contiene el formulario de búsqueda
        $output = ob_get_clean(); // Obtener la salida HTML capturada
        
        // Verificar que la salida HTML contiene el formulario de búsqueda
        $this->assertStringContainsString('<form id="search-form" action="./php/buscar.php" method="GET">', $output);
    }

    // Prueba para verificar si se muestran correctamente las noticias cuando hay noticias disponibles
    public function testNoticiasDisponibles()
    {
        ob_start(); // Capturar la salida HTML
        include './index.php'; // Incluir el archivo que contiene las noticias
        $output = ob_get_clean(); // Obtener la salida HTML capturada
        
        // Verificar que la salida HTML contiene al menos una noticia
        $this->assertStringContainsString('<div class="mensaje"', $output);
    }

    // Prueba para verificar si se muestra correctamente el mensaje cuando no hay noticias disponibles
    public function testNoHayNoticias()
    {
        // Simular una situación donde no hay noticias disponibles
        $conn = $this->getMockBuilder(PDO::class)
                     ->disableOriginalConstructor()
                     ->getMock();
        $conn->method('query')
             ->willReturn(false);

        ob_start(); // Capturar la salida HTML
        include './index.php'; // Incluir el archivo que contiene el mensaje de ausencia de noticias
        $output = ob_get_clean(); // Obtener la salida HTML capturada
        
        // Verificar que la salida HTML contiene el mensaje de ausencia de noticias
        $this->assertStringContainsString('No hay noticias para mostrar.', $output);
    }

    // Prueba para verificar si se muestra correctamente el botón de publicar noticia
    public function testBotonPublicarNoticia()
    {
        ob_start(); // Capturar la salida HTML
        include './index.php'; // Incluir el archivo que contiene el botón de publicar noticia
        $output = ob_get_clean(); // Obtener la salida HTML capturada
        
        // Verificar que la salida HTML contiene el botón de publicar noticia
        $this->assertStringContainsString('<div class="overlay-trigger">', $output);
    }

    // Prueba para verificar si se muestra correctamente el nombre de usuario cuando está iniciada la sesión
    public function testNombreDeUsuarioInicioSesion()
    {
        // Simular una sesión iniciada
        $_COOKIE['cookie_session'] = 'valor_de_cookie_valido';

        ob_start(); // Capturar la salida HTML
        include './index.php'; // Incluir el archivo que contiene el nombre de usuario
        $output = ob_get_clean(); // Obtener la salida HTML capturada
        
        // Verificar que la salida HTML contiene el nombre de usuario
        $this->assertStringContainsString('nombre-usuario', $output);
    }

    // Prueba para verificar si se muestra correctamente el mensaje de inicio de sesión cuando no está iniciada la sesión
    public function testMensajeInicioSesion()
    {
        // Simular una sesión no iniciada
        unset($_COOKIE['cookie_session']);

        ob_start(); // Capturar la salida HTML
        include './index.php'; // Incluir el archivo que contiene el mensaje de inicio de sesión
        $output = ob_get_clean(); // Obtener la salida HTML capturada
        
        // Verificar que la salida HTML contiene el mensaje de inicio de sesión
        $this->assertStringContainsString('<a href="iniciarsesion.html" class="botonInicio">', $output);
    }
}
