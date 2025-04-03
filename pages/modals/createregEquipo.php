<div class="modal fade text-xs-left" id="createEquipo" tabindex="-1" role="dialog" area-labelledby="modalEquipos" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalEquipos"><i class="icon-road2"></i>Nuevo Equipo</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="placa">Placa</label>
					<div class="position-relative has-icon-left">
					<input type="text" id="txtplaca" class="form-control" placeholder="Placa del equipo" required>
					<div class="form-control-position"><i class="icon-bag"></i></div>
					</div>
					<label for="serial">Serial</label>
					<div class="position-relative has-icon-left">
					<input type="text" id="txtserial" class="form-control" placeholder="Serial del equipo" autofocus required>
					<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
					<label for="descripcion">Descripcion</label>
					<div class="position-relative has-icon-left">
					<textarea rows="7" type="text" id="txtdescripcion" class="form-control" placeholder="Descripcion del equipo" autofocus required></textarea>
					<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
					<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<div class="position-relative has-icon-left">
					<textarea rows="7" type="text" id="txtobservaciones" class="form-control" placeholder="Observaciones del equipo" autofocus required></textarea>
					<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
					</div>
					<div class="form-group">
					<label for="accesorios">Accesorios</label>
					<div class="position-relative has-icon-left">
					<textarea type="text" id="txtaccesorios" class="form-control" placeholder="Accesorios del equipo" autofocus required></textarea>
					<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
					</div>
					<div class="form-group">
					<label for="empresa">Empresa</label>
					<div class="position-relative has-icon-left">
					<input type="text" id="txtempresa" class="form-control" placeholder="Empresa" autofocus required>
					<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
					</div>
					<div class="form-group">
					<label for="fecha">Fecha de Ingreso</label>
					<div class="position-relative has-icon-left">
					<input type="date" id="txtfecha" class="form-control" placeholder="Fecha de ingreso" autofocus required>
					<div class="form-control-position"><i class="icon-calendar"></i></div>	
					</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-outline-primary">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>