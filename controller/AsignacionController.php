<?php
require "../model/Asignacion.php";

$cat = new Asignacion();

switch($_REQUEST["operador"]){
    case "listar_Asignacion":
        $datos = $cat->listarAsignacion();
        if(is_array(value: $datos)){
            for($i=0;$i<count($datos);$i++){
                $list[]=array(
                    // Llenar el array con los datos de la asignación
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
                );
            }
            $resultador = array(
                // Estructura del resultado para el DataTable
                "sEcho"=>1,
                "iTotalRecords"=>count($list),
                "iTotalDisplayRecords"=>count($list),
                "aaData"=>$list
            );
        }else{
            $datos = array(
                // Si no hay datos, retornar un array con campos de error
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
                // Estructura del resultado para el DataTable
                        "sEcho"=>1,
                        "iTotalRecords"=>count($list),
                        "iTotalDisplayRecords"=>count($list),
                        "aaData"=>$list
                    );
        }

        echo json_encode($resultador);

    break;
    case "registrarAsignacion":
    // Validar los campos requeridos antes de registrar la asignación
        if(isset($_POST["id_Empl"]) && !empty($_POST["id_Empl"]) && $_POST["descripcion"]
        && !empty($_POST["descripcion"]) && $_POST["observaciones"] && !empty($_POST["observaciones"]) 
        && $_POST["placa"] && !empty($_POST["placa"]) && $_POST["acta"] && !empty($_POST["acta"])){
            // Obtener los datos del formulario
            $placa=$_POST["placa"];
            $id_Empl=$_POST["id_Empl"];
            // Verificar si la asignación ya existe
        if($cat->Verificar($id_Empl,$placa)){
            // Registrar la asignación
            if($cat->RegistrarAsignacion($_POST["id_Empl"],$_POST["placa"],$_POST["observaciones"],
            $_POST["descripcion"],$_POST["acta"])){
                // Si la asignación se registra correctamente, retornar éxito
                $response ="sucess";
            }else{
                // Si hay un error al registrar, retornar requerido
                $response ="required";
            }
        }else{
            // Si la asignación ya existe, retornar error
            $response ="registered";
        }
        // Retornar la respuesta
        echo $response;
        }
    
        break;
        case"LlenarSelectEmpleados":
            // Obtener la lista de empleados para llenar el select
            $data = $cat->LlenarSelectEmpleado();
            if($data){
                // Crear un array para almacenar los empleados
                for($i=0;$i<count($data);$i++){
                    // Llenar el array con los datos de los empleados
                    $list[]=array(
                    "id_Empl"=>$data[$i]["id_Empl"],
                    "Empleado"=>$data[$i]['Empleado']
                    );
                    }
                    // Retornar el array como JSON
            echo json_encode($list);
            }else{
                $response = array(
                    "error"=>"error"
                );
            }
    break;
    case "buscarAsignacionId":
        // Buscar asignación por ID
        // Validar si se ha enviado un ID de asignación
    if(isset($_POST["id_Asig"]) && !empty($_POST["id_Asig"])){
        // Si se ha enviado un ID de asignación, buscar los datos
                // Inicializar un array para almacenar los datos
                $list = array();
                // Si se encuentra la asignación, llenar el array con los datos
                $data = $cat->BuscarId($_POST["id_Asig"]);
                if($data){
                    // Llenar el array con los datos de la asignación
                        $list[] = array(
                            "id_Asig"=>$data["id_Asig"],
                            "placa"=>$data["Placa"],
                        );
                        // Retornar el array como JSON
                        echo json_encode($list);
                    }
                    }else{
                    $response = array(
                        "error"=>"error"
                    );
                }                
    break;
    case "buscarAsignacion":
        // Buscar asignación por placa
        if(isset($_POST["placa"]) && !empty($_POST["placa"])){
            // Validar si se ha enviado una placa
                // Si se ha enviado una placa, buscar los datos
                    $list = array();
                    // Buscar los datos de la asignación por placa
                $data = $cat->buscarAsignacion($_POST["placa"]);
                // Si no se encuentra la asignación, verificar si los campos son nulos
                    if($data && $data["Acta"]==null && $data["id_Asig"]==null){
                        // Si no se encuentra la asignación, llenar el array con campos vacíos
                        $list[] = array(
                            "id_Asig"=>"",
                            "placa"=>$_POST["placa"],
                            "Empleado"=>"",
                            "observaciones"=>$data["observaciones"],
                            "descripcion"=>$data["descripcion"],
                            "Acta"=>""
                        );
                        // Retornar el array como JSON
                        echo json_encode($list);
                    }else{
                        
                        if($data){
                            // Si se encuentra la asignación, llenar el array con los datos
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
                        // Si no se encuentra la asignación, llenar el array con campos vacíos
                        // Retornar el array como JSON
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
        // Eliminar asignación por ID
        // Validar si se ha enviado un ID de asignación
        if(isset($_POST["id_Asig"]) && !empty($_POST["id_Asig"])){
            // Si se ha enviado un ID de asignación, eliminar la asignación
            if($cat->EliminarAsignacion($_POST["id_Asig"])){
                // Si la asignación se elimina correctamente, retornar éxito
                $response = "sucess";
            }else{
                // Si hay un error al eliminar, retornar requerido
                $response = "required";
            }
        }else{
            // Si no se ha enviado un ID de asignación, retornar requerido
            $response = "required";
        }
        echo $response;
    break;
    case "Actualizar_Asignacion":
        // Actualizar asignación por ID
        // Validar los campos requeridos antes de actualizar la asignación
        if(isset($_POST["id_Asig"]) && !empty($_POST["id_Asig"]) && $_POST["descripcion"]
        && !empty($_POST["descripcion"]) && $_POST["observaciones"] && !empty($_POST["observaciones"]) 
        && $_POST["placa"] && !empty($_POST["placa"]) && $_POST["acta"] && !empty($_POST["acta"])){
            // Obtener los datos del formulario
            $placa=$_POST["placa"];
            $id_Empl=$_POST["id_Empl"];
                if($cat->ActualizarAsignacion($_POST["id_Asig"],$_POST["id_Empl"],$_POST["placa"],
                $_POST["observaciones"],$_POST["descripcion"],$_POST["acta"])){
                    // Si la asignación se actualiza correctamente, retornar éxito
                    $response ="sucess";
                }else{
                    // Si hay un error al actualizar, retornar error
                    $response ="error";
                }
        }else{
            // Si no se han enviado los campos requeridos, retornar requerido
           $response ="required";
        }
        // Retornar la respuesta
        echo $response;
    break;
    case "Activar_Asignacion":
        // Activar asignación por ID
        if(isset($_POST["id_Asig"]) && !empty($_POST["id_Asig"])){
            // Validar si se ha enviado un ID de asignación
            if($cat->ActivarAsignacion($_POST["id_Asig"])){
                // Si la asignación se activa correctamente, retornar éxito
                $response = "sucess";
            }else{
                // Si hay un error al activar, retornar requerido
                $response = "required";
            }
        }else{
            // Si no se ha enviado un ID de asignación, retornar requerido
            $response = "required";
        }
        echo $response;
    break;
    case"LlenarSelectEmpleadosUpdate":
        // Obtener la lista de empleados para llenar el select en la actualización
        $data = $cat->LlenarSelectEmpleadoUpdate();
        if($data){
            // Crear un array para almacenar los empleados
            for($i=0;$i<count($data);$i++){
                // Llenar el array con los datos de los empleados
                $list[]=array(
                    "id_Empl"=>$data[$i]["id_Empl"],
                    "Empleado"=>$data[$i]['Empleado']
                );
            }
            echo json_encode($list);
        }else{
            // Si no se encuentran empleados, retornar un array de error
            $response = array(
                "error"=>"error"
            );
        }
    break;
}

?>