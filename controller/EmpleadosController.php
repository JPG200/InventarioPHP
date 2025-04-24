<?php
require "../model/Empleados.php";


$cat = new Empleados();

switch($_REQUEST["operador"]){
    case "listar_Empleados":
        $datos = $cat->listarEmpleados();
        if(is_array(value: $datos)){
            for($i=0;$i<count($datos);$i++){
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]["id_Empl"],
                    "Cedula"=> $datos[$i]['cedula'],
                    "Nombre"=> $datos[$i]['nombre'],
                    "Apellido"=> $datos[$i]['apellido'],
                    "Area"=> $datos[$i]['Area'],
                    "Estado"=> $datos[$i]['estado']==1?'<div class="tag tag-success">Activo</div>':
                                                        '<div class="tag tag-danger">Inactivo</div>',
                    "op"=>'<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="true"><i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item"><i class="icon-edit"> Editar</i></a>
                                    <a class="dropdown-item"><i class="icon-trash"> Eliminar</i></a>
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
                    "Nombre"=> "ERROR",
                    "Apellido"=> "ERROR",
                    "Area"=> "ERROR",
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