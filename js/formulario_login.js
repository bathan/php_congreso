$(document).ready(function() {

 $('#btnLogin').click(function (e) {

    $('#btnLogin').attr("disabled", true);

    var email = $('#email').val();
    var password = $('#password').val();
    var errores = [];

    if(email=='') { errores.push('Email'); }
    if(password=='') { errores.push('Contraseña'); }

    if(errores.length > 0) {
        $('#btnLogin').attr("disabled", false);
        alert('Debe completar los siguientes campos: ' + errores.join(', '));
        return;
    }


    //-- Si llegamos a este momento es que todas las validaciones han sido correctas.
    var dataString = JSON.stringify({
                    email: email,
                    password:  password,
                    action: 'login'
                });

    var posting = $.post( "/form_actions/participantes/index.php", dataString );

    // Put the results in a div
    posting.done(function( data ) {
        var response = jQuery.parseJSON(data);

        if(response.status=='error') {
            alert(response.data);
        }

        if(response.status=='ok') {
            window.location = '/inicio.php';
            $("#btnLogin").attr("disabled", true);
        }
    });

     $('#btnLogin').attr("disabled", false);
 });

});
