<?php
require "../config/Conexion.php";

class Empleados{

    public $cnx;

    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }

    // listarEmpleados: Retrieves a list of active employees along with their area and contact details.
    // Returns an associative array of employee data if successful, false otherwise.
    // This function joins the tbempleado and tbarea tables to get employee details along with their area.
    // It uses a prepared statement to prevent SQL injection.
    function listarEmpleados(){
        $query="SELECT tbe.id_Empl,tbe.nombre,tbe.apellido,tba.Area,tbe.estado,tbe.cedula,tbe.correo, tbe.Cargo
        FROM tbempleado tbe INNER JOIN tbarea tba ON tbe.id_Area=tba.id_Area;";
   
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

    // VerifiarEmpleados: Checks if an employee with the given cedula exists and is active.
    // Parameters: cedula (string).
    // Returns true if the employee exists and is active, false otherwise.
    // This function uses a prepared statement to prevent SQL injection.
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

    // ActivarEmpleados: Activates an employee by setting their estado to 1.
    // Parameters: id (int).
    // Returns true on success, false on failure.
    // This function updates the estado of the employee to 1 (active).
    // It uses a prepared statement to prevent SQL injection.
    function ActivarEmpleados($id){
        $query="UPDATE tbempleado set estado = 1 where id_Empl = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id);
        if($result->execute()){
            return true;
        
        }else{
            return false;
        }
    }

    // ActualizarEmpleados: Updates employee details in the database.
    // Parameters: id (int), cedula (string), nombre (string), apellido (string), correo (string), id_Area (int).
    // Returns true on success, false on failure.
    // This function updates the employee's details in the tbempleado table using a prepared statement to prevent SQL injection.
    function ActualizarEmpleados($id,$cedula,$nombre,$apellido,$correo,$id_Area,$cargo){
        $query="UPDATE tbempleado set cedula = ?, nombre = ?, apellido = ?, correo = ?, id_Area = ?, Cargo=? where id_Empl = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$cedula);
        $result->bindParam(2,$nombre);
        $result->bindParam(3,$apellido);
        $result->bindParam(4,$correo);
        $result->bindParam(5,$id_Area);
        $result->bindParam(6,$cargo);  
        $result->bindParam(7,$id);

        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }


    // buscarEmpleadosId: Retrieves employee details by their ID.
    // Parameters: id (int).
    // Returns an associative array with employee details if found, false otherwise.
    // This function joins the tbempleado and tbarea tables to get employee details along with their area.
    // It uses a prepared statement to prevent SQL injection.
        function buscarEmpleadosId($id){
        $query="SELECT tbe.id_Empl,tbe.nombre,tbe.apellido,tbe.cedula,tbe.correo,tba.Area 
        from tbempleado tbe INNER JOIN tbArea tba ON tbe.id_Area=tba.id_Area where tbe.id_Empl = ?;";
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


    // buscarEmpleados: Retrieves employee details by their cedula.
    // Parameters: id (string).
    // Returns an associative array with employee details if found, false otherwise.
    // This function joins the tbempleado and tbarea tables to get employee details along with their area.
    // It uses a prepared statement to prevent SQL injection.
    function buscarEmpleados($id){
        $query="SELECT tbe.id_Empl,tbe.nombre,tbe.apellido,tbe.cedula,tbe.correo,tba.Area 
        from tbempleado tbe INNER JOIN tbArea tba ON tbe.id_Area=tba.id_Area where tbe.estado=1 AND tbe.cedula = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id, PDO::PARAM_INT);
        if($result->execute()){
            if($result->rowCount()>0){
                return $result->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }
        return false;
    }


    // LlenarSelectArea: Retrieves all active areas from the database.
    // Returns an associative array of area data if successful, false otherwise.
    // This function retrieves all areas from the tbarea table where estado is 1 (active).
    // It uses a prepared statement to prevent SQL injection.
    function LlenarSelectArea(){
        $query="SELECT * from tbarea where estado = 1;";
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

    // registrarEmpleados: Inserts a new employee into the database.
    // Parameters: cedula (string), nombre (string), apellido (string), correo (string), id_Area (int).
    // Returns true on success, false on failure.
    // This function inserts a new employee into the tbempleado table with a default estado of 1 (active).
    // It uses a prepared statement to prevent SQL injection.
function registrarEmpleados($cedula,$nombre,$apellido,$correo,$id_Area,$cargo){
    $query="INSERT INTO tbempleado(cedula,nombre,apellido,correo,id_Area,estado,Cargo) VALUES(?,?,?,?,?,?,?);"; 
    $result = $this->cnx->prepare($query); 
    $estado = 1; //Activo por defecto
    $result->bindParam(1,$cedula);
    $result->bindParam(2,$nombre);
    $result->bindParam(3,$apellido);
    $result->bindParam(4,$correo);
    $result->bindParam(5,$id_Area);
    $result->bindParam(6,$estado);
    $result->bindParam(7,$cargo);
    if($result->execute()){
        return true;
    }else{
        return false;
    }
}

// eliminarEmpleados: Marks an employee as inactive by setting their estado to 0.
// Parameters: id (int).
// Returns true on success, false on failure.
// This function updates the estado of the employee to 0 (inactive).
// It uses a prepared statement to prevent SQL injection.
    function eliminarEmpleados($id){
        $query="UPDATE tbempleado set estado = 0 where id_Empl = ?;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1,$id);
        if($result->execute()){
            return true;
        
        }else{
            return false;
        }
    }   

}

?>