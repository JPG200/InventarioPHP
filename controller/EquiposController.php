<?php
require "../model/Equipos.php";
$cat = new Equipos();

switch($_REQUEST["operador"]){
    case "listar_categorias":  
    
        $datos = $cat->ListarEquipos();
        // Verificamos si $datos es un array
        if(is_array(value: $datos)){
            for($i=0;$i<count($datos);$i++){
                // Creamos un array con los datos del equipo
                // y lo agregamos a la lista $list  
                $list[]=array(
                    "Numero de Registro"=>$datos[$i]["id_Equip"],
                    "Placa"=> $datos[$i]['placa'],
                    "Serial"=> $datos[$i]['serial'],
                    "Fecha de Ingreso"=> $datos[$i]['fecha_creacion'],
                    "Estado"=> $datos[$i]['estado']==1?'<div class="tag tag-success">Activo</div>':
                                                        '<div class="tag tag-danger">Inactivo</div>',
                    "op"=> ($datos[$i]['estado'])==1?'<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#updateEquipo"
                                     onclick="BuscarEquipo('.$datos[$i]['id_Equip'].",'editar'".');">
                                     <i class="icon-pencil"></i> Editar</a>
                                    <a class="dropdown-item" onclick="BuscarEquipo('.$datos[$i]['id_Equip'].",'eliminar'".');">
                                    <i class="icon-trash"></i> Eliminar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>':'
                            <div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="icon-gear"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="BuscarEquipo('.$datos[$i]['id_Equip'].",'activar'".');"><i class="icon-check"></i> Activar</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>'
                );
            }
            // Preparamos el resultado para enviar como JSON
            // sEcho es un parámetro utilizado por DataTables para la paginación y ordenamiento
            // iTotalRecords es el número total de registros sin filtrar
            // iTotalDisplayRecords es el número total de registros después de aplicar los filtros
            // aaData contiene los datos que se mostrarán en la tabla
            $resultador = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($list),
                "iTotalDisplayRecords"=>count($list),
                "aaData"=>$list
            );
        }else{
            // Si $datos no es un array, devolvemos un resultado vacío
            // Esto puede ocurrir si no hay equipos registrados o si hubo un error al obtener los datos
            // En este caso, devolvemos un array vacío con los parámetros necesarios para DataTables
            $resultador = array(
                "sEcho"=>1,
                "iTotalRecords"=>0,
                "iTotalDisplayRecords"=>0,
                "aaData"=>array()
            );
        }
        // Enviamos el resultado como JSON
        echo json_encode($resultador);

    break;
    case "registrar_equipo":
        // Verificamos que los campos requeridos estén presentes y no vacíos
        // Si están presentes, procedemos a registrar el equipo
        // Si el registro es exitoso, devolvemos "sucess"; de lo contrario, "error"
        if(isset($_POST["placa"]) && isset($_POST["serial"]) 
        && !empty($_POST["placa"] && $_POST["serial"])){
            // Obtenemos los datos del formulario
            // y verificamos si la placa ya está registrada
            // Si no está registrada, procedemos a registrar el equipo
            // Si el registro es exitoso, devolvemos "sucess"; de lo contrario, "error"
            $placa = $_POST["placa"];
            $serial = $_POST["serial"];
            // Verificamos si la placa ya está registrada
            if($cat->Verificar($placa)){
                // Si la placa no está registrada, procedemos a registrar el equipo
                if($cat->RegistrarEquipos($placa,$serial)){
                    // Si el registro es exitoso, devolvemos "sucess"; de lo contrario, "error"
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
        // Devolvemos la respuesta
        echo $response;
     break;

    case "buscar_equipo":
        // Verificamos si el ID del equipo está presente y no está vacío
        // Si está presente, buscamos el equipo por su ID
        // Si se encuentra, devolvemos los datos del equipo; de lo contrario, devolvemos un error

        if(isset($_POST["id_Equip"]) && !empty($_POST["id_Equip"])){
            // Creamos un array para almacenar los datos del equipo
        $data = $cat->buscarEquipo($_POST["id_Equip"]);
            if($data){
                // Si se encuentra el equipo, agregamos sus datos al array $list
                $list[] = array(
                    "Numero de Registro"=>$data["id_Equip"],
                    "placa"=>$data["placa"],
                    "serial"=>$data["serial"],
                    "estado"=>$data["estado"]
                );
                // Enviamos la lista como respuesta en formato JSON
                echo json_encode($list);
            }else{
                $response = array(
                    "error"=>"error"
                );
            }

        }
        break;

        case "Actualizar_Equipos":
            // Verificamos que los campos requeridos estén presentes y no vacíos
            // Si están presentes, procedemos a actualizar el equipo
            // Si la actualización es exitosa, devolvemos "sucess"; de lo contrario, "error"
            // Si los campos no están presentes o están vacíos, devolvemos "required"
            if(isset($_POST["placa"],$_POST["serial"])
             && !empty($_POST["placa"]) && !empty($_POST["serial"])){
            // Obtenemos los datos del formulario
            // y verificamos que el ID del equipo esté presente
            // Si está presente, procedemos a actualizar el equipo
            // Si la actualización es exitosa, devolvemos "sucess"; de lo contrario, "error"
                $id=$_POST["id_Equip"];
                $placa=$_POST["placa"];
                $serial = $_POST["serial"];
                // Verificamos que el ID del equipo esté presente
                if($cat->ActualizarEquipos($id,$placa,$serial)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }
            }else{
                $response = "required";
            }
            // Devolvemos la respuesta
            echo $response;
        break;

        case "Eliminar_Equipos":
            // Verificamos si el ID del equipo está presente y no está vacío
            if(isset($_POST["id_Equip"]) && !empty($_POST["id_Equip"])){
                // Si el ID está presente, procedemos a eliminar el equipo
                // Si la eliminación es exitosa, devolvemos "sucess"; de lo contrario, "error"
                // Si el ID no está presente o está vacío, devolvemos "required"
                $id=$_POST["id_Equip"];
                // Si el ID está presente, procedemos a eliminar el equipo
                if($cat->EliminarEquipos($id)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }
            }else{
                $response = "required";
            }
            // Devolvemos la respuesta
            echo $response;
        break;
        case "Activar_Equipos":
            // Verificamos si el ID del equipo está presente y no está vacío
            // Si está presente, procedemos a activar el equipo
            // Si la activación es exitosa, devolvemos "sucess"; de lo contrario, "error"
            // Si el ID no está presente o está vacío, devolvemos "required"
            if(isset($_POST["id_Equip"]) && !empty($_POST["id_Equip"])){
                // Si el ID está presente, procedemos a activar el equipo
                $id=$_POST["id_Equip"];
                // Verificamos si el equipo se puede activar
                if($cat->ActivarEquipos($id)){
                    $response = "sucess";
                }else{
                    $response = "error";
                }
            }else{
                $response = "required";
            }
            // Devolvemos la respuesta
            echo $response;
}

?>