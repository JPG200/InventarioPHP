<?php
require "../model/Devolucion.php";

$cat = new Devolucion();

/*BuscarAsignacion('.$datos[$i]['Placa'].",'editar'".');
BuscarAsignacion('.$datos[$i]['Placa'].",'eliminar'".');
BuscarAsignacionPorId('.$datos[$i]['id_Asig'].');
*/

switch($_REQUEST["operador"]){
    case "listar_Devolucion":
        $datos = $cat->listarDevolucion();
        if(is_array(value: $datos)){
            for($i=0;$i<count($datos);$i++){
            /* Numero de Registro, Acta de Asignacion, Empleado, Placa, Empresa, Fecha de Devolucion, Fecha de Entrega, Acta de Devolucion, Estado, op*/
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]['id_Dev'],
                    "Acta"=> $datos[$i]['Acta'],
                    "Empleado"=> $datos[$i]['Empleado'],
                    "Placa"=> $datos[$i]['Placa'],
                    "Empresa"=> $datos[$i]['Empresa'],
                    "Acta Devolucion"=> $datos[$i]['Acta Devolucion'],
                    "Fecha de Entrega"=> $datos[$i]['fecha_fin']=="0000-00-00"?'<div class="tag tag-success">Vigente</div>':
                                                        $datos[$i]['fecha_fin'],
                    "Fecha de Devolucion"=> $datos[$i]['fecha_retorno']=="0000-00-00"?'<div class="tag tag-danger">Sin Entrega</div>':
                                                        $datos[$i]['fecha_retorno'],
                    "Estado"=> $datos[$i]['estado']==1?'<div class="tag tag-danger">No Entregado</div>':
                                                        '<div class="tag tag-success">Entregado</div>',
                    "op"=> ($datos[$i]['estado'])==1? '<div class="btn-group">
                    <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="icon-gear"></i>
                    </button>
                    <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="modal" data-target=""
                            onclick="">
                            <i class="icon-pencil"></i>Editar</a>
                        <a class="dropdown-item" onclick="">
                        <i class="icon-trash"></i> Eliminar</a>
                    </div>':'<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick=""><i class="icon-check"></i> Activar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>'
                    /*($datos[$i]['estado'])==1?'<div class="btn-group">
                    <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="icon-gear"></i>
                    </button>
                    <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="modal" data-target="#createregEquipo"
                            onclick="BuscarEquipo('.$datos[$i]['placa'].",'editar'".');">
                            <i class="icon-pencil"></i> Editar</a>
                        <a class="dropdown-item" onclick="BuscarEquipo('.$datos[$i]['placa'].",'eliminar'".');">
                        <i class="icon-trash"></i> Eliminar</a>
                    </div>':'
                            <div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="BuscarEquipo('.$datos[$i]['placa'].",'activar'".');"><i class="icon-check"></i> Activar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>'*/
                );
            }
            $resultador = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($list),
                "iTotalDisplayRecords"=>count($list),
                "aaData"=>$list
            );
        }else{
            $datos = array(
                "Numero de Registro"=>"ERROR",
                "Placa"=> "ERROR",
                "Empleado"=> "ERROR",
                "Area"=> "ERROR",
                "Acta"=> "ERROR",
                "Empresa"=> "ERROR",
                "Fecha Ingreso"=> "ERROR",
                "Fecha Devolucion"=> "ERROR",
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
    case "registrar_Devolucion":
        try{
            if(isset($_POST['acta_asig']) && isset($_POST['observaciones']) && isset($_POST['acta']) && 
            !empty($_POST['acta_asig']) && !empty($_POST['observaciones']) && !empty($_POST['acta'])){
            $acta_asig = $_POST['acta_asig'];
            $observaciones = $_POST['observaciones'];
            $acta = $_POST['acta'];
            
            if($cat->RegistrarDevolucion($acta_asig, $observaciones, $acta)){
                $response="success";
            }else{
                $response="error";
            }
            }else{
                $response="required";
            }
        }catch(Exception $e){
            $response = "error";
        }
            echo $response;
    break;
    case "buscarDevolucion":
        if(isset($_POST["acta"]) && !empty($_POST["acta"])){
                    $list = array();
                $data = $cat->BuscarInformacion($_POST["acta"]);
                    if($data && $data["observaciones"]==null && $data["ActaDev"]==null){
                        $list[] = array(
                            "id_Dev"=>"",
                            "placa"=>$_POST["placa"],
                            "observaciones"=>"",
                            "descripcion"=>$data["descripcion"],
                            "ActaAsig"=>$_POST["ActaAsig"],
                            "ActaDev"=>""
                    );
                        echo json_encode($list);
                    }else{
                        $data = $cat->BuscarInformacion($_POST["acta_asig"]);
                        if($data){
                        $list[] = array(
                            "id_Dev"=>$_POST["id_Dev"],
                            "placa"=>$_POST["placa"],
                            "observaciones"=>$_POST["observaciones"],
                            "descripcion"=>$data["descripcion"],
                            "ActaAsig"=>$_POST["ActaAsig"],
                            "ActaDev"=>$_POST["ActaDev"],
                        );
                        echo json_encode($list);
                    }else{
                      $list[] = array(
                            "id_Dev"=>"",
                            "placa"=>"",
                            "observaciones"=>"",
                            "descripcion"=>"",
                            "ActaAsig"=>"",
                            "ActaDev"=>""
                        );
                        echo json_encode($list);  
                    }
                    }
                    $response = array(
                        "error"=>"error"
                    );
                }
    break;
}
?>