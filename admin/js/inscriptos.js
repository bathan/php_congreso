$(document).ready(function() {


    $('#theform').keypress(function(event){

        if (event.keyCode == 10 || event.keyCode == 13) {
            event.preventDefault();
            doMagicSearch();

        }


      });

    $('#magic_field_link').click(function (e) {
        doMagicSearch();
    });


    function doMagicSearch() {
        var magic_field = $('#filtro_magic').val();
            var uri = cleanUri();

            var qs = updateQueryStringParameter(uri, 'filtro_magic', magic_field);
            document.location.href = qs;
    }

});