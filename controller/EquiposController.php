<?php
require "../model/Equipos.php";

$cat = new Equipos();

switch($_REQUEST["operador"]){
case "listar_categorias":
$datos = $cat->ListarEquipos();
if(is_array($datos)){
for($i=0;$i<count($datos);$i++){
$list[]=array(
"Numero de Registro"=>$datos[$i]["id_Equip"],
"Placa"=> $datos[$i]['placa'],
"Serial"=> $datos[$i]['serial'],
"Descripcion"=> $datos[$i]['descripcion'],
"Observaciones"=> $datos[$i]['observaciones'],
"Accesorios"=> $datos[$i]['accesorios'],
"Empresa"=> $datos[$i]['empresa'],
"Fecha de Ingreso"=> $datos[$i]['fecha_creacion'],
"Estado"=> $datos[$i]['estado']==1?'<div class="tag tag-success">Activo</div>':
                                    '<div class="tag tag-danger">Inactivo</div>',
"op"=> '<div class="btn-group">
<button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
  <i class="icon-gear"></i>
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="#"><i class="icon-pencil"></i> Editar</a>
    <a class="dropdown-item" href="#"><i class="icon-trash"></i> Eliminar</a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="#"><i class="icon-eye"></i> Ver</a>
</div>'
);
}
$resultador = array(
    "sEcho"=>1,
    "iTotalRecords"=>count($list),
    "iTotalDisplayRecords"=>count($list),
    "aaData"=>$list
);
}

echo json_encode($resultador);

break;
}



?>