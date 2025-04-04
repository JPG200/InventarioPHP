<?php
require "../model/regEquipos.php";

$cat = new regEquipos();

switch($_REQUEST["operador"]){
case "listar_regEquipos":
$datos = $cat->ListarregEquipos();
if(is_array(value: $datos)){
for($i=0;$i<count($datos);$i++){
$list[]=array(
"Numero de Registro"=>$datos[$i]["id_Reg"],
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
} else{
    $list[]=array(
        "Numero de Registro"=>"ERROR",
        "Placa"=> "ERROR",
        "Serial"=> "ERROR",
        "Descripcion"=> "ERROR",
        "Observaciones"=> "ERROR",
        "Accesorios"=> "ERROR",
        "Empresa"=> "ERROR",
        "Fecha de Ingreso"=> "ERROR",
        "Estado"=> "ERROR",
        "op"=> "ERROR"
        );
        $resultador = array(
            "sEcho"=>1,
            "iTotalRecords"=>count($list),
            "iTotalDisplayRecords"=>count($list),
            "aaData"=>$list
        );
 }

echo json_encode($resultador);

break;

case "buscarEquipo":

if(isset($_POST["placa"]) && !empty($_POST["placa"])){
        $data = $cat->buscarEquipo($_POST["placa"]);
            if($data){
                $list[] = array(
                    "placa"=>$data["placa"],
                    "serial"=>$data["serial"],
                    "descripcion"=>$data["descripcion"],
                    "observaciones"=>$data["observaciones"],
                    "accesorios"=>$data["accesorios"],
                    "empresa"=>$data["empresa"]
                );
                echo json_encode($list);
            }else{
                $response = array(
                    "error"=>"error"
                );
            }

        }
        break;

}
?>