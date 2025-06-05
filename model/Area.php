<?php 
require "../config/Conexion.php";

class Equipos{
    public $cnx;

    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }

    function ListarArea(){
        $query="SELECT * from tbarea;";

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
    function CrearArea($Area,$centro_costos){
        $estado = 1; //Activo por defecto
        $fecha_fin = null; //Fecha de terminacion por defecto
        $fecha_creacion=date( 'Y-m-d H:i:s',time()); //Fecha creacion del equipo
        $query = "INSERT INTO tbarea (Area,centro_costos, fecha_fin,fecha_creacion, estado) VALUES (?,?,?,?,?)";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $Area);
        $result->bindParam(2, $centro_costos);
        $result->bindParam(3, $fecha_fin);
        $result->bindParam(4, $fecha_creacion);
        $result->bindParam(5, $estado);
        
        if($result->execute()){
            return true;
        } else {
            return false;
        }
}
function VerificarArea($centro_costos){
    $query = "SELECT * FROM tbarea WHERE centro_costos = ? AND estado = 1";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1, $centro_costos);
    
    if($result->execute()){
        if($result->rowCount()>0){
            return false; // Area ya existe
        }
    }
    return true; // Area no existe
}
    function buscarArea($id){
        $query="SELECT * from tbarea where id_Area = ?;";
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
 function ActivarArea($id){
        $query="UPDATE tbarea set estado = 1 where id_Area = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    function EliminarArea($id){
        $query="UPDATE tbarea set estado = 0 where id_Area = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }
    function ActualizarArea($id,$Area,$centro_costos){
        $query="UPDATE tbarea set Area = ?, centro_costos = ? where id_Area = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$Area);
        $result->bindParam(2,$centro_costos);
        $result->bindParam(3,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }
}
?>