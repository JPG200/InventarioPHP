<?php
require "../model/Empresa.php";

$cat = new Empresa();

switch($_REQUEST["operador"]){
    case "listar_Empresa":
        $datos = $cat->listarEmpresa();
        if(is_array(value: $datos)){
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
                    } else {
                        // Si la fecha actual es anterior o igual a la fecha final, mostramos los días restantes
                        $vigenciaDisplay = '<div class="tag tag-success">' . $diasRestantes . ' días restantes</div>';
                    }
                } else {
                    // Si la fecha_fin es inválida o vacía, asumimos que está vigente o mostramos un mensaje predeterminado
                    $vigenciaDisplay = '<div class="tag tag-success">Vigente (Sin Fecha Fin)</div>';
                }
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]['id_Empresa'],
                    "Empresa"=> $datos[$i]['Empresa'],
                    "NIT"=> $datos[$i]['NIT'],
                    "Numero de Contrato"=> $datos[$i]['NumeroContrato'],
                    "Fecha de Inicio"=> $datos[$i]['fecha_Inicio'],
                    "Fecha de Final"=> $datos[$i]['fecha_fin'],
                    "Vigencia"=> $vigenciaDisplay,
                    "Estado"=> $datos[$i]['estado']==1?'<div class="tag tag-danger">No Entregado</div>':
                                                        '<div class="tag tag-success">Entregado</div>',
                    "op"=> ($datos[$i]['estado'])==1? '<div class="btn-group">
                    <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="icon-gear"></i>
                    </button>
                    <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="modal" data-target="#updateDevolucion"
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
}
?>