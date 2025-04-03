<?php 
require "../config/Conexion.php";

class regEquipos{
public $cnx;

function __construct(){
    $this->cnx = Conexion::ConectarBD();
}

function ListarregEquipos(){
    $query="SELECT tbeq.id_Equip,tbeq.placa,tbeq.serial,tbreq.descripcion,tbreq.observaciones,tbreq.accesorios,tbreq.empresa, tbreq.fecha_creacion,tbreq.estado
     FROM tbequipos tbeq INNER JOIN tbregequip tbreq ON tbeq.id_Equip=tbreq.id_Equip WHERE tbeq.estado=1;";

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
}
?>