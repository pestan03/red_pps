<?php
use PHPUnit\Framework\TestCase;

require_once('/home/runner/work/red_pps/red_pps/html/php/funciones.php'); // Asegúrate de que esta ruta es correcta y apunta al archivo que contiene la clase Funciones

class PruebasRegistro extends TestCase
{
    public function testValidarContrasena()
    {
        // Pruebas para la función validar_contraseña
        
        // Caso: Contraseña válida
        $this->assertTrue(Funciones::validar_contrasena("Contraseña1#"));
        
        // Caso: Contraseña demasiado corta
        $this->assertFalse(Funciones::validar_contrasena("short1#"));
        
        // Caso: Contraseña sin mayúsculas
        $this->assertFalse(Funciones::validar_contrasena("minuscula1#"));
        
        // Caso: Contraseña sin minúsculas
        $this->assertFalse(Funciones::validar_contrasena("MAYUSCULA1#"));
        
        // Caso: Contraseña sin dígitos
        $this->assertFalse(Funciones::validar_contrasena("SoloLetras#"));
        
        // Caso: Contraseña sin caracteres especiales
        $this->assertFalse(Funciones::validar_contrasena("MissingSpecialChar1"));
    }
    
    public function testComprobarDniLetra()
    {
        // Caso: DNI válido
        $this->assertTrue(Funciones::comprobar_dni_letra("12345678Z"));

        // Caso: DNI con letra incorrecta
        $this->assertFalse(Funciones::comprobar_dni_letra("12345678A"));

        // Caso: DNI con letra correcta
        $this->assertTrue(Funciones::comprobar_dni_letra("87654321R"));
    }
}
?>
