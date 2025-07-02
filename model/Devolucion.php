<?php
require "../config/Conexion.php";
class Devolucion{
 public $cnx;
    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }
    function listarDevolucion(){
        /* Numero de Registro, Acta de Asignacion, Empleado, Placa, Empresa, Fecha de Devolucion, Fecha de Entrega, Acta de Devolucion, Estado, op*/
        $query = "SELECT tbd.id_Dev,ta.Acta ,CONCAT(tbem.nombre,' ',tbem.apellido) AS 'Empleado',tbe.Placa ,tbemp.Empresa ,
                         ta.fecha_fin ,tbd.fecha_retorno, tbd.Acta AS 'Acta Devolucion', tbd.estado
					from tbdevolucion tbd INNER JOIN tbasignado ta ON tbd.id_Asig=ta.id_Asig
                    INNER JOIN tbregequip tbr ON ta.id_Eq=tbr.id_Equip 
                    INNER JOIN tbempleado tbem ON ta.id_Empl=tbem.id_Empl
                    INNER JOIN tbempresas tbemp ON tbr.id_Empresa=tbemp.id_Empresa 
                    INNER JOIN tbarea tba ON tbem.id_Area=tba.id_Area 
                    INNER JOIN tbequipos tbe ON tbe.id_Equip=tbr.id_Equip;";

        $result = $this->cnx->prepare($query);

        if($result->execute()){
            if($result->rowCount() > 0){
                while($fila = $result->fetch(PDO::FETCH_ASSOC)){
                    $datos[] = $fila;
                }
                return $datos;
            }
        }
        return false;
    }

    function RegistrarDevolucion($acta_asig,$observaciones,$acta){
        try{
        //Verificar si existe una devolucion activa para esta acta de asignacion
        if(!$this->VerificarDevolucion($acta)){
            return false; //Ya existe una devolucion activa para esta acta de devolucion
        }
        //Verificar si existe una asignacion activa para esta acta de asignacion
        if(!$this->VerificarAsignacion($acta_asig)){
            return false; //Ya existe una asignacion inactiva para esta acta de asignacion
        }

        $Asig=$this->buscartablaAsignacion($acta_asig);
        $id_Equip = $Asig['id_Equip'];
        $id_Asig = $Asig['id_Asig'];
        $descripcion = $Asig['descripcion'];
        $id_Empresa = $this->buscartablaRegEquipo($id_Equip)['id_Empresa'];
        $fecha_dev= date( 'Y-m-d H:i:s',time());
        $fecha_retorno= "0000-00-00";
        $estado=1; //Activo por defecto
        $query = "INSERT INTO tbdevolucion(id_Equip, id_Asig,descipcion, id_Empresa, 
                fecha_dev, fecha_retorno, observaciones, acta, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $id_Equip);
        $result->bindParam(2, $id_Asig);
        $result->bindParam(3, $descripcion);
        $result->bindParam(4, $id_Empresa);
        $result->bindParam(5, $fecha_dev);
        $result->bindParam(6, $fecha_retorno);
        $result->bindParam(7, $observaciones);
        $result->bindParam(8, $acta);
        $result->bindParam(9, $estado);

        
        if($result->execute()){
            return true;
        }
        return false;
}catch(Exception $e){
    $e="Error";
    return $e; //Error al registrar la devolucion
}
}

function BuscarInformacion($acta_asig){
    //Buscar informacion de la asignacion activa
    //Si no existe una asignacion activa, retornar false
    if(!$this->VerificarExistenciaAsignacion($acta_asig)){
        return false; //No existe una asignacion activa para esta acta de asignacion
    }

    $Asig=$this->buscartablaAsignacion($acta_asig);
    $id_Asig = $Asig['id_Asig'];

    if(!$this->VerificarAsigenDevolucion($id_Asig)){
        return false; //No existe una asignacion activa para esta acta de asignacion
    }

    $query=" SELECT tbd.id_Dev,tbd.observaciones,tba.descripcion,tbd.acta AS 'ActaDev',tbe.placa,tba.acta AS 'ActaAsig' FROM tbdevolucion tbd 
    RIGHT JOIN tbasignado tba ON tbd.id_Asig=tba.id_Asig 
    INNER JOIN tbequipos tbe ON tba.id_Eq=tbe.id_Equip 
    WHERE tba.id_Asig=4 AND tba.estado=1;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1, $id_Asig);
    if($result->execute()){
        if($result->rowCount() > 0){
            return $result->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
    return false;
}

    function buscartablaAsignacion($acta){
        $query="SELECT id_Eq,id_Asig,descripcion from tbasignado where acta=? and estado=1;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$acta);
        if($result->execute()){
            if($result->rowCount()>0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }
        return false;
    }

    function buscartablaRegEquipo($id_Equip){
        $query="SELECT id_Empresa from tbregequip where id_Equip=?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id_Equip);
        if($result->execute()){
            if($result->rowCount()>0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }
        return false;
    }
    
    function VerificarAsigenDevolucion($id_Asig){
        $query = "SELECT * FROM tbdevolucion WHERE id_Asig=? and estado=1;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $id_Asig);
        if($result->execute()){
            if($result->rowCount() > 0){
                return false; //Ya existe una devolucion activa para esta acta de devolucion
            }
        }
        return true; //No existe acta de devolucion
    }

        function VerificarDevolucion($acta_dev){
        $query = "SELECT * FROM tbdevolucion WHERE acta=?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $acta_dev);
        if($result->execute()){
            if($result->rowCount() > 0){
                return false; //Ya existe una devolucion activa para esta acta de devolucion
            }
        }
        return true; //No existe acta de devolucion
    }

    function VerificarAsignacion($acta_asig){
        $query = "SELECT * FROM tbasignado WHERE acta=? AND estado=1;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $acta_asig);
        if($result->execute()){
            if($result->rowCount() > 0){
                return true; //Ya existe una devolucion activa para esta asignacion
            }
        }
        return false; //No existe devolucion activa
    }
    function VerificarExistenciaAsignacion($acta_asig){
        $query = "SELECT * FROM tbasignado WHERE acta=? AND estado=1;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $acta_asig);
        if($result->execute()){
            if($result->rowCount() > 0){
                return true; //Ya existe una devolucion activa para esta asignacion
            }
        }
        return false; //No existe devolucion activa
    }

}
?>