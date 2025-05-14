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
                    "Email"=> $datos[$i]['correo'],
                    "Area"=> $datos[$i]['Area'],
                    "Estado"=> $datos[$i]['estado']==1?'<div class="tag tag-success">Activo</div>':
                                                        '<div class="tag tag-danger">Inactivo</div>',
                    "op"=> ($datos[$i]['estado'])==1?'<div class="btn-group">
                    <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="icon-gear"></i>
                    </button>
                    <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="modal" data-target="#updateEmpleado"
                            onclick="BuscarEmpleado('.$datos[$i]['id_Empl'].",'editar'".');">
                            <i class="icon-pencil"></i> Editar</a>
                        <a class="dropdown-item" onclick="BuscarEmpleado('.$datos[$i]['id_Empl'].",'eliminar'".');">
                        <i class="icon-trash"></i> Eliminar</a>
                    </div>':'
                            <div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="BuscarEmpleado('.$datos[$i]['id_Empl'].",'activar'".');"><i class="icon-check"></i> Activar</a>
                                    <div class="dropdown-divider"></div>
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
    case"LlenarSelectArea":
        $data = $cat->LlenarSelectArea();
        if($data){
            for($i=0;$i<count($data);$i++){
                $list[]=array(
                "id_Area"=>$data[$i]["id_Area"],
                "Area"=>$data[$i]['Area']
            );
            }
            echo json_encode($list);
        }else{
            $response = array(
            "error"=>"error"
            );
        }
    break;
        case "buscarEmpleado":
        if(isset($_POST["id_Empl"]) && !empty($_POST["id_Empl"])){
                $data = $cat->buscarEmpleadosId($_POST["id_Empl"]);
                    if($data && $data["correo"]!=null){
                        $list[] = array(
                            "id_Empl"=>$data["id_Empl"],
                            "nombre"=>$data["nombre"],
                            "apellido"=>$data["apellido"],
                            "cedula"=>$data["cedula"],
                            "correo"=>$data["correo"],
                            "area"=>$data["Area"]
                        );
                        echo json_encode($list);
                    }                    
                    $response = array(
                        "error"=>"error"
                    );
                }
        break;
    case "buscarEmpleadoBoton":
        if(isset($_POST["cedula"]) && !empty($_POST["cedula"])){
                $data = $cat->buscarEmpleados($_POST["cedula"]);
                    if($data && $data["correo"]!=null){
                        $list[] = array(
                            "id_Empl"=>$data["id_Empl"],
                            "nombre"=>$data["nombre"],
                            "apellido"=>$data["apellido"],
                            "cedula"=>$data["cedula"],
                            "correo"=>$data["correo"],
                            "area"=>$data["Area"]
                        );
                        echo json_encode($list);
                    }                    
                    $response = array(
                        "error"=>"error"
                    );
                }
        break;
    case "RegistrarEmpleado":
        if(!isset($_POST["cedula"]) || empty($_POST["cedula"]) || !isset($_POST["nombre"]) || empty($_POST["nombre"]) 
        || !isset($_POST["apellido"]) || empty($_POST["apellido"]) || !isset($_POST["email"])
        || empty($_POST["email"]) || !isset($_POST["area"]) || empty($_POST["area"])){
            $response = "required";
        }else{
            $cedula = $_POST["cedula"];
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $correo = $_POST["email"];
            $id_Area = $_POST["area"];

            if($cat->VerifiarEmpleados($cedula)){
                $response = "registered";
            }else{
                if($cat->registrarEmpleados($cedula,$nombre,$apellido,$correo,$id_Area)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }
            }        
        }
        echo $response;
    break;
    case "Actualizar_Empleado":
        if(!isset($_POST["cedula"]) || empty($_POST["cedula"]) || !isset($_POST["nombre"]) || empty($_POST["nombre"]) 
        || !isset($_POST["apellido"]) || empty($_POST["apellido"]) || !isset($_POST["email"])
        || empty($_POST["email"]) || !isset($_POST["area"]) || empty($_POST["area"])){
            $response = "required";
        }else{
            $id_Empleado = $_POST["id_Empleado"];
            $cedula = $_POST["cedula"];
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $correo = $_POST["email"];
            $id_Area = $_POST["area"];

                if($cat->ActualizarEmpleados($id_Empleado,$cedula,$nombre,$apellido,$correo,$id_Area)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }        
        }
        echo $response;
    break;
    case "Eliminar_Empleado":
        if(isset($_POST["id_Empl"]) && !empty($_POST["id_Empl"])){
            $id=$_POST["id_Empl"];
            if($cat->EliminarEmpleados($id)){
                $response = "sucess";
            }else{
                $response = "error";
            }
        echo $response;
    }
    break;
    case "Activar_Empleado":
        if(isset($_POST["id_Empl"]) && !empty($_POST["id_Empl"])){
            $id=$_POST["id_Empl"];
            if($cat->ActivarEmpleados($id)){
                $response = "sucess";
            }else{
                $response = "error";
            }
        echo $response;
    }
}

?>