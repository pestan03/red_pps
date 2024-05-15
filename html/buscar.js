$(document).ready(function() {
    // Delegar el evento clic desde el documento al contenedor más cercano de los enlaces a-bus
    $(document).on('click', '.bus', function(e) {
        e.preventDefault(); // Evitar que el enlace se comporte como un enlace normal
        var userId = $(this).data('user-id'); // Obtener el ID de usuario del atributo 'data-user-id'
        window.location.href = "index.php?idBusqueda=" + userId;
    });
});