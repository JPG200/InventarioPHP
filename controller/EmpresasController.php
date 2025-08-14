<?php
require "../model/Empresa.php";

$cat = new Empresa();

switch($_REQUEST["operador"]){
    case "listar_Empresa":
        $datos = $cat->listarEmpresa();
        if(is_array( $datos)){
            for($i=0;$i<count($datos);$i++){
                $fechaFinalContratoStr = $datos[$i]['fecha_fin'];

                // Inicializamos la variable para la vigencia
                $vigenciaDisplay = '';
                 if ($fechaFinalContratoStr !== "0000-00-00" && !empty($fechaFinalContratoStr)) {
                    // Creamos objetos DateTime para la fecha final y la fecha actual
                    $fechaFinal = new DateTime($fechaFinalContratoStr);
                    $fechaActual = new DateTime(); // La fecha y hora actuales

                    // Calculamos la diferencia entre las dos fechas
                    $diferencia = $fechaActual->diff($fechaFinal);
                    $diasRestantes = (int)$diferencia->days; // Convertimos a entero para asegurar

                    // Comprobamos si la fecha actual ha superado la fecha final
                    if ($fechaActual > $fechaFinal) {
                        // Si la fecha actual es posterior a la fecha final, el contrato no está vigente
                        $vigenciaDisplay = '<div class="tag tag-danger"><i class="fa fa-times-circle"></i> No Vigente</div>';
                        // O si no tienes Font Awesome: $vigenciaDisplay = '<div class="tag tag-danger">No Vigente</div>';
                    } else if($datos[$i]['estado'] == 0){
                        $vigenciaDisplay = '<div class="tag tag-danger"><i class="fa fa-times-circle"></i> No Vigente</div>';  
                    }
                    else {
                        // Si la fecha actual es anterior o igual a la fecha final, mostramos los días restantes
                        $vigenciaDisplay = '<div class="tag tag-success">' . $diasRestantes . ' días restantes</div>';
                    }
                } else {
                    // Si la fecha_fin es inválida o vacía, asumimos que está vigente o mostramos un mensaje predeterminado
                    $vigenciaDisplay = '<div class="tag tag-success">Vigente (Sin Fecha Fin)</div>';
                }

                // Determinamos el estado del contrato
                // "estado" es 1 para activo y 0 para inactivo
                // Si el estado en DB es 0, es inactivo.
                
                if ($datos[$i]['estado'] == 0) {
                    $estadoDisplay = '<div class="tag tag-danger">Inactivo</div>'; // Si el estado en DB es 0, es inactivo.
                } elseif ($fechaFinalContratoStr !== "0000-00-00" && !empty($fechaFinalContratoStr) && $fechaActual > $fechaFinal) {
                    $estadoDisplay = '<div class="tag tag-danger">No Vigente</div>'; // Si la fecha ya pasó, no está vigente.
                } elseif ($diasRestantes >= 0 && $diasRestantes <= 7) {
                    $estadoDisplay = '<div class="tag tag-warning">Pronto Vencimiento</div>'; // Menos o igual a 7 días restantes.
                } else {
                    $estadoDisplay = '<div class="tag tag-success">Vigente</div>'; // Por defecto, si no cumple las anteriores y está activo.
                }
                
                // Creamos el array para cada empresa
                
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]['id_Empresa'],
                    "Empresa"=> $datos[$i]['Empresa'],
                    "NIT"=> $datos[$i]['NIT'],
                    "Numero de Contrato"=> $datos[$i]['NumeroContrato'],
                    "Fecha de Inicio"=> $datos[$i]['fecha_Inicio'],
                    "Fecha de Final"=> $datos[$i]['fecha_fin'],
                    "Vigencia"=> $vigenciaDisplay,
                    "Estado"=> $estadoDisplay,
                    "op"=> ($datos[$i]['estado'])==1? '<div class="btn-group">
                    <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="icon-gear"></i>
                    </button>
                    <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="modal" data-target="#updateEmpresa"
                            onclick="AlertaBuscarEmpresa('.$datos[$i]['NIT'].",'".htmlspecialchars($datos[$i]['NumeroContrato'])."','editar'".');">
                            <i class="icon-pencil"></i>Editar</a>
                        <a class="dropdown-item" onclick="AlertaBuscarEmpresa('.$datos[$i]['NIT'].",'".htmlspecialchars($datos[$i]['NumeroContrato'])."','eliminar'".');">
                        <i class="icon-trash"></i> Eliminar</a>
                    </div>':'<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="AlertaBuscarEmpresa('.$datos[$i]['NIT'].",'".htmlspecialchars($datos[$i]['NumeroContrato'])."','activar'".');">
                                    <i class="icon-check"></i> Activar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>'
                );
            }
            // Preparamos el resultado para enviar como JSON
            $resultador = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($list),
                "iTotalDisplayRecords"=>count($list),
                "aaData"=>$list
            );
        }else{
            $datos = array(
                "Numero de Registro"=>"ERROR",
                "Empresa"=> "ERROR",
                "NIT"=> "ERROR",
                "Numero de Contrato"=> "ERROR",
                "Fecha de Inicio"=> "ERROR",
                "Fecha de Final"=> "ERROR",
                "Vigencia"=> "ERROR",
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
        // Enviamos el resultado como JSON
        echo json_encode($resultador);

    break;
    case "registrar_Empresa":
        // Verificamos que los campos requeridos estén presentes y no vacíos
        // Si están presentes, procedemos a registrar la empresa
        // Si el registro es exitoso, devolvemos "success"; de lo contrario, "error"
        if(isset($_POST['empresa']) && !empty($_POST['empresa'])&&isset($_POST['NIT']) && !empty($_POST['NIT'])&&
            isset($_POST['NumeroContrato']) && !empty($_POST['NumeroContrato'])
            &&isset($_POST['FechaI']) && !empty($_POST['FechaI'])&&isset($_POST['FechaF']) && !empty($_POST['FechaF'])){
                // Obtenemos los datos del formulario
                // Creamos la empresa con los datos proporcionados
            $empresa = $_POST['empresa'];
            $NIT = $_POST['NIT'];
            $NumeroContrato = $_POST['NumeroContrato'];
            $FechaI = $_POST['FechaI'];
            $FechaF = $_POST['FechaF'];
            // Llamamos al método RegistrarEmpresa del modelo Empresa
            // Si el registro es exitoso, devolvemos "success"; de lo contrario, "error"
            $datos = $cat->RegistrarEmpresa($empresa,$NIT,$NumeroContrato,$FechaI,$FechaF);
            if($datos){
                $response="success";
            }else{
                $response="error";
            }
        }else{
            $response="required";
        }
        // Enviamos la respuesta
        echo $response;
    break;
    case"actualizar_Empresa":
        // Verificamos que los campos requeridos estén presentes y no vacíos
        if(isset($_POST['id_Empresa']) && !empty($_POST['id_Empresa'])&&isset($_POST['empresa']) && !empty($_POST['empresa'])&&
            isset($_POST['NIT']) && !empty($_POST['NIT']) && isset($_POST['NumeroContrato']) && !empty($_POST['NumeroContrato'])
            &&isset($_POST['FechaI']) && !empty($_POST['FechaI'])&&isset($_POST['FechaF']) && !empty($_POST['FechaF'])){
            // Obtenemos los datos del formulario
            // Actualizamos la empresa con los datos proporcionados
            $id_Empresa = $_POST['id_Empresa'];
            $empresa = $_POST['empresa'];
            $NIT = $_POST['NIT'];
            $NumeroContrato = $_POST['NumeroContrato'];
            $FechaI = $_POST['FechaI'];
            $FechaF = $_POST['FechaF'];
            // Llamamos al método ActualizarEmpresa del modelo Empresa
            // Si la actualización es exitosa, devolvemos "success"; de lo contrario, "error"
            $datos = $cat->ActualizarEmpresa($id_Empresa,$empresa,$NIT,$NumeroContrato, $FechaI, $FechaF);
            if($datos){
                $response="success";
            }else{
                $response="error";
            }
        }else{
            $response="required";
        }
        // Enviamos la respuesta
        echo $response;
    break;
    case"buscar_Empresa":
        // Verificamos que los campos requeridos estén presentes y no vacíos
        // Si están presentes, buscamos la empresa
        // Si la empresa existe, devolvemos sus datos; de lo contrario, devolvemos un mensaje de error
        if(isset($_POST["NIT"]) && !empty($_POST["NIT"]) && isset($_POST["NumeroContrato"]) && !empty($_POST["NumeroContrato"])){
                $list = array();
                $data = $cat->BuscarInformacion($_POST["NIT"],$_POST["NumeroContrato"]);
                    if($data && $data["id_Empresa"]==null && $data["id_Empresa"]==null){
                        // Si no se encuentra la empresa, devolvemos un array con Id_Empresa vacío
                        $list[] = array(
                            "id_Empresa"=>"",
                            "Empresa"=>$data["Empresa"],
                            "NIT"=>$data["NIT"],
                            "FechaI"=>$data["fecha_Inicio"],
                            "FechaF"=>$data["fecha_fin"],
                            "NumeroContrato"=>$data['NumeroContrato']
                    );
                    // Enviamos los datos de la empresa encontrada
                        echo json_encode($list);
                    }else{
                    if(is_array($data)){
                        // Si se encuentra la empresa, devolvemos un array con los datos de la empresa
                        $list[] = array(
                            "id_Empresa"=>$data["id_Empresa"],
                            "Empresa"=>$data["Empresa"],
                            "NIT"=>$data["NIT"],
                            "FechaI"=>$data["fecha_Inicio"],
                            "FechaF"=>$data["fecha_fin"],
                            "NumeroContrato"=>$data['NumeroContrato']
                        );
                        // Enviamos los datos de la empresa encontrada
                        echo json_encode($list);
                    }else{
                        // Si no se encuentra la empresa, devolvemos un array con campos vacíos
                        // Esto es útil para manejar casos donde no se encuentra la empresa
                        // o cuando los datos no son válidos
                        $list[] = array(
                            "id_Empresa"=>"",
                            "Empresa"=>"",
                            "NIT"=>"",
                            "FechaI"=>"",
                            "FechaF"=>"",
                            "NumeroContrato"=>""
                        );
                        // Enviamos los datos de la empresa encontrada
                        echo json_encode($list);  
                    }
                    }
                    $response = array(
                        "error"=>"error"
                    );
                }
    break;
    case"eliminar_Empresa":
        // Verificamos que los campos requeridos estén presentes y no vacíos
        // Si están presentes, procedemos a eliminar la empresa
        // Si la eliminación es exitosa, devolvemos "success"; de lo contrario, "error"
        if(isset($_POST["NumeroContrato"]) && !empty($_POST["NumeroContrato"]) && isset($_POST["id_Empresa"]) && !empty($_POST["id_Empresa"])){
            // Obtenemos los datos del formulario
            // Eliminamos la empresa con los datos proporcionados
            $NumeroContrato = $_POST["NumeroContrato"];
            $id_Empresa = $_POST["id_Empresa"];
            $datos = $cat->eliminarContrato($NumeroContrato,$id_Empresa);
            // Si la eliminación es exitosa, devolvemos "success"; de lo contrario, "error"
            if($datos){
                $response="success";
            }else{
                $response="error";
            }
        }else{
            $response="required";
        }
        // Enviamos la respuesta
        echo $response;
        break;
        case"activar_Empresa":
            // Verificamos que los campos requeridos estén presentes y no vacíos
            // Si están presentes, procedemos a activar la empresa
            // Si la activación es exitosa, devolvemos "success"; de lo contrario, "error"
        if(isset($_POST["NumeroContrato"]) && !empty($_POST["NumeroContrato"]) && isset($_POST["id_Empresa"]) && !empty($_POST["id_Empresa"])){
            // Obtenemos los datos del formulario
            // Activamos la empresa con los datos proporcionados
            $NumeroContrato = $_POST["NumeroContrato"];
            $id_Empresa = $_POST["id_Empresa"];
            // Llamamos al método activarEmpresa del modelo Empresa
            $datos = $cat->activarEmpresa($id_Empresa,$NumeroContrato);
            // Si la activación es exitosa, devolvemos "success"; de lo contrario, "error"
            if($datos){
                $response="success";
            }else{
                $response="error";
            }
        }else{
            $response="required";
        }
        // Enviamos la respuesta
        echo $response;
            break;
}
?>