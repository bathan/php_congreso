<style type="text/css">
::-webkit-scrollbar
{
    width: 20px;
    height: 20px;
	/*background-color: #F5F5F5;*/
}

::-webkit-scrollbar-thumb
{
    height: 6px;
    border: 4px solid rgba(0, 0, 0, 0);
    background-clip: padding-box;
    -webkit-border-radius: 7px;
    background-color: rgba(0, 0, 0, 0.15);
    -webkit-box-shadow: inset -1px -1px 0px rgba(0, 0, 0, 0.05), inset 1px 1px 0px rgba(0, 0, 0, 0.05);
}

::-webkit-scrollbar-button
{
    width: 0;
    height: 0;
    display: none;
}

::-webkit-scrollbar-corner
{
    background-color: transparent;
}
</style>
<div class="boxed-grey">
<h2>Enviar tu trabajo</h2>
                <div class="row" id="formulario_inscripcion_div">
                <form enctype="multipart/form-data" id="contact-form" method="POST" action="form_actions/participantes/">

                  <div class="col-md-12" id="formulario_inscripcion">
                        <div class="form-group">
                            <label for="nombre">
                                Título</label>
                            <input type="text" class="form-control" id="titulo_trabajo" name="titulo_trabajo" placeholder="Ingrese un título" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="apellido">
                                Archivo</label>
                          <input type="file" name="theFile" id="theFile" />
                        </div>
                        <div class="form-group"></div>

                        
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-skin pull-center" id="btnUpload">
                      Enviar Trabajo</button>
                  </div>


                    <!--Fin de confirmacion-->
                    <input type="hidden" name="action" id="action" value="upload_trabajo" />
                    <input type="hidden" name="id_participante" id="id_participante" value="<?=$session_id_participante;?>" />
                </form>
                </div>
    <!--Confirmacion de envio-->
    <div id="confirmacion">
        Tu trabajo se envio exitosamente. Recibira un email de confirmación. Por favor guarde ese email para futura referencia. <br>
        ¡Gracias!
    </div>
            </div>

