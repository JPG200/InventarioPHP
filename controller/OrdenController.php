<?php
require "../model/Orden.php";

$cat = new Orden();
switch($_REQUEST["operador"]){
    case "listarOrden":
        $datos = $cat->listarOrden();
        if(is_array(value: $datos)){
            for($i=0;$i<count($datos);$i++){
                /*$fechaFinalContratoStr = $datos[$i]['fecha_fin'];

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
                */
                
                $list[]=array(
                    "Orden De Compra"=> $datos[$i]['orden_Compra'],
                    "Orden de Servicio"=> $datos[$i]['orden_Servicio'],
                    "Numero de Registro"=> $datos[$i]['id_Orden'],
                    "Fecha de Entrega"=> $datos[$i]['fecha_Entrega'],
                    "Tipo de Orden"=> $datos[$i]['Tipo_Orden'],
                    "Numero de Contrato"=> $datos[$i]['NumeroContrato'],
                    "Total de Equipos Activos"=> $datos[$i]['TotalEquiposActivos'],
                    "Total de Equipos Devueltos"=> $datos[$i]['TotalEquiposDevueltos'],
                    "Empresa"=> $datos[$i]['Empresa'],
                    "Orden Original"=>($datos[$i]['orden_original'])==0?'<div class="tag tag-success">Orden Origen</div>':
                                                        $datos[$i]['orden_original'],
                    "op"=>($datos[$i]['TotalEquiposActivos'])==0? '<div class="btn-group">
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
                                    <a class="dropdown-item" onclick="">
                                    <i class="icon-check"></i> Activar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>'
                    /*"op"=> ($datos[$i]['estado'])==1? '<div class="btn-group">
                    <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="icon-gear"></i>
                    </button>
                    <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="modal" data-target="#updateEmpresa"
                            onclick="">
                            <i class="icon-pencil"></i>Editar</a>
                        <a class="dropdown-item" onclick="">
                        <i class="icon-trash"></i> Eliminar</a>
                    </div>':'<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="">
                                    <i class="icon-check"></i> Activar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>'*/
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
                    "Orden De Compra"=> "",
                    "Orden de Servicio"=> "",
                    "Numero de Registro"=> "",
                    "Fecha de Entrega"=> "",
                    "Tipo de Orden"=> "",
                    "Numero de Contrato"=> "",
                    "Total de Equipos Activos"=> "",
                    "Total de Equipos Devueltos"=> "",
                    "Empresa"=> "",
                    "Orden Original"=>"",
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
    case "llenarTipoOrden":
        $data = $cat->llenarTipoOrden();
        if($data){
            for($i=0;$i<count($data);$i++){
                $list[]=array(
                "id_TipoOrden"=>$data[$i]["id_TipoOrden"],
                "Tipo_Orden"=>$data[$i]['Tipo_Orden']
            );
            }
            echo json_encode($list);
        }else{
            $response = array(
            "error"=>"error"
            );
        }
        break;
        case 'buscarContrato':
        try {
         $numeroContrato = $_POST['numeroContrato'] ?? '';
            if (empty($numeroContrato)) {
                echo json_encode(['status' => 'required', 'message' => 'El contrato es requerido para la búsqueda.']);
                exit();
            }

            $datosOrden = $cat->buscarContratoPorNumero($numeroContrato);

            if ($datosOrden) {
                echo json_encode(['status' => 'success', 'message' => 'Contrato encontrado.', 'data' => $datosOrden]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'contrato no encontrado o datos inválidos.']);
            }

        } catch (Exception $e) {
            error_log("Error en controlador (buscarOrden): " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor al buscar el contrato.']);
        }
        break;
        case 'buscarOrden':
        try {
         $numeroOrden = $_POST['numeroOrden'] ?? '';

            if (empty($numeroOrden)) {
                echo json_encode(['status' => 'required', 'message' => 'El numero de registro es requerido para la búsqueda.']);
                exit();
            }

            $datosOrden = $cat->buscarOrdenPorNumero($numeroOrden);

            if ($datosOrden) {
                echo json_encode(['status' => 'success', 'message' => 'Orden encontrada.', 'data' => $datosOrden]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Orden no encontrada o datos inválidos.']);
            }

        } catch (Exception $e) {
            error_log("Error en controlador (buscarOrden): " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor al buscar la orden.']);
        }
        break;
        case "registrarOrden":
        try {
            $ordenCompra = $_POST['ordenCompra'] ?? '';
            $ordenServicio = $_POST['ordenServicio'] ?? '';
            $fechaEntrega = $_POST['fechaEntrega'] ?? '';
            $idTipoOrden = $_POST['idTipoOrden'] ?? '';
            $numeroContrato = $_POST['numeroContrato'] ?? '';
            $equiposParaRegistrar = $_POST['equiposParaRegistrar'] ?? []; // Equipos que entran/se asocian
            $equiposParaDevolver = $_POST['equiposParaDevolver'] ?? [];  // Equipos que salen/se desvinculan
            $numeroRegistro = $_POST['numeroRegistro'];

            // Validación principal de campos de la orden
            if (empty($ordenCompra) || empty($ordenServicio) || empty($idTipoOrden) || empty($numeroContrato)) {
                echo json_encode(['status' => 'required', 'message' => 'Todos los campos principales de la orden son requeridos.']);
                exit();
            }

            $resultado = false;
            // Lógica según el tipo de orden
            switch ($idTipoOrden) {
                case '1': // Tipo de Orden: Instalación
                    if (empty($equiposParaRegistrar)) {
                        echo json_encode(['status' => 'required', 'message' => 'Para una Instalación, debe agregar al menos un equipo.']);
                        exit();
                    }
                    $resultado = $cat->procesarOrden($ordenCompra, $ordenServicio, $fechaEntrega, $idTipoOrden, $numeroContrato, $equiposParaRegistrar, [],$numeroRegistro);
                    break;

                case '3': // Tipo de Orden: Devolución
                    $equiposParaDevolver=$equiposParaRegistrar;
                    if (empty($equiposParaDevolver)) {
                        echo json_encode(['status' => 'required', 'message' => 'Para una Devolución, debe especificar los equipos a devolver.']);
                        exit();
                    }
                    
                    // Para devolución, los 'equiposParaRegistrar' estarán vacíos. Solo se procesan los que salen.
                    $resultado = $cat->procesarOrden($ordenCompra, $ordenServicio, $fechaEntrega, $idTipoOrden, $numeroContrato, $equiposParaDevolver, $equiposParaDevolver,$numeroRegistro);
                    break;

                case '2': // Tipo de Orden: Cambio
                    if (empty($equiposParaDevolver) || empty($equiposParaRegistrar)) {
                        echo json_encode(['status' => 'required', 'message' => 'Para un Cambio, se requieren tanto los equipos a devolver como los nuevos equipos.']);
                        exit();
                    }
                    $resultado = $cat->procesarOrden($ordenCompra, $ordenServicio, $fechaEntrega, $idTipoOrden, $numeroContrato, $equiposParaRegistrar, $equiposParaDevolver,$numeroRegistro);
                    break;

                default:
                    echo json_encode(['status' => 'error', 'message' => 'Tipo de orden no válido.']);
                    exit();
            }

            if ($resultado === true) {
                echo json_encode(['status' => 'success', 'message' => 'Orden procesada exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error desconocido al procesar la orden.']);
            }

        } catch (Exception $e) {
            error_log("Error en controlador (registrarOrden): " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor al procesar la orden. Detalles: ' . $e->getMessage()]);
        }
        break;
}
?>