<?php
require_once '/home/runner/work/red_pps/red_pps/html/php/funciones.php';  // Ajusta la ruta al archivo funciones.php
use PHPUnit\Framework\TestCase;


class FuncionesTest extends TestCase {
    public function testComprobarDniLetra() {
        $tests = [
            '12345678Z' => true,   // Ejemplo de un DNI correcto
            '12345678A' => false,  // Ejemplo de un DNI incorrecto
            '87654321X' => true,   // Otro ejemplo de un DNI correcto
            '87654321B' => false,  // Otro ejemplo de un DNI incorrecto
        ];

        foreach ($tests as $dni => $expected) {
            $result = Funciones::comprobarDniLetra($dni);
            $this->assertEquals($expected, $result, "Failed asserting that $dni is " . ($expected ? 'valid' : 'invalid'));
        }
    }

    public function testValidarContrasena() {
        $tests = [
            'Password123!' => true,     // Contraseña válida
            'password123!' => false,    // Falta mayúscula
            'PASSWORD123!' => false,    // Falta minúscula
            'Password!'    => false,    // Falta dígito
            'Password123'  => false,    // Falta carácter especial
            'Passw1!'      => false,    // Menos de 8 caracteres
            'ValidPass123$'=> true,     // Otra contraseña válida
        ];

        foreach ($tests as $password => $expected) {
            $result = Funciones::validarContrasena($password);
            $this->assertEquals($expected, $result, "Failed asserting that '$password' is " . ($expected ? 'valid' : 'invalid'));
        }
    }
}
?>
