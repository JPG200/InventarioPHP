<?php 
require "../config/Conexion.php";

class regEquipos{
public $cnx;

function __construct(){
    $this->cnx = Conexion::ConectarBD();
}

function ListarregEquipos(){
    $query="SELECT tbreq.id_Reg,tbeq.placa,tbeq.serial,tbreq.descripcion,tbreq.observaciones,tbreq.accesorios,emp.Empresa, tbreq.fecha_creacion,tbreq.fecha_finalizacion,tbreq.estado
     FROM tbequipos tbeq INNER JOIN tbregequip tbreq ON tbeq.id_Equip=tbreq.id_Equip INNER JOIN tbempresas emp ON emp.id_Empresa=tbreq.id_Empresa;";

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


function buscarSerial($placa){
    $estado = 1; //Activo por defecto
    $query="SELECT tbeq.placa, tbeq.serial from tbequipos tbeq where placa=? and estado=?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,$placa, PDO::PARAM_INT);
    $result->bindParam(2,$estado);
    if($result->execute()){
        if($result->rowCount()>0){
            return $result->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
    return false;
}

function buscarEquipo($placa){
    $estado = 1; //Activo por defecto
    $query="SELECT tbreq.id_Reg,tbeq.placa,tbeq.serial,tbreq.descripcion,tbreq.observaciones,tbreq.accesorios,emp.Empresa
    from tbequipos tbeq INNER JOIN tbregequip tbreq ON tbeq.id_Equip=tbreq.id_Equip INNER JOIN tbempresas emp ON emp.id_Empresa=tbreq.id_Empresa
    where tbeq.placa=? and tbeq.estado=?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,$placa , PDO::PARAM_INT);
    $result->bindParam(2,$estado);
    if($result->execute()){
        if($result->rowCount()>0){
            return $result->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
    return false;
}

function LlenarSelectEmpresas(){
    $query="SELECT * from tbempresas;";
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

function RegistrarRegistroEquipo($placa,$descripcion,$observaciones,$accesorios,$Empr){

    $estado = 1; //Activo por defecto
    $fecha_creacion=date( 'Y-m-d H:i:s',time()); //Fecha creacion del equipo
    $Equip=$this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
    #$Empre=$this->buscarEmpresa($Empr); //Buscar el id de la empresa en la tabla de empresas
    if($Equip){
        #echo "el id de la empre es: ".$Empre;
        #echo "El id del equipo es; ".$Equip;
        #$id_Empresa=$Empre['id_Empresa']; //Obtenemos el id de la empresa
        if ($Equip && isset($Equip['id_Equip'])) {
            $id_Equip = $Equip['id_Equip']; // Obtenemos el id del equipo
        } else {
            return false; // Handle the case where $Equip is null or does not contain 'id_Equip'
        }
        $query="INSERT INTO tbregequip(id_Equip,descripcion,observaciones,accesorios,id_Empresa,fecha_creacion,estado) VALUES (?,?,?,?,?,?,?)";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id_Equip);
        $result->bindParam(2,$descripcion);
        $result->bindParam(3,$observaciones);
        $result->bindParam(4,$accesorios);
        $result->bindParam(5,$Empr);
        $result->bindParam(6,$fecha_creacion);
        $result->bindParam(7,$estado);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function buscartablaEquipo($placa){
    $query="SELECT id_Equip from tbequipos where placa=?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,$placa, PDO::PARAM_INT);
    if($result->execute()){
        if($result->rowCount()>0){
            return $result->fetch(mode: PDO::FETCH_ASSOC);
        }
        return false;
    }
    return false;
}


function eliminarRegistroEquipo($id_Reg){
    $fecha_fin=date( 'Y-m-d H:i:s',time());
    $query="UPDATE tbregequip
    set estado = 0, fecha_finalizacion=?  where id_Reg = ?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,$fecha_fin);
    $result->bindParam(2,$id_Reg);
    if($result->execute()){
        return true;
    }else{
        return false;
    }
}

function activarRegEquipo($id_Reg){
    $fecha_finalizacion="0000-00-00";
    $query="UPDATE tbregequip
    set estado = 1,fecha_finalizacion=? WHERE id_Reg = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$fecha_finalizacion);
        $result->bindParam(2,$id_Reg);
    if($result->execute()){
        return true;
    }else{
        return false;
    }
}


/*
function buscarEmpresa($Empr){
    $EmprUper=strtoupper($Empr); //Convertimos a mayusculas el nombre de la empresa
    $query="SELECT id_Empresa from tbempresas where Empresa LIKE ?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,$EmprUper);
    if($result->execute()){
        if($result->rowCount()>0){
            return $result->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
    return false;

}
*/
function ActualizarRegistroEquipo($id,$placa,$descripcion,$observaciones,$accesorios,$Empr){
    $Equip=$this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
    if($Equip){
        $id_Equip=$Equip['id_Equip']; //Obtenemos el id del equipo
        $query="UPDATE tbregequip set id_Equip=?, descripcion=?,observaciones=?,accesorios=?,id_Empresa=? where id_Reg=?";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id_Equip);
        $result->bindParam(2,$descripcion);
        $result->bindParam(3,$observaciones);
        $result->bindParam(4,$accesorios);
        $result->bindParam(5,$Empr);
        $result->bindParam(6,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
    }

function Verificar($placa){
    $Equip=$this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
    $estado = 1; //Activo por defecto
    $id_Equip=$Equip['id_Equip']; //Obtenemos el id del equipo
    $query="SELECT * from tbregequip where id_Equip=? and estado=?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,$id_Equip);
    $result->bindParam(2,$estado);
    if($result->execute()){
        if($result->rowCount()<=0){
            return true;
        }
        return false;
    }
    return false;
}
}
?>