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
                    "Empresa"=> $datos[$i]['Empresa'],
                    "Fecha de Ingreso"=> $datos[$i]['fecha_creacion'],
                    "Estado"=> $datos[$i]['estado']==1?'<div class="tag tag-success">Activo</div>':
                                                        '<div class="tag tag-danger">Inactivo</div>',
                    "op"=> ($datos[$i]['estado'])==1?'<div class="btn-group">
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
                if($data && $data["descripcion"]!=null){
                    $list[] = array(
                        "id_Reg"=>$data["id_Reg"],
                        "placa"=>$data["placa"],
                        "serial"=>$data["serial"],
                        "descripcion"=>$data["descripcion"],
                        "observaciones"=>$data["observaciones"],
                        "accesorios"=>$data["accesorios"],
                        "empresa"=>$data["Empresa"]
                    );
                    echo json_encode($list);
                }else{
                    $data = $cat->buscarSerial($_POST["placa"]);
                    $list[] = array(
                        "id_Reg"=>"",
                        "placa"=>$data["placa"],
                        "serial"=>$data["serial"],
                        "descripcion"=>"",
                        "observaciones"=>"",
                        "accesorios"=>"",
                        "empresa"=>""
                    );
                    echo json_encode($list);
                }
                $response = array(
                    "error"=>"error"
                );
            }
            break;
           /* case "confirmarInformacion":

            break;
*/
            case"LlenarSelectEmpresas":
                $data = $cat->LlenarSelectEmpresas();
                if($data){
                    for($i=0;$i<count($data);$i++){
                        $list[]=array(
                            "id_Empresa"=>$data[$i]["id_Empresa"],
                            "Empresa"=>$data[$i]['Empresa']
                        );
                    }
                    echo json_encode($list);
                }else{
                    $response = array(
                        "error"=>"error"
                    );
                }
                break;
    case "registrarEquipo":
        if(isset($_POST["placa"]) && !empty($_POST["placa"]) && $_POST["descripcion"]
        && !empty($_POST["descripcion"]) && $_POST["observaciones"] && !empty($_POST["observaciones"]) 
        && $_POST["accesorios"] && !empty($_POST["accesorios"]) && $_POST["empresa"] && !empty($_POST["empresa"])){
            $placa=$_POST["placa"];
        if($cat->Verificar($placa)){
            if($cat->RegistrarRegistroEquipo($_POST["placa"],$_POST["descripcion"],$_POST["observaciones"],
            $_POST["accesorios"],$_POST["empresa"])){
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
        

    case "editarEquipo":
        if(isset($_POST["id_Reg"]) && !empty($_POST["id_Reg"]) && isset($_POST["placa"]) && !empty($_POST["placa"]) && $_POST["descripcion"]
        && !empty($_POST["descripcion"]) && $_POST["observaciones"] && !empty($_POST["observaciones"]) 
        && $_POST["accesorios"] && !empty($_POST["accesorios"]) && $_POST["empresa"] && !empty($_POST["empresa"])){
            if($cat->ActualizarRegistroEquipo($_POST["id_Reg"],$_POST["placa"],$_POST["descripcion"],$_POST["observaciones"],
            $_POST["accesorios"],$_POST["empresa"])){
                $response = "sucess";
            }else{
                $response = "error";
            }
        }else{
            $response = "required";
        }
        echo $response;
        break;

    
    case "eliminarEquipo":
        if(isset($_POST["id_Reg"]) && !empty($_POST["id_Reg"])){
            $data = $cat->eliminarRegistroEquipo($_POST["id_Reg"]);
            if($data){
                $response = "sucess";

            }else{
                $response = "error";

            }
        }else{
            $response = "required";

        }
        echo $response;
        break;

    case "activarRegEquipo":
        if(isset($_POST["id_Reg"]) && !empty($_POST["id_Reg"])){
            try{
            $data = $cat->activarRegEquipo($_POST["id_Reg"]);
            if($data){
                $response = "sucess";
            }else{
                $response = "error";
            }
        }catch(ErrorException $ex){
            $response = $ex;
        }
        }else{
            $response = "required";

        }
        echo $response;
        break;

}



?>