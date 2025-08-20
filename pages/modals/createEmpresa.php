<div class="modal fade text-xs-left" id="createEmpresa" tabindex="-1" role="dialog" area-labelledby="modalEmpresa" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" onClick="cerrarModal();" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalEmpresa"><i class="icon-road2"></i>Creacion de Empresa</h4>
			</div>
			<div class="modal-body">
				<div class="form-group"> 
                    <input type="hidden" id="id_Empresa">
                    <div class="form-group">
                    <div class="form-group">
                        <label for="Placa">Empresa</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="txtempresacrear" class="form-control" placeholder="Empresa" autofocus required></textarea>
                            <div class="form-control-position"><i class="icon-file2"></i></div>	
                        </div>
                    </div>
                    <label for="Acta">NIT</label>
                        <div class="row"> 
                            <div class="col-md-8 position-relative has-icon-left">
                                <input type="number" id="txtNITcrear" class="form-control" placeholder="NIT" autofocus required pattern="[0-9]*" inputmode="numeric"></input>
                                <div class="form-control-position"><i class="icon-bag"></i></div>
                            </div>
                            <div class="col-md-4">
                                <button type="button" onClick="AlertaBuscarEmpresa(document.getElementById('txtNITcrear').value,document.getElementById('txtNumeroContratoCrear').value,'registrar');" class="btn btn-outline-primary">
                                <i class="bi bi-search me-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <label for="Contrato">Numero de Contrato</label>
                        <div class="row"> 
                            <div class="col-md-8 position-relative has-icon-left">
                                <input type="text" id="txtNumeroContratoCrear" class="form-control" placeholder="Numero de Asignacion" autofocus required></input>
                                <div class="form-control-position"><i class="icon-bag"></i></div>
                            </div>
                        </div>
                    <label for="Placa">Fecha de Inicio</label>
                        <div class="position-relative has-icon-left">
                            <input type="date" id="txtFechaIcrear" class="form-control" placeholder="Fecha de Inicio del Contrato" autofocus required>
                        <div class="form-control-position"><i class="icon-file2"></i></div>
                        </div>
                    <label for="descripcion">Fecha Final</label>
                    <div class="position-relative has-icon-left">
                        <input type="date" id="txtFechaFcrear" class="form-control" placeholder="Fecha de Terminacion del Contrato" autofocus required>
                        <div class="form-control-position"><i class="icon-file2"></i></div>
                    </div>	
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal" onClick="cerrarModal();">Cerrar</button>
				<button id="btnGuardar" type="button" class="btn btn-outline-primary" onclick="RegistrarEmpresa();">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>
