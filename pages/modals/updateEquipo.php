<div class="modal fade text-xs-left" id="updateEquipo" tabindex="-1" role="dialog" area-labelledby="modalEquipos" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onClick="LimpiarModel();" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalEquipos"><i class="icon-road2"></i>Actualizar Equipo</h4>
			</div>
			<div class="modal-body">
                <input type="hidden" id="id_Equipupdate">
			<label for="placa">Placa</label>
				<div class="form-group">
					<div class="position-relative has-icon-left">
						<input type="text" id="txtplacaupdate" class="form-control" placeholder="Placa del equipo" autofocus required>
						<div class="form-control-position"><i class="icon-bag"></i></div>
					</div>
					<label for="serial">Serial</label>
					<div class="position-relative has-icon-left">
						<input type="text" id="txtserialupdate" class="form-control" placeholder="Serial del equipo" required>
						<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" onclick="ActualizarEquipo();" class="btn btn-outline-primary">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>

