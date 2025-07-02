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
					<input type="number" id="txtplaca" class="form-control" placeholder="Placa del equipo" autofocus required pattern="[0-9]*" inputmode="numeric">
					<label for="serial">Serial</label>
					<input type="text" id="txtserial" class="form-control" placeholder="Serial del equipo" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" onClick="LimpiarModel();" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-outline-primary" onclick="RegistrarEquipo();">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>