<?php 
session_start();
// Incluir el archivo de conexión a la base de datos
require "../model/Usuario.php";
$usuario = new Usuario();


switch($_REQUEST["operador"]){
    case 'RegistrarUsuario':
        if(isset($_POST["u_nombre"],$_POST["u_apelidos"],$_POST["u_correo"],$_POST["u_contraseña"],$_POST["u_tipo"]) &&
        !empty($_POST["u_nombre"]) && !empty($_POST["u_apelidos"]) && !empty($_POST["u_correo"]) && !empty($_POST["u_contraseña"]) && !empty($_POST["u_tipo"])){
        $u_nombre = $_POST["u_nombre"];
        $u_apelidos = $_POST["u_apelidos"];
        $u_correo = $_POST["u_correo"];
        $u_contraseña = $_POST["u_contraseña"];
        $u_tipo = $_POST["u_tipo"];
        
        if($usuario->RegistrarUsuario($u_nombre,$u_apelidos,$u_correo,$u_contraseña,$u_tipo)){
            $response ="success";
        }else{
            $response ="error";
        }
        } else{
        $response="requerido";
        }
        echo $response;
    break;
    
    case 'ValidarUsuario':
        if(isset($_POST["correo"],$_POST["clave"]) && !empty($_POST["correo"]) && !empty($_POST["clave"]) || !empty($_POST["correo"]) || !empty($_POST["clave"])){
            $u_correo = $_POST["correo"];
            $u_contraseña = $_POST["clave"];
            if($user = $usuario->ValidarUsuario($u_correo,$u_contraseña)){
                foreach($user as $campos => $valor){
                    $_SESSION["user"][$campos] = $valor;
                }
                $response ="success";
            }else{
            $response ="not found";
            }
        }else{
            $response ="requerido";
        }
        echo $response;
    break;

    case 'CerrarSesion':
        unset($_SESSION["user"]);
        session_destroy();
        header("Location: ../index.php");
        break;
}
?>