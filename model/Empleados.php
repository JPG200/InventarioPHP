<?php
require "../config/Conexion.php";

class Empleados{

    public $cnx;

    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }
    function listarEmpleados(){
        $query="SELECT tbe.id_Empl,tbe.nombre,tbe.apellido,tba.Area,tbe.estado,tbe.cedula
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

    function eliminarEmpleados(){
    $query="DELETE ";
    
    }

}



?>