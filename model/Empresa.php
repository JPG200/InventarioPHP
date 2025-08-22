<?php
require "../config/Conexion.php";
class Empresa{
 public $cnx;
    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }


    //Listar todas las empresas con su contrato
    function listarEmpresa(){
        $query = "SELECT tbem.id_Empresa,tbem.Empresa,tbem.NIT,tbc.NumeroContrato,tbc.fecha_Inicio,tbc.fecha_fin,tbc.estado 
                  FROM tbempresas tbem INNER JOIN tbcontrato tbc ON tbc.id_Empresa=tbem.id_Empresa;";

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

    //Buscar contrato por numero de contrato y id de empresa
    function buscarContrato($NumeroContrato,$id_Empresa){
        $query = "SELECT * FROM tbcontrato WHERE estado =1 AND id_Empresa=? OR NumeroContrato = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $NumeroContrato);
        $result->bindParam(2, $id_Empresa);

        if($result->execute()){
            if($result->rowCount() > 0){
                return $result->fetch(PDO::FETCH_ASSOC); //Retorna los datos del contrato
            }
        }
        return false; //No se encontro el contrato

    }

    //Buscar empresa por nombre o NIT
    function buscarEmpresa($empresa,$NIT){
        $query = "SELECT * FROM tbempresas WHERE Empresa = ? OR NIT=?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $empresa);
        $result->bindParam(2, var: $NIT);

        if($result->execute()){
            if($result->rowCount() > 0){
                return $result->fetch(PDO::FETCH_ASSOC); //Retorna los datos de la empresa
            }
        }
        return false; //No se encontro la empresa
    }

    //Buscar empresa por NIT y numero de contrato
    function BuscarInformacion($NIT,$NumeroContrato){
        $query = "SELECT tbe.id_Empresa,tbe.NIT,tbe.Empresa,tbc.NumeroContrato,tbc.fecha_Inicio,tbc.fecha_fin
         FROM tbempresas tbe INNER JOIN tbcontrato tbc ON tbc.id_Empresa=tbe.id_Empresa
         WHERE tbc.NumeroContrato = ? AND tbe.NIT=?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $NumeroContrato);
        $result->bindParam(2, $NIT);

        if($result->execute()){
            if($result->rowCount() > 0){
                return $result->fetch(PDO::FETCH_ASSOC); //Retorna los datos de la empresa
            }
        }
        return false; //No se encontro la empresa
    }

    //Actualizar empresa y contrato
    function actualizarEmpresa($id_Empresa,$empresa,$NIT,$NumeroContrato,$FechaI,$FechaF){
try{       
        $estado = 1; //Estado activo por defecto

        $query = "UPDATE tbempresas SET Empresa=?, NIT=? WHERE id_Empresa=?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $empresa);
        $result->bindParam(2,  $NIT);
        $result->bindParam(3, $id_Empresa);

        if($result->execute()){

            $queryContrato = "UPDATE tbcontrato SET fecha_Inicio=?, fecha_fin=?, estado=? WHERE id_Empresa=? AND NumeroContrato=?;";
            $resultContrato = $this->cnx->prepare($queryContrato);
            $resultContrato->bindParam(1, $FechaI);
            $resultContrato->bindParam(2, $FechaF);
            $resultContrato->bindParam(3, $estado);
            $resultContrato->bindParam(4, $id_Empresa);
            $resultContrato->bindParam(5, $NumeroContrato);


            if($resultContrato->execute()){
                return true; //Contrato actualizado correctamente
            }else{
                return false; //Error al actualizar el contrato
            }       
         }else{
            return false; //Error al actualizar la empresa
         }
        }catch(Exception $e){
            return $e->getMessage(); //Error al actualizar la empresa
        }
    }

    //Registrar empresa y contrato
        function RegistrarEmpresa($empresa,$NIT,$NumeroContrato,$FechaI,$FechaF){
            try{
            $datos=$this->buscarEmpresa($empresa,$NIT);
            $estado = 1; //Estado activo por defecto
            $fecha_creacion = date( 'Y-m-d H:i:s',time());
            $fecha_fin = "0000-00-00"; //Fecha de finalizacion por defecto
                //Si no existe la empresa, se registra
            if(!$datos){
                $query = "INSERT INTO tbempresas (Empresa,NIT,fecha_creacion,estado,fecha_fin) VALUES (?,?,?,?,?);";
                $result = $this->cnx->prepare($query);
                $result->bindParam(1, $empresa);
                $result->bindParam(2, $NIT);
                $result->bindParam(3, $fecha_creacion);
                $result->bindParam(4, $estado);
                $result->bindParam(5,  $fecha_fin);
                if($result->execute()){    
                    $id_Empresa = $this->cnx->lastInsertId();
                    $queryContrato = "INSERT INTO tbcontrato (id_Empresa,NumeroContrato,fecha_Inicio,fecha_fin,estado) VALUES (?,?,?,?,?);";
                    $resultContrato = $this->cnx->prepare($queryContrato);
                    $resultContrato->bindParam(1, $id_Empresa);
                    $resultContrato->bindParam(2, $NumeroContrato);
                    $resultContrato->bindParam(3, $FechaI);
                    $resultContrato->bindParam(4, $FechaF);
                    $resultContrato->bindParam(5, $estado);

                    if($resultContrato->execute()){
                        return true;
                    }
                    return false; //Error al registrar el contrato
            }
            } else{
                //Si la empresa ya existe, solo se registra el contrato si no existe otro con el mismo numero para esa empresa
                if(!$this->buscarContrato($NumeroContrato,$datos['id_Empresa'])){
                    $queryContrato = "INSERT INTO tbcontrato (id_Empresa,NumeroContrato,fecha_Inicio,fecha_fin,estado) VALUES (?,?,?,?,?);";
                    $resultContrato = $this->cnx->prepare($queryContrato);
                    $resultContrato->bindParam(1, $datos['id_Empresa']);
                    $resultContrato->bindParam(2, $NumeroContrato);
                    $resultContrato->bindParam(3, $FechaI);
                    $resultContrato->bindParam(4, $FechaF);
                    $resultContrato->bindParam(5, $estado);
                    if($resultContrato->execute()){
                        return true;
                    }
                }
                    return false; //Ya existe un contrato con ese numero para esta empresa
                }
            return false;
            }catch(Exception $e){
                return false; //Error al registrar la empresa
            }
    }

    //Activar contrato
    function activarEmpresa($id_Empresa,$NumeroContrato){
        //Una empresa nunca se desactiva, solo su contrato
        $query = "UPDATE tbcontrato SET estado=1 WHERE NumeroContrato=? AND id_Empresa=?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $NumeroContrato);
        $result->bindParam(2, $id_Empresa);

        if($result->execute()){
            return true; //Empresa activada correctamente
        }
        return false; //Error al activar la empresa
    }

    //Eliminar contrato (cambiar estado a 0)
    function eliminarContrato($NumeroContrato,$id_Empresa){

        $query = "UPDATE tbcontrato SET estado=0 WHERE NumeroContrato=? and id_Empresa=?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $NumeroContrato);
        $result->bindParam(2, $id_Empresa);

        if($result->execute()){
            return true; //Empresa eliminada correctamente
        }
        return false; //Error al eliminar la empresa
    }
}
?>