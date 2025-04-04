<div class="modal fade text-xs-left" id="createEquipo" tabindex="-1" role="dialog" area-labelledby="modalEquipos" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" onClick="LimpiarModel();" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalEquipos"><i class="icon-road2"></i>Nuevo Equipo</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="placa">Placa</label>
					<input type="text" id="txtplaca" class="form-control" placeholder="Placa del equipo" required>
					<label for="serial">Serial</label>
					<input type="text" id="txtserial" class="form-control" placeholder="Serial del equipo" required>
					<label for="descripcion">Descripcion</label>
					<input type="text" id="txtdescripcion" class="form-control" placeholder="Descripcion del equipo" required>
					<label for="observaciones">Observaciones</label>
					<input type="text" id="txtobservaciones" class="form-control" placeholder="Observaciones del equipo" required>
					<label for="accesorios">Accesorios</label>
					<input type="text" id="txtaccesorios" class="form-control" placeholder="Accesorios del equipo" required>
					<label for="empresa">Empresa</label>
					<input type="text" id="txtempresa" class="form-control" placeholder="Empresa" required>
					<label for="fecha">Fecha de Ingreso</label>
					<input type="date" id="txtfecha" class="form-control" placeholder="Fecha de ingreso" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-outline-primary">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>