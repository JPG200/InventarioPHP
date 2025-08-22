<?php
require "../config/Conexion.php";
class Usuario{
    public $cnx;
    function __construct(){
        $this->cnx=Conexion::ConectarBD();
    }


    //Registrar Usuario
    function RegistrarUsuario($u_nombre,$u_apelidos,$u_correo,$u_contraseña,$u_tipo): bool{
        $estado=1; // Estado activo
        $fecha_creacion=date( 'Y-m-d H:i:s',time()); //Fecha creacion del usuario
        //Insertar el usuario en la base de datos
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
        //Validar Usuario
    function ValidarUsuario($u_correo,$u_contraseña){
        //Consulta para validar el usuario
        $query = "SELECT tbusuarios.correo,tbusuarios.contraseña, tbusuarios.nombre,tbusuarios.apellidos, tbtipou.tipo_usuario 
        FROM tbusuarios INNER JOIN tbtipou
        ON tbusuarios.id_tipoU = tbtipou.id_tipoU
        WHERE tbusuarios.correo=? AND tbusuarios.contraseña=? AND tbusuarios.estado=1";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$u_correo);
        $result->bindParam(2,$u_contraseña);

        $result->execute();
        if($result->rowCount()>0){
            $row=$result->fetch(PDO::FETCH_ASSOC);
                return $row;
        }
        return false;
    }
}
?>