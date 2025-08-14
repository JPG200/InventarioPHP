<?php
require "../model/Devolucion.php";

$cat = new Devolucion();

/*BuscarAsignacion('.$datos[$i]['Placa'].",'editar'".');
BuscarAsignacion('.$datos[$i]['Placa'].",'eliminar'".');
BuscarAsignacionPorId('.$datos[$i]['id_Asig'].');
*/

switch($_REQUEST["operador"]){
    case "listar_Devolucion":
        // Listar las devoluciones
        $datos = $cat->listarDevolucion();
        if(is_array(value: $datos)){
            // Recorrer los datos obtenidos
            for($i=0;$i<count($datos);$i++){

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
                            <a class="dropdown-item" data-toggle="modal" data-target="#updateDevolucion"
                            onclick="BuscarInformacionActaAsignacion('.$datos[$i]['Acta'].",'editar'".');">
                            <i class="icon-pencil"></i>Editar</a>
                        <a class="dropdown-item" onclick="BuscarInformacionActaAsignacion('.$datos[$i]['Acta'].",'eliminar'".');">
                        <i class="icon-trash"></i> Eliminar</a>
                    </div>':'<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="BuscarInformacionActaAsignacion('.$datos[$i]['Acta'].",'activar'".');">
                                    <i class="icon-check"></i> Activar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>'
                );
            }
            // Preparar el resultado para enviar como JSON
            $resultador = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($list),
                "iTotalDisplayRecords"=>count($list),
                "aaData"=>$list
            );
        }else{
            // Si no hay datos, enviar un mensaje de error
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
            // Preparar el resultado para enviar como JSON
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
            // Validar que los campos requeridos estén presentes y no vacíos
            if(isset($_POST['acta_asig']) && isset($_POST['observaciones']) && isset($_POST['acta']) && 
            !empty($_POST['acta_asig']) && !empty($_POST['observaciones']) && !empty($_POST['acta'])){
                // Obtener los datos del formulario
            $acta_asig = $_POST['acta_asig'];
            $observaciones = $_POST['observaciones'];
            $acta = $_POST['acta'];
            // Registrar la devolución utilizando el modelo
            if($cat->RegistrarDevolucion($acta_asig, $observaciones, $acta)){
                // Si la inserción es exitosa, enviar una respuesta de éxito
                $response="success";
            }else{
                // Si la inserción falla, enviar una respuesta de error
                $response="error";
            }
            }else{
                // Si los campos requeridos no están presentes o están vacíos, enviar una respuesta de error
                $response="required";
            }
        }catch(Exception $e){
            $response = "error";
        }
            echo $response;
    break;
    case "buscarDevolucion":
        if(isset($_POST["acta"]) && !empty($_POST["acta"])){
            // Buscar información de la devolución por el número de acta
                $list = array();
                // Llamar al método BuscarInformacion del modelo Devolucion
                $data = $cat->BuscarInformacion($_POST["acta"]);
                // Verificar si se obtuvieron datos
                    if($data && $data["id_Dev"]==null && $data["id_Dev"]==null){
                        // Si no se encontraron datos, enviar un mensaje de error
                        $list[] = array(
                            "id_Dev"=>"",
                            "placa"=>$data["placa"],
                            "observaciones"=>"",
                            "descripcion"=>$data["descripcion"],
                            "ActaAsig"=>$data["ActaAsig"],
                            "ActaDev"=>""
                    );
                        echo json_encode($list);
                    }else{
                        // Si se encontraron datos, preparar la respuesta con la información de la devolución
                    if(is_array($data)){
                        // Agregar los datos de la devolución al array de respuesta
                        $list[] = array(
                            "id_Dev"=>$data["id_Dev"],
                            "placa"=>$data["placa"],
                            "observaciones"=>$data["observaciones"],
                            "descripcion"=>$data["descripcion"],
                            "ActaAsig"=>$data["ActaAsig"],
                            "ActaDev"=>$data["ActaDev"],
                        );
                        echo json_encode($list);
                    }else{
                        // Si no se encontraron datos, enviar un mensaje de error
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
    case"actualizar_Devolucion":
        try{
            // Validar que los campos requeridos estén presentes y no vacíos
            if(isset($_POST['id_Dev']) && isset($_POST['acta_asig']) && isset($_POST['observaciones']) && isset($_POST['descripcion']) && isset($_POST['acta_dev']) && 
            !empty($_POST['id_Dev']) && !empty($_POST['acta_asig']) && !empty($_POST['observaciones']) && !empty($_POST['descripcion']) && !empty($_POST['acta_dev'])){
                // Obtener los datos del formulario
                $id_Dev = $_POST['id_Dev'];
                $acta_asig = $_POST['acta_asig'];
                $observaciones = $_POST['observaciones'];
                $acta = $_POST['acta_dev'];
                // Actualizar la devolución utilizando el modelo|
                if($cat->ActualizarDevolucion($id_Dev, $acta_asig, $observaciones, $acta)){
                    // Si la actualización es exitosa, enviar una respuesta de éxito
                    $response="success";
                }else{
                    // Si la actualización falla, enviar una respuesta de error
                    $response="error";
                }
            }else{
                $response="required";
            }
        }catch(Exception $e){
            $response = $e->getMessage();
        }
        echo $response;
    break;
    case "eliminar_Devolucion":
        // Eliminar una devolución
        if(isset($_POST['id_Dev']) && !empty($_POST['id_Dev'])){
            // Validar que el ID de la devolución esté presente y no vacío
            $id_Dev = $_POST['id_Dev'];
            // Llamar al método EliminarDevolucion del modelo Devolucion
            if($cat->EliminarDevolucion($id_Dev)){
                // Si la eliminación es exitosa, enviar una respuesta de éxito
                $response="success";
            }else{
                // Si la eliminación falla, enviar una respuesta de error
                $response="error";
            }
        }
    break;
    case"activar_Devolucion":
        // Activar una devolución
        // Validar que el ID de la devolución esté presente y no vacío
        if(isset($_POST['id_Dev']) && !empty($_POST['id_Dev'])){
            // Obtener el ID de la devolución desde el formulario
            $id_Dev = $_POST['id_Dev'];
            // Llamar al método ActivarDevolucion del modelo Devolucion
            if($cat->ActivarDevolucion($id_Dev)){
                // Si la activación es exitosa, enviar una respuesta de éxito
                $response="success";
            }else{
                // Si la activación falla, enviar una respuesta de error
                $response="error";
            }
        }
    break;
}
?>