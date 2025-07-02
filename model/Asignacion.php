<?php
require "../config/Conexion.php";
class Asignacion{

 public $cnx;
    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }

    function BuscarId($id_Asig){
        $query = "SELECT tba.id_Asig,tbe.Placa FROM tbasignado tba INNER JOIN tbequipos tbe ON tbe.id_Equip=tba.id_Eq Where id_Asig=?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id_Asig);
        if($result->execute()){
            if($result->rowCount() > 0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        }
        return false;
    }

    function buscarAsignacion($placa){
        $Equip = $this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
        if(!$Equip){
            return false; //Si no se encuentra el equipo, retornar false
        }
        $id_Equip = $Equip['id_Equip']; //Obtener el id del equipo  
        $query="SELECT tbr.descripcion,tbr.observaciones, (Select CONCAT(tbe.nombre,' ',tbe.apellido) From tbempleado tbe Where tbe.id_Empl=(Select Id_Empl FROM tbasignado ta WHERE id_Eq=?) AND tbe.estado=1) as Empleado,
        (SELECT ta.Acta FROM tbasignado ta WHERE ta.id_Eq=? AND ta.estado=1) as Acta,(SELECT ta.id_Asig FROM tbasignado ta WHERE ta.id_Eq=? AND ta.estado=1) AS id_Asig FROM tbregequip tbr WHERE tbr.id_Equip=? AND tbr.estado=1;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id_Equip);
        $result->bindParam(2,$id_Equip);
        $result->bindParam(3,$id_Equip);
        $result->bindParam(4,$id_Equip);
        
        if($result->execute()){
            if($result->rowCount()>0){
                return $result->fetch(PDO::FETCH_ASSOC);  
            }
            return false;
        }
        return false;
    }

    
    function listarAsignacion(){
        /* Placa, Empleado,Area,Acta,Empresa,Fecha Ingreso,Fecha Devolucion, OP*/
        $query = "SELECT ta.id_Asig ,tbe.Placa ,CONCAT(tbem.nombre,' ',tbem.apellido) AS 'Empleado',tba.Area ,
                    ta.Acta,tbemp.Empresa, ta.fecha_inicio ,ta.fecha_fin, ta.estado
					from tbasignado ta 
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
    
function LlenarSelectEmpleadoUpdate(){
    $estado = 1; //Activo por defecto
    $query="SELECT tbe.id_Empl, CONCAT(tbe.nombre,' ',tbe.apellido) AS 'Empleado' 
    from tbempleado tbe
    WHERE tbe.estado=?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,var: $estado);

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
function LlenarSelectEmpleado(){
    $estado = 1; //Activo por defecto
    $query="SELECT tbe.id_Empl, CONCAT(tbe.nombre,' ',tbe.apellido) AS 'Empleado' from tbempleado tbe LEFT JOIN tbasignado ta ON tbe.id_Empl=ta.id_Empl
     WHERE tbe.estado=? OR ta.Id_Empl NOT IN (SELECT tas.id_Empl FROM tbasignado tas WHERE tas.estado=?) AND ta.id_Empl IS NULL;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1,var: $estado);
    $result->bindParam(2,var: $estado);

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

    function RegistrarAsignacion($id_Empl,$placa,$observaciones,$descripcion,$acta){       
        $estado = 1; //Activo por defecto
        $fecha_finalizacion="0000-00-00";
        $fecha_creacion=date( 'Y-m-d H:i:s',time()); //Fecha creacion del equipo
        $Equip = $this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
        if(!$Equip){
            return false; //Si no se encuentra el equipo, retornar false
        }
        if($Equip!=false && isset($Equip['id_Equip'])){
            $id_Equip = $Equip['id_Equip']; //Obtener el id del equipo
            $query = "INSERT INTO tbasignado(id_Empl,id_Eq,observaciones,descripcion,acta,fecha_inicio,estado,fecha_fin) 
            VALUES(?,?,?,?,?,?,?,?)";
            $result = $this->cnx->prepare($query);
            $result->bindParam(1,$id_Empl);
            $result->bindParam(2,$id_Equip);
            $result->bindParam(3,$observaciones);
            $result->bindParam(4,$descripcion);
            $result->bindParam(5,$acta);
            $result->bindParam(6,$fecha_creacion);
            $result->bindParam(7,$estado);
            $result->bindParam(8,$fecha_finalizacion);
            if($result->execute()){
                if($result->rowCount() > 0){
                    return true;
                }
            }
        }else{
            return false; //Si no se encuentra el equipo, retornar false}
        }
    }

    function Verificar($id_Empl,$placa){
        $Equip = $this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
        if(!$Equip){
            return false; //Si no se encuentra el equipo, retornar false
        }
        if($Equip && isset($Equip['id_Equip'])){
            $id_Equip = $Equip['id_Equip']; //Obtener el id del equipo
            $estado=1;
            $query = "SELECT * FROM tbasignado WHERE id_Empl = ? AND estado = ? OR id_Eq = ? ";
            $result = $this->cnx->prepare($query);
            $result->bindParam(1,$id_Empl);
            $result->bindParam(2,$id_Equip);
            $result->bindParam(3,$estado);
            if($result->execute()){
                if($result->rowCount() > 0){
                    return false; //Asignacion ya existe
                }
            }
        }
        return true; //Asignacion no existe
    }
    function buscartablaEquipo($placa){
        $query="SELECT id_Equip from tbequipos where placa=?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$placa);
        if($result->execute()){
            if($result->rowCount()>0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }
        return false;
    }
    
    function EliminarAsignacion($id_Asig){
        $estado = 0; //Inactivo por defecto
        $fecha_finalizacion=date( 'Y-m-d H:i:s',time());
        $query = "UPDATE tbasignado SET estado=?,fecha_fin=? WHERE id_Asig=?";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$estado);
        $result->bindParam(2,$fecha_finalizacion);
        $result->bindParam(3,$id_Asig);
        if($result->execute()){
            if($result->rowCount() > 0){
                return true;
            }
        }
        return false;

    }

    function ActualizarAsignacion($id_Asig,$id_Empl,$placa,$observaciones,$descripcion,$acta){
        $estado = 1; //Activo por defecto
        $fecha_finalizacion="0000-00-00";
        $Equip = $this->buscartablaEquipo($placa); //Buscar el id del equipo en la tabla de equipos
        if(!$Equip){
            return false; //Si no se encuentra el equipo, retornar false
        }
        if($Equip!=false && isset($Equip['id_Equip'])){
            $id_Equip = $Equip['id_Equip']; //Obtener el id del equipo
            $query = "UPDATE tbasignado SET id_Empl=?,id_Eq=?,observaciones=?,descripcion=?,acta=?,estado=?,fecha_fin=? WHERE id_Asig=?";
            $result = $this->cnx->prepare($query);
            $result->bindParam(1,$id_Empl);
            $result->bindParam(2,$id_Equip);
            $result->bindParam(3,$observaciones);
            $result->bindParam(4,$descripcion);
            $result->bindParam(5,$acta);
            $result->bindParam(6,$estado);
            $result->bindParam(7,$fecha_finalizacion);
            $result->bindParam(8,$id_Asig);
            if($result->execute()){
                if($result->rowCount() > 0){
                    return true;
                }
            }
        }else{
            return false; //Si no se encuentra el equipo, retornar false}
        }
    }
    function BuscarAsignacionId($id_Asig){
        $query = "SELECT * FROM tbasignado WHERE id_Asig=?";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id_Asig);
        if($result->execute()){
            if($result->rowCount() > 0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        }
        return false;
    }
    function ActivarAsignacion($id_Asig){
        $estado = 1; //Activo por defecto
        $fecha_finalizacion="0000-00-00";
        $query = "UPDATE tbasignado SET estado=?,fecha_fin=? WHERE id_Asig=?";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $estado);
        $result->bindParam(2, $fecha_finalizacion);
        $result->bindParam(3, $id_Asig);

        if($result->execute()){
            if($result->rowCount() > 0){
                return true;
            }
        }
        return false;
    }
}
?>