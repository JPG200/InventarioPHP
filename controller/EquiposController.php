<?php
require "../model/Equipos.php";
$cat = new Equipos();

switch($_REQUEST["operador"]){
    case "listar_categorias":  
        $datos = $cat->ListarEquipos();
        if(is_array(value: $datos)){
            for($i=0;$i<count($datos);$i++){
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]["id_Equip"],
                    "Placa"=> $datos[$i]['placa'],
                    "Serial"=> $datos[$i]['serial'],
                    "Fecha de Ingreso"=> $datos[$i]['fecha_creacion'],
                    "Estado"=> $datos[$i]['estado']==1?'<div class="tag tag-success">Activo</div>':
                                                        '<div class="tag tag-danger">Inactivo</div>',
                    "op"=> '<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#updateEquipo"
                                     onclick="BuscarEquipo('.$datos[$i]['id_Equip'].');"><i class="icon-pencil"></i> Editar</a>
                                    <a class="dropdown-item" href="#"><i class="icon-trash"></i> Eliminar</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"><i class="icon-eye"></i> Ver</a>
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
    case "registrar_equipo":
        if(isset($_POST["placa"]) && isset($_POST["serial"]) 
        && !empty($_POST["placa"] && $_POST["serial"]) || !empty($_POST["placa"] || $_POST["serial"])){
            $placa = $_POST["placa"];
            $serial = $_POST["serial"];
            if($cat->Verificar($placa)){
                if($cat->RegistrarEquipos($placa,$serial)){
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
     break;

    case "buscar_equipo":
        if(isset($_POST["id_Equip"]) && !empty($_POST["id_Equip"])){
        $data = $cat->buscarEquipo($_POST["id_Equip"]);
            if($data){
                $list[] = array(
                    "Numero de Registro"=>$data["id_Equip"],
                    "placa"=>$data["placa"],
                    "serial"=>$data["serial"],
                    "estado"=>$data["estado"]
                );
                echo json_encode($list);
            }else{
                $response = array(
                    "error"=>"error"
                );
            }

        }
        break;

        case "Actualizar_Equipos":
            if(isset($_POST["placa"],$_POST["serial"])
             && !empty($_POST["placa"]) && !empty($_POST["serial"])){
                $id=$_POST["id_Equip"];
                $placa=$_POST["placa"];
                $serial = $_POST["serial"];
                if($cat->ActualizarEquipos($id,$placa,$serial)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }
            }else{
                $response = "required";
            }
            echo $response;
        break;
}

?>