<?php

require "../model/Area.php";
$cat = new Equipos();

switch($_REQUEST["operador"]){
    case "Area_listar":  
        try{
        // Listar las áreas
        $datos = $cat->ListarArea();
        if(is_array(value: $datos)){
            for($i=0;$i<count($datos);$i++){
                // Preparar los datos para la respuesta
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
                );
            }
            $resultador = array(
                // Respuesta para el DataTable
                "sEcho"=>1,
                "iTotalRecords"=>count($list),
                "iTotalDisplayRecords"=>count($list),
                "aaData"=>$list
            );
        }else{
            // Respuesta en caso de vacios
            $list[]=array(
                "Numero de Registro"=>"",
                "Area"=> "",
                "Centro de Costos"=> "",
                "Fecha de Inicio"=> "",
                "Fecha de Terminacion"=> "",
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
            "Area"=> "ERROR",
            "Centro de Costos"=> "ERROR",
            "Fecha de Inicio"=> "ERROR",
            "Fecha de Terminacion"=> "ERROR",
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
        case "buscar_area":
        // Buscar un área específica
        if(isset($_POST["id_Area"]) && !empty($_POST["id_Area"])){
        $data = $cat->buscarArea($_POST["id_Area"]);
            if($data){
                // Preparar los datos para la respuesta
                $list[] = array(
                    "Numero de Registro"=>$data["id_Area"],
                    "Area"=>$data["Area"],
                    "centro_costos"=>$data["centro_costos"]
                    );
                echo json_encode($list);
            }else{
                // Respuesta en caso de error
                $response = array(
                    "error"=>"error"
                );
            }

        }
        break;
    case "registrar_Area":
        // Registrar un área
        if(isset($_POST["area"]) && isset($_POST["centro_costos"]) 
        && !empty($_POST["area"] && $_POST["centro_costos"])){
        // Verificar si el área ya existe
            $Area = $_POST["area"];
            $centro_costos = $_POST["centro_costos"];
            if($cat->VerificarArea($centro_costos)){
                // Crear el área
                if($cat->CrearArea($Area,$centro_costos)){
                    // Respuesta exitosa
                    $response ="sucess";
                }else
                {
                    // Respuesta en caso de error
                    $response ="error";
                }
            }
            else
            {
                // Respuesta si el área ya existe o está activa
                $response ="registered"; //El equipo ya existe o esta activo
            }
        }
        else
        {
            // Respuesta si faltan datos
            $response ="required";
        }
        echo $response;
     break;
    case "Actualizar_Area":
        // Actualizar un área
                if(isset($_POST["Area"],$_POST["Centro_costos"])
                && !empty($_POST["Area"]) && !empty($_POST["Centro_costos"])){
                    // Obtener los datos del área
                    $id=$_POST["id_Area"];
                    $area=$_POST["Area"];
                    $centro_costos = $_POST["Centro_costos"];
                    // Actualizar el área
                    if($cat->ActualizarArea($id,$area,$centro_costos)){
                        // Respuesta exitosa
                        $response = "sucess";
                    }else{
                        // Respuesta en caso de error
                        $response = "error";
                    }
                }else{
                    // Respuesta si faltan datos
                    $response = "required";
                }
                echo $response;
    break;
case "Eliminar_Area":
            // Eliminar un área
            if(isset($_POST["id_Area"]) && !empty($_POST["id_Area"])){
                // Obtener el ID del área
                $id=$_POST["id_Area"];
                // Eliminar el área
                if($cat->EliminarArea($id)){
                    // Respuesta exitosa
                    $response = "sucess";
                }else{
                    // Respuesta en caso de error
                    $response = "error";
                }
            }else{
                // Respuesta si faltan datos
                $response = "required";
            }
            echo $response;
        break;
        case "Activar_Area":
            // Activar un área
            if(isset($_POST["id_Area"]) && !empty($_POST["id_Area"])){
                $id=$_POST["id_Area"];
                // Activar el área
                if($cat->ActivarArea($id)){
                    // Respuesta exitosa
                    $response = "sucess";
                }else{
                    // Respuesta en caso de error
                    $response = "error";
                }
            }else{
                // Respuesta si faltan datos
                $response = "required";
            }
            echo $response;
}
?>