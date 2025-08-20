<?php 
require "../config/Conexion.php";

class regEquipos{
public $cnx;

function __construct(){
    $this->cnx = Conexion::ConectarBD();
}


//Listar todos los registros de equipos
function ListarregEquipos(){
    //Consulta para listar todos los equipos
    
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

//Buscar serial por placa
function buscarSerial($placa){
    $estado = 1; //Activo por defecto
    //Consulta para buscar el serial por placa
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


//Buscar equipo registrado por placa
function buscarEquipo($placa){
    $estado = 1; //Activo por defecto
    //Consulta para buscar el equipo registrado por placa
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

//Llenar select de empresas
function LlenarSelectEmpresas(){
    //Consulta para llenar el select de empresas
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

//Registrar registro de equipo
function RegistrarRegistroEquipo($placa,$descripcion,$observaciones,$accesorios,$Empr){

    $estado = 1; //Activo por defecto
    $fecha_creacion=date( 'Y-m-d H:i:s',time()); //Fecha creacion del equipo
    $Equip=$this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
    if($Equip){
        
        if ($Equip && isset($Equip['id_Equip'])) {
            $id_Equip = $Equip['id_Equip']; // Obtenemos el id del equipo
        } else {
            return false; // Handle the case where $Equip is null or does not contain 'id_Equip'
        }
        //Insertar el registro del equipo en la base de datos
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

//Buscar el id del equipo en la tabla de equipos
function buscartablaEquipo($placa){
    //Consulta para buscar el id del equipo en la tabla de equipos
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
    //Actualizar el estado del registro a inactivo y la fecha de finalizacion
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

//Activar registro de equipo
function activarRegEquipo($id_Reg){
    $fecha_finalizacion="0000-00-00";
    //Actualizar el estado del registro a activo y la fecha de finalizacion a 0000-00-00
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

//Actualizar registro de equipo
function ActualizarRegistroEquipo($id,$placa,$descripcion,$observaciones,$accesorios,$Empr){
    $Equip=$this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
    if($Equip){
        $id_Equip=$Equip['id_Equip']; //Obtenemos el id del equipo
        //Actualizar el registro del equipo en la base de datos
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

    //Verificar si el equipo ya tiene un registro activo
function Verificar($placa){
    $Equip=$this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
    $estado = 1; //Activo por defecto
    $id_Equip=$Equip['id_Equip']; //Obtenemos el id del equipo
    //Consulta para verificar si el equipo ya tiene un registro activo
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