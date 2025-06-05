<?php
require "../model/Area.php";
$cat = new Equipos();

switch($_REQUEST["operador"]){
    case "Area_listar":  
        $datos = $cat->ListarArea();
        if(is_array(value: $datos)){
            for($i=0;$i<count($datos);$i++){
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]["id_Area"],
                    "Area"=> $datos[$i]['Area'],
                    "Centro de Costos"=> $datos[$i]['centro_costos'],
                    "Fecha de Inicio"=> $datos[$i]['fecha_creacion'],
                    "Fecha de Terminacion"=> ($datos[$i]['fecha_fin'])==null?
                                                '<div class="tag tag-success">Vigente</div>':
                                                $datos[$i]['fecha_fin'],
                    "Estado"=> ($datos[$i]['estado'])==1?'<div class="tag tag-success">Activo</div>':
                                                        '<div class="tag tag-danger">Inactivo</div>',
                    "op"=> $datos[$i]['estado']==1?'<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button> 
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#updateArea"
                                    onclick="BuscarArea('.$datos[$i]["id_Area"].",'editar'".');">
                                    <i class="icon-pencil"></i> Editar</a>
                                    <a class="dropdown-item" 
                                    onclick="BuscarArea('.$datos[$i]['id_Area'].",'eliminar'".');">          
                                    <i class="icon-trash"></i> Eliminar</a>
                                </div>
                            </div>':'
                            <div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="BuscarArea('.$datos[$i]['id_Area'].",'activar'".');"><i class="icon-check"></i> Activar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>'
                    /*($datos[$i]['estado'])==1?'<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#updateEquipo"
                                     onclick="BuscarEquipo('.$datos[$i]['id_Area'].",'editar'".');">
                                     <i class="icon-pencil"></i> Editar</a>
                                    <a class="dropdown-item" onclick="BuscarEquipo('.$datos[$i]['id_Area'].",'eliminar'".');">
                                    <i class="icon-trash"></i> Eliminar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>':'
                            <div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="BuscarEquipo('.$datos[$i]['id_Area'].",'activar'".');"><i class="icon-check"></i> Activar</a>
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
            $resultador = array(
                "sEcho"=>1,
                "iTotalRecords"=>0,
                "iTotalDisplayRecords"=>0,
                "aaData"=>array()
            );
        }

        echo json_encode($resultador);

    break;
        case "buscar_area":
        if(isset($_POST["id_Area"]) && !empty($_POST["id_Area"])){
        $data = $cat->buscarArea($_POST["id_Area"]);
            if($data){
                $list[] = array(
                    "Numero de Registro"=>$data["id_Area"],
                    "Area"=>$data["Area"],
                    "centro_costos"=>$data["centro_costos"]
                    );
                echo json_encode($list);
            }else{
                $response = array(
                    "error"=>"error"
                );
            }

        }
        break;
    case "registrar_Area":
        if(isset($_POST["area"]) && isset($_POST["centro_costos"]) 
        && !empty($_POST["area"] && $_POST["centro_costos"])){
            $Area = $_POST["area"];
            $centro_costos = $_POST["centro_costos"];
            if($cat->VerificarArea($centro_costos)){
                if($cat->CrearArea($Area,$centro_costos)){
                    $response ="sucess";
                }else
                {
                    $response ="error";
                }
            }
            else
            {
                $response ="registered"; //El equipo ya existe o esta activo
            }
        }
        else
        {
            $response ="required";
        }
        echo $response;
     break;
    case "Actualizar_Area":
                if(isset($_POST["Area"],$_POST["Centro_costos"])
                && !empty($_POST["Area"]) && !empty($_POST["Centro_costos"])){
                    $id=$_POST["id_Area"];
                    $area=$_POST["Area"];
                    $centro_costos = $_POST["Centro_costos"];
                    if($cat->ActualizarArea($id,$area,$centro_costos)){
                        $response = "sucess";
                    }else{
                        $response = "error";
                    }
                }else{
                    $response = "required";
                }
                echo $response;
    break;
case "Eliminar_Area":
            if(isset($_POST["id_Area"]) && !empty($_POST["id_Area"])){
                $id=$_POST["id_Area"];
                if($cat->EliminarArea($id)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }
            }else{
                $response = "required";
            }
            echo $response;
        break;
        case "Activar_Area":
            if(isset($_POST["id_Area"]) && !empty($_POST["id_Area"])){
                $id=$_POST["id_Area"];
                if($cat->ActivarArea($id)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }
            }else{
                $response = "required";
            }
            echo $response;
}
?>