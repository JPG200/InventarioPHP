<div class="modal fade text-xs-left" id="createArea" tabindex="-1" role="dialog" area-labelledby="modalArea" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" onClick="LimpiarModel();" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalArea"><i class="icon-road2"></i>Nueva Area</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="CentroCostos">Centro de Costos</label>
					<input type="text" id="txtCentroCostos" class="form-control" placeholder="Centro de Costos" required>
					<label for="Area">Area</label>
					<input type="text" id="txtArea" class="form-control" placeholder="Area" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" onClick="LimpiarModel();" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-outline-primary" onclick="RegistrarArea();">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>