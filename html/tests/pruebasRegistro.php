<?php
use PHPUnit\Framework\TestCase;
require_once("/home/runner/work/red_pps/red_pps/html/php/conexion.php");
require_once("/home/runner/work/red_pps/red_pps/html/php/registro.php");
class PruebasRegistro extends TestCase
{
    public function testValidarContrasena()
    {
        // Pruebas para la función validar_contraseña
        
        // Caso: Contraseña válida
        $this->assertTrue(validar_contraseña("Contraseña1#"));
        
        // Caso: Contraseña demasiado corta
        $this->assertFalse(validar_contraseña("short1#"));
        
        // Caso: Contraseña sin mayúsculas
        $this->assertFalse(validar_contraseña("minuscula1#"));
        
        // Caso: Contraseña sin minúsculas
        $this->assertFalse(validar_contraseña("MAYUSCULA1#"));
        
        // Caso: Contraseña sin dígitos
        $this->assertFalse(validar_contraseña("SoloLetras#"));
        
        // Caso: Contraseña sin caracteres especiales
        $this->assertFalse(validar_contraseña("MissingSpecialChar1"));
    }
    
    public function testComprobarDniLetra()
    {
        // Pruebas para la función comprobar_dni_letra

        // Caso: DNI válido
        $this->assertTrue(comprobar_dni_letra("12345678Z"));

        // Caso: DNI con letra incorrecta
        $this->assertFalse(comprobar_dni_letra("12345678A"));

        // Caso: DNI con letra correcta
        $this->assertTrue(comprobar_dni_letra("87654321R"));
    }
}
?>