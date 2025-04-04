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
				<h2 class="content-header-title">Equipos Registrados</h2>
			</div>
			<div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
				<div class="breadcrumb-wrapper col-xs-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="../main">Dashboard</a></li>
						<li class="breadcrumb-item"><a >Productos</a></li>
						<li class="breadcrumb-item active"><a href="#">Categorias</a></li>
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
									<button class="btn btn-sm btn-success" data-target="#createEquipo" data-toggle="modal" aria-expanded="false" aria-controls="createEquipo">
										Nueva categoria</button>
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
										<table id="Tabla_Equipos" class="table table-bordered table-sm">
											<thead>
												<tr>
													<th width="5%">Numero de Registro</th>
													<th width="5%">Placa</th>
													<th width="5%">Serial</th>
													<th width="25%">Descripcion</th>
													<th width="20%">Observaciones</th>
													<th width="10%">Accesorios</th>
													<th width="5%">Empresa</th>
													<th width="10%">Fecha de Ingreso</th>
													<th width="5%">Estado</th>
													<th width="5%">op</th>
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
include 'modals/createEquipo.php';
?>
<!--==========================================-->

<!-- ========= | scripts robust | ============-->
<?php  include "layouts/main_scripts.php"; ?>
<!--==========================================-->
<script src="../app-assests/plugins/DataTables/datatables.min.js" type="text/javascript"></script>
<script src="../app-assests/plugins/DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="../assets/js/Equipos.js" type="text/javascript"></script>
<!-- ============= | footer | ================-->
<?php  include "layouts/footer.php";      }
else{
	header(header: "Location: ../");
}?>
<!--==========================================-->