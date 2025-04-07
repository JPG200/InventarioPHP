<div class="modal fade text-xs-left" id="createregEquipo" tabindex="-1" role="dialog" area-labelledby="modalEquipos" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" onClick="cerrarModal();" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalEquipos"><i class="icon-road2"></i>Registrar Nuevo Equipo</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="placa">Placa</label>
					<div class="row"> 
					<div class="col-md-8 position-relative has-icon-left">
						<input type="text" id="txtplacacrear" class="form-control" placeholder="Placa del equipo" autofocus required></input>
						<div class="form-control-position"><i class="icon-bag"></i></div>
        			</div>
        			<div class="col-md-4">
						<button type="button" onClick="BuscarEquipo();" class="btn btn-outline-primary"><i class="bi bi-search me-2"></i></button>
        			</div>
   					</div>
					<label for="serial">Serial</label>
					<div class="position-relative has-icon-left">
						<input type="text" id="txtserialcrear" class="form-control" placeholder="Serial del equipo" autofocus required readonly>
						<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
					<label for="descripcion">Descripcion</label>
					<div class="position-relative has-icon-left">
						<textarea rows="7" type="text" id="txtdescripcioncrear" class="form-control" placeholder="Descripcion del equipo" autofocus required></textarea>
						<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
					<div class="form-group">
						<label for="observaciones">Observaciones</label>
						<div class="position-relative has-icon-left">
							<textarea rows="7" type="text" id="txtobservacionescrear" class="form-control" placeholder="Observaciones del equipo" autofocus required></textarea>
							<div class="form-control-position"><i class="icon-file2"></i></div>	
						</div>
					</div>
					<div class="form-group">
						<label for="accesorios">Accesorios</label>
						<div class="position-relative has-icon-left">
							<textarea type="text" id="txtaccesorioscrear" class="form-control" placeholder="Accesorios del equipo" autofocus required></textarea>
							<div class="form-control-position"><i class="icon-file2"></i></div>	
						</div>
					</div>
					<div class="form-group">
						<label for="empresa">Empresa</label>
						<div class="position-relative has-icon-left">
       						 <select  id="txtempresacrear" class="form-control selectpicker" data-live-search="true" title="Seleccione una empresa" required></select>
       						 <div class="form-control-position"><i class="icon-office"></i></div>
    					</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal" onClick="cerrarModal();">Cerrar</button>
				<button type="button" class="btn btn-outline-primary" onclick="RegistrarEquipo();">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>


