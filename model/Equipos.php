<?php 
require "../config/Conexion.php";

class Equipos{
    public $cnx;

    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }

    //Listar todos los equipos
    function ListarEquipos(){
        //Consulta para listar todos los equipos
        $query="SELECT * from tbequipos;";

        $result = $this->cnx->prepare($query);

        if($result->execute()){
                if($result->rowCount()>0){
                    while($fila = $result->fetch(PDO::FETCH_ASSOC)){
                        $datos[]=$fila;
                    }
                    return $datos;
                }
        }
        return false;
    }


    //Registrar equipos
    function RegistrarEquipos($placa,$serial){
            $estado = 1; //Activo por defecto
            $fecha_creacion=date( 'Y-m-d H:i:s',time()); //Fecha creacion del equipo
            //Insertar el equipo en la base de datos
            $query="INSERT INTO tbequipos(placa,serial,fecha_creacion,estado) VALUES(?,?,?,?);";

            $result = $this->cnx->prepare($query);
            $result->bindParam(1,$placa);
            $result->bindParam(2,$serial);
            $result->bindParam(3,$fecha_creacion);
            $result->bindParam(4,$estado);

            if($result->execute()){
                return true;
            }else{
                return false;
            }
        
    }

    //Buscar equipo por id
    function buscarEquipo($id){
        //Buscar equipo en la base de datos por id
        $query="SELECT * from tbequipos where id_Equip = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id);
        
        if($result->execute()){
            if($result->rowCount()>0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }
        return false;
    }

    //Actualizar equipo
    function ActualizarEquipos($id,$placa,$serial){
        //Actualizar equipo en la base de datos
        $query="UPDATE tbequipos set placa = ?, serial = ? where id_Equip = ?;";
        
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$placa);
        $result->bindParam(2,$serial);
        $result->bindParam(3,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }

    //Activar equipo
    function ActivarEquipos($id){
        //Activar equipo en la base de datos
        $query="UPDATE tbequipos set estado = 1 where id_Equip = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    //Eliminar equipo (cambiar estado a 0)
    function EliminarEquipos($id){
        //Eliminar equipo en la base de datos
        $query="UPDATE tbequipos set estado = 0 where id_Equip = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }

    //Verificar si el equipo ya existe por placa
    function Verificar($placa){
        //Buscar equipo en la base de datos por placa
        $query="SELECT * from tbequipos where placa = ? and estado = 1;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$placa);
        if($result->execute()){
            if($result->rowCount()<=0){
                return true; //No existe el equipo o esta inactivo
            }
            return false; //El equipo ya existe o esta activo
        }
    }
}
?>