$(document).ready(function() {

 $('#btnRetrievePass').click(function (e) {

    $('#btnRetrievePass').attr("disabled", true);

    var email = $('#email').val();

    if(email=='') {
        $('#btnLogin').attr("disabled", false);
            alert('Debe completar el email');
            return;
    }

    //-- Si llegamos a este momento es que todas las validaciones han sido correctas.
    var dataString = JSON.stringify({
                    email: email,
                    action: 'retrieve_password'
                });

    var posting = $.post( "/form_actions/participantes/index.php", dataString );

    // Put the results in a div
    posting.done(function( data ) {
        var response = jQuery.parseJSON(data);

        if(response.status=='error') {
            alert('Se ha producido un error al enviar la contraseña. '+ response.data);
            $('#btnRetrievePass').attr("disabled", false);
        }

        if(response.status=='ok') {
            alert('La contraseña ha sido enviada con éxito');
            window.location = '/';
        }
    });

     $('#btnRetrievePass').attr("disabled", false);
 });

});
