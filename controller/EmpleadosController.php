<?php
require "../model/Empleados.php";


$cat = new Empleados();

switch($_REQUEST["operador"]){
    case "listar_Empleados":
        try{
        $datos = $cat->listarEmpleados();
        if(is_array(value: $datos)){
            // Caso para listar empleados
            for($i=0;$i<count($datos);$i++){
                // Create an array to hold the list of employees
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]["id_Empl"],
                    "Cedula"=> $datos[$i]['cedula'],
                    "Nombre"=> $datos[$i]['nombre'],
                    "Apellido"=> $datos[$i]['apellido'],
                    "Email"=> $datos[$i]['correo'],
                    "Cargo"=> $datos[$i]['Cargo'],
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
            // Prepare the result to be returned as JSON
            // The result includes the total number of records and the data itself
            $resultador = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($list),
                "iTotalDisplayRecords"=>count($list),
                "aaData"=>$list
            );
            } else{
                // If no data is found, prepare an empty response
                $list[]=array(
                    "Numero de Registro"=>"",
                    "Nombre"=> "",
                    "Apellido"=> "",
                    "Area"=> "",
                    "Estado"=> "",
                    "op"=> ""
                    );
                    // Prepare the result to be returned as JSON
                    // The result includes the total number of records and the data itself
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
                "Nombre"=> "ERROR",
                "Apellido"=> "ERROR",
                "Area"=> "ERROR",
                "Estado"=> "ERROR",
                "op"=> "ERROR"
                );
                // Prepare the result to be returned as JSON
                // The result includes the total number of records and the data itself
                $resultador = array(
                    "sEcho"=>1,
                    "iTotalRecords"=>count($list),
                    "iTotalDisplayRecords"=>count($list),
                    "aaData"=>$list
                );
            }
        // Return the result as a JSON response
        echo json_encode($resultador);

    break;
    case"LlenarSelectArea":
        // This case is for filling the area select dropdown
        $data = $cat->LlenarSelectArea();
        if($data){
            // Create an array to hold the list of areas
            for($i=0;$i<count($data);$i++){
                // Create an array to hold the list of areas
                $list[]=array(
                "id_Area"=>$data[$i]["id_Area"],
                "Area"=>$data[$i]['Area']
            );
            }
            // Prepare the result to be returned as JSON
            // The result includes the total number of records and the data itself
            echo json_encode($list);
        }else{
            $response = array(
            "error"=>"error"
            );
        }
    break;
        case "buscarEmpleado":
            // This case is for searching an employee by ID
        if(isset($_POST["id_Empl"]) && !empty($_POST["id_Empl"])){
            // Check if the ID is set and not empty
            // If the ID is set, proceed to search for the employee
                // Call the buscarEmpleadosId method to get employee data
                $data = $cat->buscarEmpleadosId($_POST["id_Empl"]);
                    if($data && $data["correo"]!=null){
                        // If employee data is found, create an array to hold the employee details
                        // Create an array to hold the list of employees
                        $list[] = array(
                            "id_Empl"=>$data["id_Empl"],
                            "nombre"=>$data["nombre"],
                            "apellido"=>$data["apellido"],
                            "cedula"=>$data["cedula"],
                            "correo"=>$data["correo"],
                            "area"=>$data["Area"]
                        );
                        // Return the list of employees as a JSON response
                        // The list contains the employee details
                        echo json_encode($list);
                    }                    
                    $response = array(
                        "error"=>"error"
                    );
                }
        break;
    case "buscarEmpleadoBoton":
        // This case is for searching an employee by cedula
        // Check if the cedula is set and not empty
        // If the cedula is set, proceed to search for the employee
        if(isset($_POST["cedula"]) && !empty($_POST["cedula"])){
            // Call the buscarEmpleados method to get employee data
            // If the cedula is set, proceed to search for the employee
                $data = $cat->buscarEmpleados($_POST["cedula"]);
                // Check if the data is not empty and the email is not null
                    if($data && $data["correo"]!=null){
                        // If employee data is found, create an array to hold the employee details
                        // Create an array to hold the list of employees
                        $list[] = array(
                            "id_Empl"=>$data["id_Empl"],
                            "nombre"=>$data["nombre"],
                            "apellido"=>$data["apellido"],
                            "cedula"=>$data["cedula"],
                            "correo"=>$data["correo"],
                            "area"=>$data["Area"]
                        );
                        // Return the list of employees as a JSON response
                        // The list contains the employee details
                        echo json_encode($list);
                    }                    
                    $response = array(
                        "error"=>"error"
                    );
                }
        break;
    case "RegistrarEmpleado":
        // This case is for registering a new employee
        // Check if the required fields are set and not empty
        // If any of the required fields are missing, return a "required" response
        if(!isset($_POST["cedula"]) || empty($_POST["cedula"]) || !isset($_POST["nombre"]) || empty($_POST["nombre"]) 
        || !isset($_POST["apellido"]) || empty($_POST["apellido"]) || !isset($_POST["email"])
        || empty($_POST["email"]) || !isset($_POST["area"]) || empty($_POST["area"]) || !isset($_POST["cargo"]) || empty($_POST["cargo"])){
            $response = "required";
        }else{
            // If all required fields are present, proceed to register the employee
            // Get the employee details from the POST request
            // Check if the employee already exists using the VerifiarEmpleados method
            $cedula = $_POST["cedula"];
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $correo = $_POST["email"];
            $id_Area = $_POST["area"];
            $cargo= $_POST["cargo"];
            // If the employee already exists, return a "registered" response
            // If the employee does not exist, proceed to register the employee using the registrarEmpleados method
            // If the registration is successful, return a "success" response; otherwise, return an "error" response
            if($cat->VerifiarEmpleados($cedula)){
                $response = "registered";
            }else{
                // If the employee does not exist, proceed to register the employee
                // Call the registrarEmpleados method to register the employee
                // If the registration is successful, return a "success" response; otherwise, return an "error" response
                if($cat->registrarEmpleados($cedula,$nombre,$apellido,$correo,$id_Area,$cargo)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }
            }        
        }
        // Return the response
        echo $response;
    break;
    case "Actualizar_Empleado":
        // This case is for updating an existing employee
        // Check if the required fields are set and not empty
        // If any of the required fields are missing, return a "required" response
        if(!isset($_POST["cedula"]) || empty($_POST["cedula"]) || !isset($_POST["nombre"]) || empty($_POST["nombre"]) 
        || !isset($_POST["apellido"]) || empty($_POST["apellido"]) || !isset($_POST["email"])
        || empty($_POST["email"]) || !isset($_POST["area"]) || empty($_POST["area"]) || !isset($_POST["cargo"]) || empty($_POST["cargo"])){
            $response = "required";
        }else{
            // If all required fields are present, proceed to update the employee
            // Get the employee details from the POST request
            // Check if the employee ID is set and not empty
            // If the ID is set, proceed to update the employee
            $id_Empleado = $_POST["id_Empleado"];
            $cedula = $_POST["cedula"];
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $correo = $_POST["email"];
            $id_Area = $_POST["area"];
            $cargo= $_POST["cargo"];
            // Check if the employee ID is set and not empty
            // If the ID is set, proceed to update the employee
                if($cat->ActualizarEmpleados($id_Empleado,$cedula,$nombre,$apellido,$correo,$id_Area,$cargo)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }        
        }
        // Return the response
        echo $response;
    break;
    case "Eliminar_Empleado":
        // This case is for deleting an employee
        // Check if the employee ID is set and not empty
        // If the ID is set, proceed to delete the employee
        if(isset($_POST["id_Empl"]) && !empty($_POST["id_Empl"])){
            // Call the EliminarEmpleados method to delete the employee
            // If the ID is set, proceed to delete the employee
            $id=$_POST["id_Empl"];
            // If the deletion is successful, return a "success" response; otherwise, return an "error" response
            // If the employee is deleted successfully, return a "success" response; otherwise, return an "error" response
            if($cat->EliminarEmpleados($id)){
                // If the deletion is successful, return a "success" response; otherwise, return an "error" response

                $response = "sucess";
            }else{
                $response = "error";
            }
            // Return the response
        echo $response;
    }
    break;
    case "Activar_Empleado":
        // This case is for activating an employee
        // Check if the employee ID is set and not empty
        // If the ID is set, proceed to activate the employee
        // Call the ActivarEmpleados method to activate the employee
        // If the ID is set, proceed to activate the employee
        if(isset($_POST["id_Empl"]) && !empty($_POST["id_Empl"])){
            // Check if the ID is set and not empty
            $id=$_POST["id_Empl"];
            // If the activation is successful, return a "success" response; otherwise, return an "error" response
            
            if($cat->ActivarEmpleados($id)){
                $response = "sucess";
            }else{
                $response = "error";
            }
            // Return the response
        echo $response;
    }
}

?>