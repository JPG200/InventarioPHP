<div class="modal fade text-xs-left" id="createDevolucion" tabindex="-1" role="dialog" area-labelledby="modalDevolucion" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" onClick="cerrarModal();" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalDevolucion"><i class="icon-road2"></i>Devolucion de Equipo</h4>
			</div>
			<div class="modal-body">
				<div class="form-group"> 
				<input type="hidden" id="id_Dev">
                <div class="form-group">
				<label for="Acta">Numero de Acta</label>
					<div class="row"> 
						<div class="col-md-8 position-relative has-icon-left">
							<input type="number" id="txtactacrear" class="form-control" placeholder="Numero de Acta" autofocus required pattern="[0-9]*" inputmode="numeric"></input>
							<div class="form-control-position"><i class="icon-bag"></i></div>
						</div>
   					</div>
				</div>
				<label for="Asig">Acta de Asignacion</label>
					<div class="row"> 
						<div class="col-md-8 position-relative has-icon-left">
							<input type="number" id="txtasigcrear" class="form-control" placeholder="Numero de Asignacion" autofocus required pattern="[0-9]*" inputmode="numeric"></input>
							<div class="form-control-position"><i class="icon-bag"></i></div>
						</div>
						<div class="col-md-4">
							<button type="button" onClick="BuscarInformacionActaAsignacion(document.getElementById('txtasigcrear').value,'registrar');" class="btn btn-outline-primary">
								<i class="bi bi-search me-2"></i>
							</button>
						</div>
   					</div>
					<div class="form-group">
					    <label for="Placa">Placa</label>
					    <div class="position-relative has-icon-left">
						    <input type="text" id="txtplacacrear" class="form-control" placeholder="Placa del Equipo" autofocus required readonly></textarea>
						    <div class="form-control-position"><i class="icon-file2"></i></div>	
					    </div>
				    </div>
				<label for="descripcion">Descripcion</label>
				<div class="position-relative has-icon-left">
					<textarea rows="7" type="text" id="txtdescripcioncrear" class="form-control" placeholder="Descripcion del equipo" autofocus required readonly></textarea>
					<div class="form-control-position"><i class="icon-file2"></i></div>	
				</div>
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<div class="position-relative has-icon-left">
						<textarea rows="7" type="text" id="txtobservacionescrear" class="form-control" placeholder="Observaciones del equipo" autofocus required></textarea>
						<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
				</div>	
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal" onClick="cerrarModal();">Cerrar</button>
				<button id="btnGuardar" type="button" class="btn btn-outline-primary" onclick="RegistrarDevolucion();">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>
