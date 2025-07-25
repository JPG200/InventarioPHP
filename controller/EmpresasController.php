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

                // 'estado' es 1 para activo y 0 para inactivo
                if ($datos[$i]['estado'] == 0) {
                    $estadoDisplay = '<div class="tag tag-danger">Inactivo</div>'; // Si el estado en DB es 0, es inactivo.
                } elseif ($fechaFinalContratoStr !== "0000-00-00" && !empty($fechaFinalContratoStr) && $fechaActual > $fechaFinal) {
                    $estadoDisplay = '<div class="tag tag-danger">No Vigente</div>'; // Si la fecha ya pasó, no está vigente.
                } elseif ($diasRestantes >= 0 && $diasRestantes <= 7) {
                    $estadoDisplay = '<div class="tag tag-warning">Pronto Vencimiento</div>'; // Menos o igual a 7 días restantes.
                } else {
                    $estadoDisplay = '<div class="tag tag-success">Vigente</div>'; // Por defecto, si no cumple las anteriores y está activo.
                }
                
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

        echo json_encode($resultador);

    break;
    case "registrar_Empresa":
        if(isset($_POST['empresa']) && !empty($_POST['empresa'])&&isset($_POST['NIT']) && !empty($_POST['NIT'])&&
            isset($_POST['NumeroContrato']) && !empty($_POST['NumeroContrato'])
            &&isset($_POST['FechaI']) && !empty($_POST['FechaI'])&&isset($_POST['FechaF']) && !empty($_POST['FechaF'])){
            $empresa = $_POST['empresa'];
            $NIT = $_POST['NIT'];
            $NumeroContrato = $_POST['NumeroContrato'];
            $FechaI = $_POST['FechaI'];
            $FechaF = $_POST['FechaF'];

            $datos = $cat->RegistrarEmpresa($empresa,$NIT,$NumeroContrato,$FechaI,$FechaF);
            if($datos){
                $response="success";
            }else{
                $response="error";
            }
        }else{
            $response="required";
        }
        echo $response;
    break;
    case"actualizar_Empresa":
        if(isset($_POST['id_Empresa']) && !empty($_POST['id_Empresa'])&&isset($_POST['empresa']) && !empty($_POST['empresa'])&&
            isset($_POST['NIT']) && !empty($_POST['NIT']) && isset($_POST['NumeroContrato']) && !empty($_POST['NumeroContrato'])
            &&isset($_POST['FechaI']) && !empty($_POST['FechaI'])&&isset($_POST['FechaF']) && !empty($_POST['FechaF'])){

            $id_Empresa = $_POST['id_Empresa'];
            $empresa = $_POST['empresa'];
            $NIT = $_POST['NIT'];
            $NumeroContrato = $_POST['NumeroContrato'];
            $FechaI = $_POST['FechaI'];
            $FechaF = $_POST['FechaF'];

            $datos = $cat->ActualizarEmpresa($id_Empresa,$empresa,$NIT,$NumeroContrato, $FechaI, $FechaF);

            if($datos){
                $response="success";
            }else{
                $response="error";
            }
        }else{
            $response="required";
        }
        echo $response;
    break;
    case"buscar_Empresa":
        if(isset($_POST["NIT"]) && !empty($_POST["NIT"]) && isset($_POST["NumeroContrato"]) && !empty($_POST["NumeroContrato"])){
                $list = array();
                $data = $cat->BuscarInformacion($_POST["NIT"],$_POST["NumeroContrato"]);
                    if($data && $data["id_Empresa"]==null && $data["id_Empresa"]==null){
                        $list[] = array(
                            "id_Empresa"=>"",
                            "Empresa"=>$data["Empresa"],
                            "NIT"=>$data["NIT"],
                            "FechaI"=>$data["fecha_Inicio"],
                            "FechaF"=>$data["fecha_fin"],
                            "NumeroContrato"=>$data['NumeroContrato']
                    );
                        echo json_encode($list);
                    }else{
                    if(is_array($data)){
                        $list[] = array(
                            "id_Empresa"=>$data["id_Empresa"],
                            "Empresa"=>$data["Empresa"],
                            "NIT"=>$data["NIT"],
                            "FechaI"=>$data["fecha_Inicio"],
                            "FechaF"=>$data["fecha_fin"],
                            "NumeroContrato"=>$data['NumeroContrato']
                        );
                        echo json_encode($list);
                    }else{
                        $list[] = array(
                            "id_Empresa"=>"",
                            "Empresa"=>"",
                            "NIT"=>"",
                            "FechaI"=>"",
                            "FechaF"=>"",
                            "NumeroContrato"=>""
                        );
                        echo json_encode($list);  
                    }
                    }
                    $response = array(
                        "error"=>"error"
                    );
                }
    break;
    case"eliminar_Empresa":
        if(isset($_POST["NumeroContrato"]) && !empty($_POST["NumeroContrato"]) && isset($_POST["id_Empresa"]) && !empty($_POST["id_Empresa"])){
            $NumeroContrato = $_POST["NumeroContrato"];
            $id_Empresa = $_POST["id_Empresa"];
            $datos = $cat->eliminarContrato($NumeroContrato,$id_Empresa);
            if($datos){
                $response="success";
            }else{
                $response="error";
            }
        }else{
            $response="required";
        }
        echo $response;
        break;
        case"activar_Empresa":
        if(isset($_POST["NumeroContrato"]) && !empty($_POST["NumeroContrato"]) && isset($_POST["id_Empresa"]) && !empty($_POST["id_Empresa"])){
            $NumeroContrato = $_POST["NumeroContrato"];
            $id_Empresa = $_POST["id_Empresa"];
            $datos = $cat->activarEmpresa($id_Empresa,$NumeroContrato);
            if($datos){
                $response="success";
            }else{
                $response="error";
            }
        }else{
            $response="required";
        }
        echo $response;
            break;
}
?>