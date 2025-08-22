<?php
require "../model/Orden.php";

$cat = new Orden();
switch($_REQUEST["operador"]){
    case "listarOrden":
        try{
        // Llamar al método listarOrden del modelo Orden
        // y obtener los datos de las órdenes
        // Se inicializa el array $list para almacenar los datos  
        $list = array();
        // Se inicializa el array $list para almacenar los datos
        // Llamar al método listarOrden del modelo Orden
        // y obtener los datos de las órdenes
        $datos = $cat->listarOrden();
        if(is_array(value: $datos)){
            // Preparar la lista para el formato de respuesta
            for($i=0;$i<count($datos);$i++){   
                // Se inicializa el array $list para almacenar los datos  
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
                    "op"=> '<div class="btn-group">
                    <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="icon-gear"></i>
                    </button>
                    <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="modal" data-target="#searchEquiposOrden"
                            onclick="buscarEquiposActivosDevueltos('.$datos[$i]['id_Orden'].');">
                            <i class="icon-pencil"></i>Lista Equipos</a>
                    </div>'           
                );
            }
            // Preparar el resultado para la respuesta JSON
            $resultador = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($list),
                "iTotalDisplayRecords"=>count($list),
                "aaData"=>$list
            );
        }else{
            $list[] = array(
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
                    "op"=>""
            );
             $resultador = array(
                        "sEcho"=>1,
                        "iTotalRecords"=>count($list),
                        "iTotalDisplayRecords"=>count($list),
                        "aaData"=>$list
                    );
        }
    }catch(Exception $e){
            $list[] = array(
                "Orden De Compra"=> "ERROR",
                "Orden de Servicio"=> "ERROR",
                "Numero de Registro"=> "ERROR",
                "Fecha de Entrega"=> "ERROR",
                "Tipo de Orden"=> "ERROR",
                "Numero de Contrato"=> "ERROR",
                "Total de Equipos Activos"=> "ERROR",
                "Total de Equipos Devueltos"=> "ERROR",
                "Empresa"=> "ERROR",
                "Orden Original"=>"ERROR",
                "op"=>"ERROR"
            );
            $resultador = array(
                        "sEcho"=>1,
                        "iTotalRecords"=>count($list),
                        "iTotalDisplayRecords"=>count($list),
                        "aaData"=>$list
                    );
    }
        // Devolver el resultado como JSON
        echo json_encode($resultador);

    break;
    case "llenarTipoOrden":
        // Llamar al método llenarTipoOrden del modelo Orden
        // y obtener los datos de los tipos de orden
        // Se inicializa el array $list para almacenar los datos
        $data = $cat->llenarTipoOrden();
        // Se inicializa el array $list para almacenar los datos
        if($data){
            // Preparar la lista para el formato de respuesta
            for($i=0;$i<count($data);$i++){
                // Se inicializa el array $list para almacenar los datos
                $list[]=array(
                "id_TipoOrden"=>$data[$i]["id_TipoOrden"],
                "Tipo_Orden"=>$data[$i]['Tipo_Orden']
            );
            }
            // Devolver el resultado como JSON
            echo json_encode($list);
        }else{
            $response = array(
            "error"=>"error"
            );
        }
        break;
        case 'buscarContrato':
        try {
            // Validar que se haya enviado el número de contrato
         $numeroContrato = $_POST['numeroContrato'] ?? '';
         // Validar que el número de contrato no esté vacío
            if (empty($numeroContrato)) {
                echo json_encode(['status' => 'required', 'message' => 'El contrato es requerido para la búsqueda.']);
                exit();
            }
            // Llamar al método buscarContratoPorNumero del modelo Orden
            $datosOrden = $cat->buscarContratoPorNumero($numeroContrato);
              // Validar que se haya encontrado el contrato
            if ($datosOrden) {
                echo json_encode(['status' => 'success', 'message' => 'Contrato encontrado.', 'data' => $datosOrden]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'contrato no encontrado o datos inválidos.']);
            }
            // Devolver el resultado como JSON
        } catch (Exception $e) {
            error_log("Error en controlador (buscarOrden): " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor al buscar el contrato.']);
        }
        break;
        case 'buscarOrden':
        try {
            // Validar que se haya enviado el número de orden
         $numeroOrden = $_POST['numeroOrden'] ?? '';
            // Validar que el número de orden no esté vacío
            if (empty($numeroOrden)) {
                echo json_encode(['status' => 'required', 'message' => 'El numero de registro es requerido para la búsqueda.']);
                exit();
            }
            // Llamar al método buscarOrdenPorNumero del modelo Orden
            // y obtener los datos de la orden
            $datosOrden = $cat->buscarOrdenPorNumero($numeroOrden);
            // Validar que se haya encontrado la orden
            if ($datosOrden) {
                echo json_encode(['status' => 'success', 'message' => 'Orden encontrada.', 'data' => $datosOrden]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Orden no encontrada o datos inválidos.']);
            }
            // Devolver el resultado como JSON
        } catch (Exception $e) {
            error_log("Error en controlador (buscarOrden): " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor al buscar la orden.']);
        }
        break;
        case "registrarOrden":
        try {
            // Validar que se hayan enviado los datos necesarios
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
                    // Para instalación, los 'equiposParaDevolver' estarán vacíos. Solo se procesan los que entran.
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
                    // Para cambio, se procesan ambos conjuntos de equipos
                    $resultado = $cat->procesarOrden($ordenCompra, $ordenServicio, $fechaEntrega, $idTipoOrden, $numeroContrato, $equiposParaRegistrar, $equiposParaDevolver,$numeroRegistro);
                    break;

                default:
                // Tipo de orden no válido
                    echo json_encode(['status' => 'error', 'message' => 'Tipo de orden no válido.']);
                    exit();
            }
            // Verificar el resultado de la operación
            if ($resultado === true) {
                echo json_encode(['status' => 'success', 'message' => 'Orden procesada exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error desconocido al procesar la orden.']);
            }
            // Devolver el resultado como JSON
        } catch (Exception $e) {
            error_log("Error en controlador (registrarOrden): " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor al procesar la orden. Detalles: ' . $e->getMessage()]);
        }
        break;
}
?>