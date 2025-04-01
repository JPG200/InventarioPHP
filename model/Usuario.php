<?php
require "../config/Conexion.php";
class Usuario{
    public $cnx;
    function __construct(){
        $this->cnx=Conexion::ConectarBD();
    }

    function RegistrarUsuario($u_nombre,$u_apelidos,$u_correo,$u_contraseña,$u_tipo): bool{
        $estado=1; // Estado activo
        $fecha_creacion=date( 'Y-m-d H:i:s',time()); //Fecha creacion del usuario
        $u_contraseña= password_hash($u_contraseña, PASSWORD_DEFAULT); // Encriptar la contraseña
        $query = "INSERT INTO tbusuarios (nombre, apellidos, correo, contraseña, id_tipoU,estado,fecha_creacion) VALUES (?,?,?,?,?,?,?)";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$u_nombre);
        $result->bindParam(2,$u_apelidos);
        $result->bindParam(3,$u_correo);
        $result->bindParam(4,$u_contraseña);
        $result->bindParam(5,$u_tipo);
        $result->bindParam(6,$estado);
        $result->bindParam(7,$fecha_creacion); 

        if($result->execute()){
            return true;
        }
        return false;
    }

    function ValidarUsuario($u_correo,$u_contraseña): bool{
        $query = "SELECT * FROM tbusuarios WHERE correo=? AND estado=1";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$u_correo);
        $result->execute();
        if($result->rowCount()>0){
            $row=$result->fetch(PDO::FETCH_ASSOC);
            if(password_verify($u_contraseña,$row['contraseña'])){
                return true;
            }
        }
        return false;
    }
}
?>