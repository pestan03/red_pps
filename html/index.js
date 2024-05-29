document.addEventListener("DOMContentLoaded", function () {
    const overlayTrigger = document.querySelector('.overlay-trigger');
    const overlay = document.querySelector('.overlay');
    
    overlayTrigger.addEventListener('click', function () {
        overlay.classList.toggle('active');
    });

    overlay.querySelector('.close-button').addEventListener('click', function () {
        overlay.classList.remove('active');
    });
});

function borrarCookie() {
    // Establecer la fecha de expiración en el pasado
    document.cookie = 'cookie_session=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    // Recargar la página para reflejar los cambios
    console.log("se ha llegado a borrarcookie");
    window.location.reload();
}
window.addEventListener('keydown', function (event) {
    // Verificar si se presionó la tecla R y si al mismo tiempo se presionó Ctrl (o Cmd en Mac)
    if ((event.key === 'r' || event.key === 'R') && (event.ctrlKey || event.metaKey)) {
        // Ejecutar la función para borrar la cookie
        borrarCookie();
    }
});
// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {
    // Selecciona todos los elementos con la clase 'mensaje-texto'
    const elementosMensajeTexto = document.querySelectorAll('.mensaje .mensaje-texto');

    // Agrega un controlador de eventos clic a cada elemento con la clase 'mensaje-texto'
    elementosMensajeTexto.forEach(elemento => {
        elemento.addEventListener('click', function () {
            // Obtiene el valor de 'data-message-id' del elemento padre
            const noticiaId = this.parentElement.getAttribute('data-noticia-id');

            // Guarda el valor en sessionStorage
            sessionStorage.setItem('noticiaId', noticiaId);

            // Redirige a la página deseada
            // Aquí puedes cambiar 'vermensaje.php' por la página a la que deseas redirigir
            window.location.href = '/vernoticia.php?noticiaId=' + noticiaId;
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // Selecciona todos los elementos con la clase 'mensaje-texto'
    const elementosMensajeTexto = document.querySelectorAll('.mensaje .nombre-usuario');

    // Agrega un controlador de eventos clic a cada elemento con la clase 'mensaje-texto'
    elementosMensajeTexto.forEach(elemento => {
        elemento.addEventListener('click', function () {
            // Obtiene el valor de 'data-message-id' del elemento padre
            const messageId = this.parentElement.getAttribute('data-noticia-id');

            // Guarda el valor en sessionStorage
            sessionStorage.setItem('noticiaId', noticiaId);

            // Redirige a la página deseada
            // Aquí puedes cambiar 'vermensaje.php' por la página a la que deseas redirigir
            window.location.href = '/perfil.php?noticiaId=' + noticiaId;
        });
    });
});

// MUESTRA DESPLEGABLE DE PERFIL Y LOGOUT
function showDropdown() {
    let dropdown = document.getElementById("myDropdown");
    dropdown.classList.add("show");
}

function hideDropdown() {
    let dropdown = document.getElementById("myDropdown");
    dropdown.classList.remove("show");
}




//FUNCION DE VALIDACION DE PASSWORD
function validarPassword() {
    const password = document.getElementById("pass").value;
    const passError = document.getElementById("passError");
    const submitBtn = document.getElementById("submitBtn");

    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (password.match(regex)) {
        passError.textContent = "";
        submitBtn.disabled = false;
    } else {
        passError.textContent = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.";
        submitBtn.disabled = true;
    }
}



// Obtén la referencia a la imagen
function selectImage() {
    const fileInput = document.getElementById('imagen');
    fileInput.click();
}



//BUSCADOR
$(document).ready(function () {
    let typingTimer; // letiable para almacenar el temporizador
    let doneTypingInterval = 500; // Tiempo de espera en milisegundos (500 ms)

    // Evento al soltar una tecla en el campo de búsqueda
    $('input[name="q"]').on('keyup', function () {
        clearTimeout(typingTimer); // Limpiar el temporizador
        let q = $(this).val(); // Obtener el valor del campo de búsqueda
        // Empezar el temporizador para esperar un poco antes de enviar la solicitud
        typingTimer = setTimeout(function () {
            // Realizar la solicitud al servidor
            $.ajax({
                url: './php/buscar.php',
                type: 'GET',
                data: { q: q },
                success: function (response) {
                    // Mostrar los resultados de búsqueda en el div de resultados
                    $('#resultados-busqueda').html(response);
                }
            });
        }, doneTypingInterval); // Esperar doneTypingInterval milisegundos antes de enviar la solicitud
    });

    //Evento cuando el campo de búsqueda pierde el foco
    $('input[name="q"]').on('blur', function () {
        // Ocultar el div de resultados
        $('#resultados-busqueda').hide();
    });

    // Evento cuando el campo de búsqueda obtiene el foco
    $('input[name="q"]').on('focus', function () {
        // Mostrar el div de resultados
        $('#resultados-busqueda').show();
    });
});


//superposicion resultados busqueda
document.getElementById('search-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Evitar que el formulario se envíe

    let formData = new FormData(this);

    fetch('./php/buscar.php', {
        method: 'GET',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            let resultadosBusqueda = document.getElementById('resultados-busqueda');
            resultadosBusqueda.innerHTML = data;
            resultadosBusqueda.style.display = data.trim() !== '' ? 'block' : 'none'; // Mostrar resultados si hay datos, ocultar si no
        })
        .catch(error => {
            console.error('Error:', error);
        });
});


// FUNCION FILTRADO MENSAJES POR USUARIO
function redirectToIndex(userId) {
    // Actualizar la URL en la barra de direcciones
    let newUrl = 'index.php?idBusqueda=' + userId;
    history.pushState(null, null, newUrl);

    // Cargar dinámicamente el contenido de la página utilizando AJAX
    // Aquí deberías hacer una solicitud AJAX para cargar el contenido de index.php con el nuevo parámetro
    // y luego actualizar el contenido de la página con la respuesta
    // Ejemplo de solicitud AJAX utilizando jQuery:
    $.ajax({
        url: newUrl,
        type: 'GET',
        success: function(response) {
            // Actualizar el contenido de la página con la respuesta de la solicitud AJAX
            document.body.innerHTML = response;
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}
