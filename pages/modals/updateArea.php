<div class="modal fade text-xs-left" id="updateArea" tabindex="-1" role="dialog" area-labelledby="modalArea" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onClick="LimpiarModel();" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalArea"><i class="icon-road2"></i>Actualizar Area</h4>
			</div>
			<div class="modal-body">
                <input type="hidden" id="id_Areaupdate">
			<label for="Area">Area</label>
				<div class="form-group">
					<div class="position-relative has-icon-left">
						<input type="text" id="txtAreaupdate" class="form-control" placeholder="Area del equipo" autofocus required>
						<div class="form-control-position"><i class="icon-bag"></i></div>
					</div>
					<label for="centro_costos">Centro de Costos</label>
					<div class="position-relative has-icon-left">
						<input type="text" id="txtcentrocostosupdate" class="form-control" placeholder="Centro de Costos" required>
						<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" onClick="LimpiarModel();" data-dismiss="modal">Cerrar</button>
				<button type="button" onclick="ActualizarArea();" class="btn btn-outline-primary">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>
