<?php
require "../model/regEquipos.php";

$cat = new regEquipos();

switch($_REQUEST["operador"]){
    case "listar_regEquipos":
        try{
        $datos = $cat->ListarregEquipos();
        if(is_array(value: $datos)){
            // Inicializamos un array para almacenar los datos
            for($i=0;$i<count($datos);$i++){
                // Creamos un array asociativo para cada registro
                // y lo agregamos al array $list
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]["id_Reg"],
                    "Placa"=> $datos[$i]['placa'],
                    "Serial"=> $datos[$i]['serial'],
                    "Descripcion"=> $datos[$i]['descripcion'],
                    "Observaciones"=> $datos[$i]['observaciones'],
                    "Accesorios"=> $datos[$i]['accesorios'],
                    "Empresa"=> $datos[$i]['Empresa'],
                    "Fecha de Ingreso"=> $datos[$i]['fecha_creacion'],
                    "Fecha de Finalizacion"=> $datos[$i]['fecha_finalizacion']=="0000-00-00"?'<div class="tag tag-success">Vigente</div>':
                                                        $datos[$i]['fecha_finalizacion'],
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
            // Creamos el array de respuesta para DataTables
            // con los datos obtenidos
            $resultador = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($list),
                "iTotalDisplayRecords"=>count($list),
                "aaData"=>$list
            );
            } else{
                // Respuesta en caso de vacios
                $list[]=array(
                    "Numero de Registro"=>"",
                    "Placa"=> "",
                    "Serial"=> "",
                    "Descripcion"=> "",
                    "Observaciones"=> "",
                    "Accesorios"=> "",
                    "Empresa"=> "",
                    "Fecha de Ingreso"=> "",
                    "Fecha de Finalizacion"=> "",
                    "Estado"=> "",
                    "op"=> ""
                    );
                    $resultador = array(
                        "sEcho"=>1,
                        "iTotalRecords"=>count($list),
                        "iTotalDisplayRecords"=>count($list),
                        "aaData"=>$list
                    );
            }
        }catch(Exception $e){
            $list[]=array(
                "Numero de Registro"=>"ERROR",
                "Placa"=> "ERROR",
                "Serial"=> "ERROR",
                "Descripcion"=> "ERROR",
                "Observaciones"=> "ERROR",
                "Accesorios"=> "ERROR",
                "Empresa"=> "ERROR",
                "Fecha de Ingreso"=> "ERROR",
                "Fecha de Finalizacion"=> "ERROR",
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
        // Verificamos si se ha enviado el campo "placa" y no está vacío
    if(isset($_POST["placa"]) && !empty($_POST["placa"])){
        // Buscamos el equipo por placa
            $data = $cat->buscarEquipo($_POST["placa"]);
                if($data && $data["descripcion"]!=null){
                    // Si se encuentra el equipo, creamos un array con sus datos
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
                    // Si no se encuentra el equipo, buscamos por serial
                    // Buscamos el equipo por serial
                    // y creamos un array con los datos obtenidos
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
            case"LlenarSelectEmpresas":
                // Llenamos el select de empresas
                // con los datos obtenidos del modelo
                $data = $cat->LlenarSelectEmpresas();
                if($data){
                    for($i=0;$i<count($data);$i++){
                        // Creamos un array asociativo para cada empresa
                        // y lo agregamos al array $list
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
        // Verificamos si se han enviado los campos necesarios y no están vacíos
         // y si la placa no está registrada
         // si es así, registramos el equipo
         // y devolvemos una respuesta de éxito o error
        if(isset($_POST["placa"]) && !empty($_POST["placa"]) && $_POST["descripcion"]
        && !empty($_POST["descripcion"]) && $_POST["observaciones"] && !empty($_POST["observaciones"]) 
        && $_POST["accesorios"] && !empty($_POST["accesorios"]) && $_POST["empresa"] && !empty($_POST["empresa"])){
            $placa=$_POST["placa"];
        if($cat->Verificar($placa)){
            // Si la placa no está registrada, registramos el equipo
            // y devolvemos una respuesta de éxito
            // si no, devolvemos una respuesta de error
            // indicando que la placa ya está registrada
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
        // Verificamos si se han enviado los campos necesarios y no están vacíos
         // y si la placa no está registrada
         // si es así, actualizamos el equipo
         // y devolvemos una respuesta de éxito o error
        if(isset($_POST["id_Reg"]) && !empty($_POST["id_Reg"]) && isset($_POST["placa"]) && !empty($_POST["placa"]) && $_POST["descripcion"]
        && !empty($_POST["descripcion"]) && $_POST["observaciones"] && !empty($_POST["observaciones"]) 
        && $_POST["accesorios"] && !empty($_POST["accesorios"]) && $_POST["empresa"] && !empty($_POST["empresa"])){
            // Verificamos si la placa no está registrada
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
        // Verificamos si se ha enviado el campo "id_Reg" y no está vacío
        // si es así, eliminamos el registro del equipo
        // y devolvemos una respuesta de éxito o error
        if(isset($_POST["id_Reg"]) && !empty($_POST["id_Reg"])){
            // Llamamos al método eliminarRegistroEquipo del modelo
            // para eliminar el registro del equipo
            // y devolvemos una respuesta de éxito o error
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
        // Verificamos si se ha enviado el campo "id_Reg" y no está vacío
        // si es así, activamos el registro del equipo
        // y devolvemos una respuesta de éxito o error
         // si no, devolvemos una respuesta de error indicando que el campo es requerido
         // y no se puede activar el registro del equipo
        if(isset($_POST["id_Reg"]) && !empty($_POST["id_Reg"])){
            // Llamamos al método activarRegEquipo del modelo
            // para activar el registro del equipo
            // y devolvemos una respuesta de éxito o error
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