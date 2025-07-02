<div class="modal fade text-xs-left" id="updateAsignacion" tabindex="-1" role="dialog" area-labelledby="modalAsignacionUpdate" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" onClick="cerrarModal();" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalAsignacionUpdate"><i class="icon-road2"></i>Actualizacion de Asignacion</h4>
			</div>
			<div class="modal-body">
				<div class="form-group"> 
				<input type="hidden" id="id_Asig">
                <div class="form-group">
					<label for="Empleado">Empleado</label>
					<div class="position-relative has-icon-left">
       					 <select  id="txtempleadoupdate" class="form-control selectpicker" data-live-search="true" title="Seleccione un Empleado" required></select>
       					 <div class="form-control-position"><i class="icon-office"></i></div>
    				</div>
				</div>
				<label for="placa">Placa</label>
					<div class="row"> 
						<div class="col-md-8 position-relative has-icon-left">
							<input type="number" id="txtplacaupdate" class="form-control" placeholder="Placa del equipo" autofocus required pattern="[0-9]*" inputmode="numeric"></input>
							<div class="form-control-position"><i class="icon-bag"></i></div>
						</div>
						<div class="col-md-4">
							<button type="button" onClick="BuscarAsignacion(document.getElementById('txtplacaupdate').value,'registrar');" class="btn btn-outline-primary">
								<i class="bi bi-search me-2"></i>
							</button>
						</div>
   					</div>
				<label for="descripcion">Descripcion</label>
				<div class="position-relative has-icon-left">
					<textarea rows="7" type="text" id="txtdescripcionupdate" class="form-control" placeholder="Descripcion del equipo" autofocus required readonly></textarea>
					<div class="form-control-position"><i class="icon-file2"></i></div>	
				</div>
				<div class="form-group">
					<label for="observaciones">Observaciones</label>
					<div class="position-relative has-icon-left">
						<textarea rows="7" type="text" id="txtobservacionesupdate" class="form-control" placeholder="Observaciones del equipo" autofocus required readonly></textarea>
						<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
				</div>	
					<div class="form-group">
					    <label for="Acta">Numero De Acta</label>
					    <div class="position-relative has-icon-left">
						    <input type="number" id="txtactaupdate" class="form-control" placeholder="Acta de Entrega" autofocus required pattern="[0-9]*" inputmode="numeric"></input>
						    <div class="form-control-position"><i class="icon-file2"></i></div>	
					    </div>
				    </div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal" onClick="cerrarModal();">Cerrar</button>
				<button id="btnActualizar" type="button" class="btn btn-outline-primary" onclick="ActualizarAsignacion();">Actualizar Informacion</button>
			</div>
		</div>
	</div>
</div>

