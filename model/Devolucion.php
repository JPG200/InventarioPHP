<?php
require "../config/Conexion.php";
class Devolucion{
 public $cnx;
    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }

    //listarDevolucion: Retrieves a list of all active returns with their details.
    // Returns an array of associative arrays containing the return details.
    // If no active returns are found, it returns false.

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

    //RegistrarDevolucion: Registers a new return for an assignment.
    // Parameters: acta_asig (string), observaciones (string), acta (string).
    // Returns true on success, false on failure.
    // This function checks if there is an active return for the assignment and if the assignment exists before inserting a new return record.
    function RegistrarDevolucion($acta_asig,$observaciones,$acta){
        try{
        //Verificar si existe una devolucion activa para esta acta de asignacion
        if(!$this->VerificarDevolucion($acta)){
            return false; //Ya existe una devolucion activa para esta acta de devolucion
        }
        //Verificar si existe una asignacion activa para esta acta de asignacion
        if(!$this->VerificarExistenciaAsignacion($acta_asig)){
            return false; //Ya existe una asignacion inactiva para esta acta de asignacion
        }

        $Asig=$this->buscartablaAsignacion($acta_asig);
        $id_Asig = $Asig['id_Asig'];

        if(!$this->VerificarAsigenDevolucion($id_Asig)){
            return false;
        }

        //Si no existe una devolucion activa para esta acta de asignacion, registrar la devolucion

        $Asig=$this->buscartablaAsignacion($acta_asig);
        $id_Equip = $Asig['id_Eq'];
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


// ActualizarDevolucion: Updates an existing return record.
// Parameters: id_Dev (int), acta_asig (string), observaciones (string), acta (string).
// Returns true on success, false on failure.
// This function checks if there is an active return for the assignment and if the assignment exists before updating the return record.
function ActualizarDevolucion($id_Dev, $acta_asig, $observaciones, $acta){
    try{
    //Verificar si existe una devolucion activa para esta acta de asignacion
    if(!$this->VerificarExistenciaAsignacion($acta_asig)){
        return false; //Ya existe una asignacion inactiva para esta acta de asignacion
    }
    
    $Asig = $this->buscartablaAsignacion($acta_asig);
    if (!$Asig) {
        return false; // No se encontró la asignación, retorna false o maneja el error según tu lógica
    }
    $id_Asig = $Asig['id_Asig'];
    $id_Equip = $Asig['id_Eq'];
    $descripcion = $Asig['descripcion'];
    $id_Empresa = $this->buscartablaRegEquipo($id_Equip)['id_Empresa'];

    $query = "UPDATE tbdevolucion 
    SET id_Asig=?, id_Equip=?,descipcion=?, observaciones=?, id_Empresa=?, acta=? WHERE id_Dev=?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1, $id_Asig);
    $result->bindParam(2, $id_Equip);
    $result->bindParam(3, $descripcion);
    $result->bindParam(4, $observaciones);
    $result->bindParam(5, $id_Empresa);
    $result->bindParam(6, $acta);
    $result->bindParam(7, $id_Dev);

    if($result->execute()){
        return true;
    }
    return false;
}catch(Exception $e){
    return $e->getMessage(); //Error al actualizar la devolucion

}
}


// BuscarInformacion: Retrieves information about an active assignment based on the provided acta_asig.
// If no active assignment exists, it returns false.
// This function checks if there is an active return for the assignment and if the assignment exists before fetching the return details.
function BuscarInformacion($acta_asig){
    //Buscar informacion de la asignacion activa
    //Si no existe una asignacion activa, retornar false
    if(!$this->VerificarExistenciaAsignacion($acta_asig)){
        return false; //No existe una asignacion activa para esta acta de asignacion
    }

    $Asig=$this->buscartablaAsignacion($acta_asig);
    $id_Asig = $Asig['id_Asig'];

    if(!$this->VerificarAsigenDevolucionBusqueda($id_Asig)){
        if(!$this->VerificarAsigenDevolucion($id_Asig)){    
        return false; //No existe una asignacion activa para esta acta de asignacion
        }
    }
    
    $query=" SELECT tbd.id_Dev,tbd.observaciones,tba.descripcion,tbd.acta AS 'ActaDev',tbe.placa,tba.acta AS 'ActaAsig' FROM tbdevolucion tbd 
    RIGHT JOIN tbasignado tba ON tbd.id_Asig=tba.id_Asig 
    INNER JOIN tbequipos tbe ON tba.id_Eq=tbe.id_Equip 
    WHERE tba.id_Asig=?;";
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

// EliminarDevolucion: Deletes a return record by setting its estado to 0 and updating the fecha_retorno.
// Parameters: id_Dev (int).
// Returns true on success, false on failure.
// This function updates the estado of the return to 0 (inactive) and sets the fecha_retorno to the current date and time.
function EliminarDevolucion($id_Dev){
    $fecha_dev= date( 'Y-m-d H:i:s',time());
    $query = "UPDATE tbdevolucion SET estado =0,fecha_retorno=? WHERE id_Dev=?;";

    $result = $this->cnx->prepare($query);
    $result->bindParam(1, $fecha_dev);
    $result->bindParam(2, $id_Dev);

    if($result->execute()){
        if($result->rowCount() > 0){
            return true; //Devolucion eliminada correctamente
        }
    }
    return false; //Error al eliminar la devolucion
}

// ActivarDevolucion: Activates a return by setting its estado to 1 and fecha_retorno to '0000-00-00 00:00:00'.
// Parameters: id_Dev (int).
// Returns true on success, false on failure.
// This function updates the estado of the return to 1 (active) and sets the fecha_retorno to '0000-00-00 00:00:00'.
function ActivarDevolucion($id_Dev){
    $fecha_retorno= '0000-00-00 00:00:00';
    $query = "UPDATE tbdevolucion SET estado=1,fecha_retorno=? WHERE id_Dev=?;";
    $result = $this->cnx->prepare($query);
    $result->bindParam(1, $fecha_retorno);
    $result->bindParam(2, $id_Dev);
    if($result->execute()){
        if($result->rowCount() > 0){
            return true; //Devolucion activada correctamente
        }
    }
    return false; //Error al activar la devolucion
}

// buscartablaAsignacion: Retrieves assignment details based on the provided acta.
// Parameters: acta (string).
// Returns an associative array with assignment details if found, false otherwise.
// This function checks if there is an active return for the assignment and if the assignment exists before fetching the assignment details.
    function buscartablaAsignacion($acta){
        $query="SELECT id_Eq,id_Asig,descripcion from tbasignado where acta=?;";
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

    // buscartablaRegEquipo: Retrieves the company ID associated with the equipment based on the provided id_Equip.
    // Parameters: id_Equip (int).
    // Returns an associative array with the company ID if found, false otherwise.
    // This function checks if there is an active return for the assignment and if the assignment exists before fetching the company ID.
    function buscartablaRegEquipo($id_Equip){
        $query="SELECT id_Empresa from tbregequip where id_Equip=? LIMIT 1;";
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
    

    // VerificarAsigenDevolucionBusqueda: Checks if there is an active return for the assignment based on the provided id_Asig.
    // Parameters: id_Asig (int).
    // Returns true if an active return exists, false otherwise.
    // This function is used to verify if there is an active return for the assignment before performing any operations on it.
        function VerificarAsigenDevolucionBusqueda($id_Asig){
        $query = "SELECT * FROM tbdevolucion WHERE id_Asig=? and estado=1;";
        $result = $this->cnx->prepare($query);
        $result->bindParam(1, $id_Asig);
        if($result->execute()){
            if($result->rowCount() > 0){
                return true; //Ya existe una devolucion activa para esta acta de devolucion
            }
        }
        return false; //No existe acta de devolucion
    }


    // VerificarAsigenDevolucion: Checks if there is an active return for the assignment based on the provided id_Asig.
    // Parameters: id_Asig (int).
    // Returns true if an active return exists, false otherwise.
    // This function is used to verify if there is an active return for the assignment before performing any operations on it.
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

    // VerificarExistenciaAsignacion: Checks if there is an active assignment for the provided acta_asig.
    // Parameters: acta_asig (string).
    // Returns true if an active assignment exists, false otherwise.
    // This function is used to verify if there is an active assignment for the provided acta_asig before performing any operations on it.
    function VerificarExistenciaAsignacion($acta_asig){
        $query = "SELECT * FROM tbasignado WHERE acta=?;";
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