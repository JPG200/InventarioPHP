<?php 
require "../config/Conexion.php";

class Equipos{
    public $cnx;

    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }

    // ListarArea: Retrieves all areas from the database.
    function ListarArea(){
        // This function fetches all records from the tbarea table.
        $query="SELECT * from tbarea;";

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

    // CrearArea: Inserts a new area into the database.
    // Parameters: Area (string), centro_costos (string).
    // Returns true on success, false on failure.
    function CrearArea($Area,$centro_costos){
        $estado = 1; //Activo por defecto
        $fecha_fin = null; //Fecha de terminacion por defecto
        $fecha_creacion=date( 'Y-m-d H:i:s',time()); //Fecha creacion del equipo
        $query = "INSERT INTO tbarea (Area,centro_costos, fecha_fin,fecha_creacion, estado) VALUES (?,?,?,?,?)";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $Area);
        $result->bindParam(2, $centro_costos);
        $result->bindParam(3, $fecha_fin);
        $result->bindParam(4, $fecha_creacion);
        $result->bindParam(5, $estado);
        
        if($result->execute()){
            return true;
        } else {
            return false;
        }
}

// VerificarArea: Checks if an area with the given centro_costos already exists.
// Parameters: centro_costos (string).
// Returns true if the area does not exist, false if it does.
function VerificarArea($centro_costos){
    // This function checks if an area with the specified centro_costos exists and is active.
    // It returns false if the area exists, and true if it does not.
    $query = "SELECT * FROM tbarea WHERE centro_costos = ? AND estado = 1";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1, $centro_costos);
    
    if($result->execute()){
        if($result->rowCount()>0){
            return false; // Area ya existe
        }
    }
    return true; // Area no existe
}

// buscarArea: Retrieves an area by its ID.
// Parameters: id (int).
// Returns the area data as an associative array if found, false otherwise.
function buscarArea($id){
    // This function fetches an area by its ID from the tbarea table.
    // It returns the area data as an associative array if found, or false if not found.
        $query="SELECT * from tbarea where id_Area = ?;";
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

    // ActivarArea: Activates an area by setting its estado to 1.
    // Parameters: id (int).
    // Returns true on success, false on failure.
    function ActivarArea($id){
        $query="UPDATE tbarea set estado = 1 where id_Area = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }
    

    // EliminarArea: Deactivates an area by setting its estado to 0.
    // Parameters: id (int).
    // Returns true on success, false on failure.
    function EliminarArea($id){
        $query="UPDATE tbarea set estado = 0 where id_Area = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }

    // ActualizarArea: Updates an existing area in the database.
    // Parameters: id (int), Area (string), centro_costos (string).
    // Returns true on success, false on failure.
    function ActualizarArea($id,$Area,$centro_costos){
        $query="UPDATE tbarea set Area = ?, centro_costos = ? where id_Area = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$Area);
        $result->bindParam(2,$centro_costos);
        $result->bindParam(3,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }
}
?>