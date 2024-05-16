
<?php
class pruebasRegistro {
    // Función para comprobar la correspondencia DNI+letra
    public static function comprobar_dni_letra($dni_completo) {
        // Extraer solo los números del DNI
        $numeros_dni = substr($dni_completo, 0, -1);
        $letra_dni = strtoupper(substr($dni_completo, -1));
        // Calcular la letra del DNI
        $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $indice = $numeros_dni % 23;
        $letra_calculada = $letras[$indice];

        // Comparar la letra del DNI calculada con la letra proporcionada
        return $letra_calculada === $letra_dni;
    }
    public static function validar_contrasena($password) {
        // La contraseña debe tener al menos 8 caracteres
        if (strlen($password) < 8) {
            return false;
        }

        // La contraseña debe contener al menos una letra mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // La contraseña debe contener al menos una letra minúscula
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // La contraseña debe contener al menos un dígito numérico
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // La contraseña debe contener al menos un carácter especial
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            return false;
        }

        return true;
    }
}
?>