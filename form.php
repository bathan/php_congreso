<div class="boxed-grey">
    <form id="contact-form">
        <div class="row">
            <div class="col-md-12" id="formulario_inscripcion">
                <div class="form-group">
                    <label for="nombre">
                        Nombre
                    </label>
                    <input type="text" class="form-control" id="nombre" placeholder="Ingrese su nombre" required="required" />
                </div>
                <div class="form-group">
                    <label for="apellido">
                        Apellido
                    </label>
                    <input type="text" class="form-control" id="apellido" placeholder="Ingrese su apellido" required="required" />
                </div>
                <div class="form-group">
                    <label for="dni">
                        DNI
                    </label>
                    <input type="text" class="form-control" id="dni" placeholder="Ingrese su DNI" required="required" />
                </div>
                <div class="form-group">
                    <label for="localidad">
                        Localidad
                    </label>
                    <input type="text" class="form-control" id="localidad" placeholder="Ingrese su localidad" required="required" />
                </div>
                <div class="form-group">
                    <label for="email">
                        Dirección de Email
                    </label>
                    <div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
						</span>
                        <input type="email" class="form-control" id="email" placeholder="Ingrese su email" required="required" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email2">
                        Confirme su dirección de Email
                    </label>
                    <div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
						</span>
                        <input type="email" class="form-control" id="email_confirm" placeholder="Ingrese su email nuevamente" required="required" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="escuela">
                        Nombre de la escuela
                    </label>
                    <input type="text" class="form-control" id="escuela" placeholder="Ingrese su escuela" required="required" />
                </div>
                <div class="form-group">
                    <label for="nivel">
                        Nivel
                    </label>
                    <select id="nivel" name="nivel" class="form-control" required="required">
                        <option value="0" selected="">Elija uno:</option>
                        <option value="Primario">Primario</option>
                        <option value="secundario">Secundario</option>
                        <option value="estudiantes">Estudiantes</option>
                        <option value="otros">Otros</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12" id="submit-button">
                <button type="button" class="btn btn-skin pull-center" id="btnRegister">
                    Enviar Inscripción
                </button>
            </div>

            <!--Confirmacion de envio-->
            <div class="col-md-12" id="confirmacion" style="color: #000000">
                La inscripción se realizó exitosamente. Recibira un email de confirmación. Por favor guarde ese email para futura referencia. <br>
                ¡Gracias!
            </div>
            <!--Fin de confirmacion-->
        </div>
        <input type="hidden" id="action" name="action" value="register">
    </form>
</div>