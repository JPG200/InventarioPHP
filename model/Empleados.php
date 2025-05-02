<?php
require "../config/Conexion.php";

class Empleados{

    public $cnx;

    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }
    function listarEmpleados(){
        $query="SELECT tbe.id_Empl,tbe.nombre,tbe.apellido,tba.Area,tbe.estado,tbe.cedula,tbe.correo
        FROM tbempleado tbe INNER JOIN tbarea tba ON tbe.id_Area=tba.id_Area WHERE tbe.estado=1;";
   
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

    function VerifiarEmpleados($cedula){
        $query="SELECT * from tbempleado where estado=1 AND cedula LIKE ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$cedula);

        if($result->execute()){
            if($result->rowCount()>0){
                return true;
            }else{
                return false;
            }
        }
    }

    function buscarEmpleados($id){
        $query="SELECT * from tbempleado where id_Empl = ?;";
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

function registrarEmpleados($cedula,$nombre,$apellido,$correo,$id_Area){
    $query="INSERT INTO tbempleado(cedula,nombre,apellido,correo,id_Area,estado) VALUES(?,?,?,?,?,?);"; 
    $result = $this->cnx->prepare($query); 
    $estado = 1; //Activo por defecto
    $result->bindParam(1,$cedula);
    $result->bindParam(2,$nombre);
    $result->bindParam(3,$apellido);
    $result->bindParam(4,$correo);
    $result->bindParam(5,$id_Area);
    $result->bindParam(6,$estado);
    if($result->execute()){
        return true;
    }else{
        return false;
    }
}
    function eliminarEmpleados(){
    $query="DELETE ";
    
    }

}



?>