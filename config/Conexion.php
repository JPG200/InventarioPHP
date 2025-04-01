<?php
class Conexion{
    static function ConectarBD(){
        try{

            require "Global.php";
            $cnx = new PDO(dsn: DSN, username: USERNAME, password: PASSWORD);
            return $cnx;
        }
        catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }
}
?>