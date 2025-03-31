<?php
require "config/Conexion.php";
class Usuario{
    public $cnx;


    function __construct(){
        $this->cnx=Conexion::ConectarBD();
    }
    
    function RegistrarUsuario(){


        
    }

}
?>php