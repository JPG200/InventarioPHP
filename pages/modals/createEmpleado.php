<div class="modal fade text-xs-left" id="createEmpleado" tabindex="-1" role="dialog" area-labelledby="modalEmpleados" aria-hiden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" onClick="" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalEmpleados"><i class="icon-road2"></i>Empleado</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="cedula">Cedula</label>
                    <div class="row"> 
						<div class="col-md-8 position-relative has-icon-left">
                            <input type="text" id="txtcedula" class="form-control" placeholder="Cedula del empleado" required>
                            <div class="form-control-position"><i class="icon-bag"></i></div>
						</div>
						<div class="col-md-4">
							<button type="button" onClick="" class="btn btn-outline-primary"><i class="bi bi-search me-2"></i></button>
						</div>
   					</div>
					<label for="nombre">Nombre</label>
                    <div class="position-relative has-icon-left">
						<textarea rows="2" type="text" id="txtnombre" class="form-control" placeholder="Nombre del empleado" autofocus required></textarea>
						<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
                    <label for="apellido">Apellido</label>
                    <div class="position-relative has-icon-left">
						<textarea rows="2" type="text" id="txtapellido" class="form-control" placeholder="Apellido del empleado" autofocus required></textarea>
						<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
                    <label for="email">Email</label>
                    <div class="position-relative has-icon-left">
						<textarea rows="2" type="text" id="txtemail" class="form-control" placeholder="Email del empleado" autofocus required></textarea>
						<div class="form-control-position"><i class="icon-file2"></i></div>	
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-outline-primary" onclick="">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>