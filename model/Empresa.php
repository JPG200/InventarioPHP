<?php
require "../config/Conexion.php";
class Empresa{
 public $cnx;
    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }

    function listarEmpresa(){
        $query = "SELECT tbem.id_Empresa,tbem.Empresa,tbem.NIT,tbc.NumeroContrato,tbc.fecha_Inicio,tbc.fecha_fin,tbc.estado 
                  FROM tbempresas tbem INNER JOIN tbcontrato tbc ON tbc.id_Empresa=tbem.id_Empresa WHERE tbc.estado=1;";

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
}

?>