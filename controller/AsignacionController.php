<?php
require "../model/Asignacion.php";

$cat = new Asignacion();

switch($_REQUEST["operador"]){
    case "listar_Asignacion":
        $datos = $cat->listarAsignacion();
        if(is_array(value: $datos)){
            for($i=0;$i<count($datos);$i++){
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]['id_Asig'],
                    "Placa"=> $datos[$i]['Placa'],
                    "Empleado"=> $datos[$i]['Empleado'],
                    "Area"=> $datos[$i]['Area'],
                    "Acta"=> $datos[$i]['Acta'],
                    "Empresa"=> $datos[$i]['Empresa'],
                    "Fecha Ingreso"=> $datos[$i]['fecha_inicio'],
                    "Fecha Devolucion"=> $datos[$i]['fecha_fin']=="0000-00-00"?'<div class="tag tag-success">Vigente</div>':
                                                        $datos[$i]['fecha_fin'],
                    "Estado"=> $datos[$i]['estado']==1?'<div class="tag tag-success">Activo</div>':
                                                        '<div class="tag tag-danger">Inactivo</div>',
                    "op"=> ($datos[$i]['estado'])==1? '<div class="btn-group">
                    <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="icon-gear"></i>
                    </button>
                    <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="modal" data-target="#updateAsignacion"
                            onclick="BuscarAsignacion('.$datos[$i]['Placa'].",'editar'".');">
                            <i class="icon-pencil"></i>Editar</a>
                        <a class="dropdown-item" onclick="BuscarAsignacion('.$datos[$i]['Placa'].",'eliminar'".');">
                        <i class="icon-trash"></i> Eliminar</a>
                    </div>':'<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="BuscarAsignacionPorId('.$datos[$i]['id_Asig'].');"><i class="icon-check"></i> Activar</a>
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
    case "registrarAsignacion":
    
        if(isset($_POST["id_Empl"]) && !empty($_POST["id_Empl"]) && $_POST["descripcion"]
        && !empty($_POST["descripcion"]) && $_POST["observaciones"] && !empty($_POST["observaciones"]) 
        && $_POST["placa"] && !empty($_POST["placa"]) && $_POST["acta"] && !empty($_POST["acta"])){
            $placa=$_POST["placa"];
            $id_Empl=$_POST["id_Empl"];
        if($cat->Verificar($id_Empl,$placa)){
            //$id_Empl,$placa,$observaciones,$descripcion,$acta
            if($cat->RegistrarAsignacion($_POST["id_Empl"],$_POST["placa"],$_POST["observaciones"],
            $_POST["descripcion"],$_POST["acta"])){
                $response ="sucess";

            }else{
                $response ="required";
            }
        }else{
            $response ="registered";
        }
        echo $response;
        }
    
        break;
        case"LlenarSelectEmpleados":
            $data = $cat->LlenarSelectEmpleado();
            if($data){
                for($i=0;$i<count($data);$i++){
                $list[]=array(
                    "id_Empl"=>$data[$i]["id_Empl"],
                    "Empleado"=>$data[$i]['Empleado']
                    );
                    }
            echo json_encode($list);
            }else{
                $response = array(
                    "error"=>"error"
                );
            }
    break;
    case "buscarAsignacionId":
    if(isset($_POST["id_Asig"]) && !empty($_POST["id_Asig"])){
                $list = array();
                $data = $cat->BuscarId($_POST["id_Asig"]);
                if($data){
                        $list[] = array(
                            "id_Asig"=>$data["id_Asig"],
                            "placa"=>$data["Placa"],
                        );
                        echo json_encode($list);
                    }
                    }else{
                    $response = array(
                        "error"=>"error"
                    );
                }                
    break;
    case "buscarAsignacion":
        if(isset($_POST["placa"]) && !empty($_POST["placa"])){
                    $list = array();
                $data = $cat->buscarAsignacion($_POST["placa"]);
                    if($data && $data["Acta"]==null && $data["id_Asig"]==null){
                        $list[] = array(
                            "id_Asig"=>"",
                            "placa"=>$_POST["placa"],
                            "Empleado"=>"",
                            "observaciones"=>$data["observaciones"],
                            "descripcion"=>$data["descripcion"],
                            "Acta"=>""
                        );
                        echo json_encode($list);
                    }else{
                        $data = $cat->buscarAsignacion($_POST["placa"]);
                        if($data){
                        $list[] = array(
                            "id_Asig"=>$data["id_Asig"],
                            "placa"=>$_POST["placa"],
                            "Empleado"=>$data["Empleado"],
                            "observaciones"=>$data["observaciones"],
                            "descripcion"=>$data["descripcion"],
                            "Acta"=>$data["Acta"]
                        );
                        echo json_encode($list);
                    }else{
                      $list[] = array(
                            "id_Asig"=>"",
                            "placa"=>"",
                            "Empleado"=>"",
                            "observaciones"=>"",
                            "descripcion"=>"",
                            "Acta"=>""
                        );
                        echo json_encode($list);  
                    }
                    }
                    $response = array(
                        "error"=>"error"
                    );
                }
    break;
    case "Eliminar_Asignacion":
        if(isset($_POST["id_Asig"]) && !empty($_POST["id_Asig"])){
            if($cat->EliminarAsignacion($_POST["id_Asig"])){
                $response = "sucess";
            }else{
                $response = "required";
            }
        }else{
            $response = "required";
        }
        echo $response;
    break;
    case "Actualizar_Asignacion":
        if(isset($_POST["id_Asig"]) && !empty($_POST["id_Asig"]) && $_POST["descripcion"]
        && !empty($_POST["descripcion"]) && $_POST["observaciones"] && !empty($_POST["observaciones"]) 
        && $_POST["placa"] && !empty($_POST["placa"]) && $_POST["acta"] && !empty($_POST["acta"])){
            $placa=$_POST["placa"];
            $id_Empl=$_POST["id_Empl"];
                if($cat->ActualizarAsignacion($_POST["id_Asig"],$_POST["id_Empl"],$_POST["placa"],
                $_POST["observaciones"],$_POST["descripcion"],$_POST["acta"])){
                    $response ="sucess";
                }else{
                    $response ="error";
                }
        }else{
           $response ="required";
        }
        echo $response;
    break;
    case "Activar_Asignacion":
        if(isset($_POST["id_Asig"]) && !empty($_POST["id_Asig"])){
            if($cat->ActivarAsignacion($_POST["id_Asig"])){
                $response = "sucess";
            }else{
                $response = "required";
            }
        }else{
            $response = "required";
        }
        echo $response;
    break;
    case"LlenarSelectEmpleadosUpdate":
        $data = $cat->LlenarSelectEmpleadoUpdate();
        if($data){
            for($i=0;$i<count($data);$i++){
                $list[]=array(
                    "id_Empl"=>$data[$i]["id_Empl"],
                    "Empleado"=>$data[$i]['Empleado']
                );
            }
            echo json_encode($list);
        }else{
            $response = array(
                "error"=>"error"
            );
        }
    break;
}

?>