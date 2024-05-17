<?php
// File header
/**
 * File: funciones.php
 * Description: Contains utility functions.
 */

class Funciones {
    /**
     * Function to validate DNI (Spanish identification number) with letter.
     *
     * @param string $dni_completo The complete DNI including letter.
     * @return bool True if the letter matches the calculated one, false otherwise.
     */
    public static function comprobarDniLetra($dni_completo) {
        // Extract only the numbers from the DNI
        $numeros_dni = substr($dni_completo, 0, -1);
        $letra_dni = strtoupper(substr($dni_completo, -1));
        // Calculate the letter of the DNI
        $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $indice = $numeros_dni % 23;
        $letra_calculada = $letras[$indice];

        // Compare the calculated DNI letter with the provided one
        return $letra_calculada === $letra_dni;
    }

    /**
     * Function to validate password strength.
     *
     * @param string $password The password to validate.
     * @return bool True if the password meets the criteria, false otherwise.
     */
    public static function validarContrasena($password) {
        $es_valida = true;
    
        // Password must have at least 8 characters
        if (strlen($password) < 8) {
            $es_valida = false;
        }
    
        // Password must contain at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $es_valida = false;
        }
    
        // Password must contain at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            $es_valida = false;
        }
    
        // Password must contain at least one numeric digit
        if (!preg_match('/[0-9]/', $password)) {
            $es_valida = false;
        }
    
        // Password must contain at least one special character
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $es_valida = false;
        }
    
        return $es_valida;
    }
}
?>
