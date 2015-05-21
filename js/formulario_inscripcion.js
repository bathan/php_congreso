$(document).ready(function() {

    $('#confirmacion').hide();

    var button_running = false;


    $('#btnRegister').click(function (e) {

        if(button_running==true) {
            return;
        }else{
            button_running = true;
        }

        $('#btnRegister').attr("disabled", true);

        //-- Obtener los datos del form
        var action = $('#action').val();
        var nombre = $('#nombre').val();
        var apellido = $('#apellido').val();
        var dni = $('#dni').val();
        var localidad = $('#localidad').val();
        var email = $('#email').val();
        var email_confirm = $('#email_confirm').val();
        var nivel = $('#nivel').val();
        var escuela = $('#escuela').val();

        //-- Validaciones iniciales
        var errores = [];

        if(nombre=='') { errores.push('Nombre'); }
        if(apellido=='') { errores.push('Apellido'); }
        if(dni=='') { errores.push('DNI'); }
        if(localidad=='') { errores.push('Localidad'); }
        if(email=='') { errores.push('Email'); }
        if(escuela=='') { errores.push('Escuela'); }
        if(nivel==0) {errores.push('Nivel');}


        if(errores.length > 0) {
            button_running = false;
            $('#btnRegister').attr("disabled", false);
            alert('Debe completar los siguientes campos: ' + errores.join(', '));
            return;
        }

        //-- Validación de Email y su confirmación
        if(!isValidEmail(email)) {
            button_running = false;
            $('#btnRegister').attr("disabled", false);
            alert('El email ingresado no es valido');
            return;
        }else{
            if(email != email_confirm) {
                button_running = false;
                $('#btnRegister').attr("disabled", false);
                alert('El email no fué confirmado correctamente');
                return;
            }
        }

        //-- Si llegamos a este momento es que todas las validaciones han sido correctas.
        var dataString = JSON.stringify({
                        nombre: nombre,
                        apellido: apellido,
                        dni: dni,
                        localidad: localidad,
                        email: email,
                        nivel:  nivel,
                        escuela:  escuela,
                        action: action
                    });

        var posting = $.post( "/form_actions/participantes/index.php", dataString );

        // Put the results in a div
        posting.done(function( data ) {
            var response = jQuery.parseJSON(data);

            if(response.status=='error') {
                alert(response.data);
            }

            if(response.status=='ok') {
                //-- Ocultar el form de Registro y mostrar el div de gracias
                $('#formulario_inscripcion').hide();
                $('#submit-button').hide();
                $('#contact-form').trigger("reset");
                $('#confirmacion').show();
                $("#btnRegister").attr("disabled", true);
            }
        });

        button_running = false;
    });

});

function isValidEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}