<?php 
require "../config/Conexion.php";

class regEquipos{
public $cnx;

function __construct(){
    $this->cnx = Conexion::ConectarBD();
}

function ListarregEquipos(){
    $query="SELECT tbreq.id_Reg,tbeq.placa,tbeq.serial,tbreq.descripcion,tbreq.observaciones,tbreq.accesorios,tbreq.empresa, tbreq.fecha_creacion,tbreq.estado
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

function buscarEquipo($placa){
    $estado = 1; //Activo por defecto
    $query="SELECT tbeq.placa,tbeq.serial,tbreq.descripcion,tbreq.observaciones,tbreq.accesorios,tbreq.empresa
    from tbequipos tbeq INNER JOIN tbregequip tbreq 
    ON tbeq.id_Equip=tbreq.id_Equip where tbeq.placa=? and tbeq.estado=?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,$placa);
    $result->bindParam(2,$estado);
    if($result->execute()){
        if($result->rowCount()>0){
            return $result->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
    return false;
}

function RegistrarRegistroEquipo($id_Equip,$descripcion,$observaciones,$accesorios,$empresa){

    $estado = 1; //Activo por defecto
    $fecha_creacion=date( 'Y-m-d H:i:s',time()); //Fecha creacion del equipo
    $query="INSERT INTO tbregequip(id_Equip,descripcion,observaciones,accesorios,empresa,fecha_creacion,estado) VALUES (?,?,?,?,?,?,?)";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,$id_Equip);
    $result->bindParam(2,$descripcion);
    $result->bindParam(3,$observaciones);
    $result->bindParam(4,$accesorios);
    $result->bindParam(5,$empresa);
    $result->bindParam(6,$fecha_creacion);
    $result->bindParam(7,$estado);

    if($result->execute()){
        return true;
    }else{
        return false;
    }
}
}
?>