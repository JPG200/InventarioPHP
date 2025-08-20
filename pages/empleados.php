<!-- ============== | head | =================-->
<?php  
session_start();
if(isset($_SESSION["user"])){
	include "layouts/head.php";  
  ?>
<!--==========================================-->

<!-- =========== | contenido | ===============-->
<div class="app-content content container-fluid">
	<div class="content-wrapper">
		<div class="content-header row">
			<div class="content-header-left col-md-6 col-xs-12 mb-1">
				<h2 class="content-header-title">Empleados Registrados</h2>
			</div>
			<div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
				<div class="breadcrumb-wrapper col-xs-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="../main">Dashboard</a></li>
						<li class="breadcrumb-item"><a >Productos</a></li>
						<li class="breadcrumb-item active"><a href="../pages/categorias.php">Registro de Equipos</a></li>
					</ol>
				</div>
			</div>
		</div>
		<div class="content-body">
			<section id="basic-form-layouts">
				<div class="row match-height">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title" id="basic-layout-form">
									<button class="btn btn-sm btn-success" data-target="#createEmpleado" data-toggle="modal" aria-expanded="false" aria-controls="createEmpleado">
										Registrar Empleado</button>
								</h4>
								<a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
								<div class="heading-elements">
									<ul class="list-inline mb-0">
										<li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
										<li><a data-action="expand"><i class="icon-expand2"></i></a></li>
									</ul>
								</div>
							</div>
							<div class="card-body collapse in">
								<div class="card-block">
									<div class="table-responsive">
										<table id="Tabla_Empleados" class="table table-bordered table-sm">
											<thead>
												<tr>
													<th >Numero de Registro</th>
                                                    <th >Cedula</th>
													<th >Nombre</th>
													<th >Apellido</th>
													<th >Email</th>
													<th >Area</th>
													<th >Estado</th>
													<th >op</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
											<tfoot>
												<tr>
												</tr>
											</tfoot>
										</table>
                    				</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
<!--=============MODAL====================-->
<?php 
?>
<!--==========================================-->

<!-- ========= | scripts robust | ============-->
<?php  include "layouts/main_scripts.php";
include 'modals/createEmpleado.php';
include 'modals/updateEmpleado.php';
 ?>
<!--==========================================-->
<script src="../app-assests/plugins/DataTables/datatables.min.js" type="text/javascript"></script>
<script src="../app-assests/plugins/DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="../assets/js/Empleados.js" type="text/javascript"></script>
<script src="../app-assests/plugins/sweetalert2/dist/sweetalert2.all.min.js" type="text/javascript"></script>
<script src="../app-assests/plugins/toastr/toastr.min.js" type="text/javascript"></script>

<!-- ============= | footer | ================-->
<?php  include "layouts/footer.php";      }
else{
	header(header: "Location: ../");
}?>
<!--==========================================-->