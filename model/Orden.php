<?php
require "../config/Conexion.php";
class Orden{
 public $cnx;
    function __construct(){
        $this->cnx = Conexion::ConectarBD();
    }

    //Listar todas las ordenes con su contrato y empresa
    function listarOrden(){
        //Consulta para listar todas las ordenes con su contrato y empresa
        //Incluye conteo de equipos activos y devueltos por orden
        $query = "SELECT 
                    tbo.id_Orden,
                    tbo.orden_Compra,
                    tbo.orden_Servicio,
                    tbo.fecha_Entrega,
                    tbto.Tipo_Orden,
                    tbc.NumeroContrato,
                    tbe.Empresa,
                    tbexo.orden_original,
                    -- Contar equipos activos (estado = 1)
                    SUM(CASE WHEN tbexo.estado = 1 THEN 1 ELSE 0 END) AS TotalEquiposActivos,
                    -- Contar equipos devueltos/cambiados (estado = 0)
                    SUM(CASE WHEN tbexo.estado = 0 THEN 1 ELSE 0 END) AS TotalEquiposDevueltos
                    FROM tborden tbo 
                INNER JOIN tbcontrato tbc On tbc.Id_Contrato=tbo.id_Contrato 
                LEFT JOIN tb_equipoxorden tbexo On tbexo.id_Orden=tbo.id_Orden -- Usar LEFT JOIN para incluir órdenes sin equipos
                INNER JOIN tbtipoorden tbto On tbto.id_TipoOrden=tbo.id_TipoOrden 
                INNER JOIN tbempresas tbe On tbe.id_Empresa=tbc.id_Empresa
                GROUP BY tbo.id_Orden, tbo.orden_Compra, tbo.orden_Servicio, tbo.fecha_Entrega, tbto.Tipo_Orden, tbc.NumeroContrato, tbe.Empresa;";

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

    //Verificar si el contrato existe por numero de contrato
    public function verificarContrato($numeroContrato) {
        //Buscar contrato en la base de datos por numero de contrato
        $query = "SELECT Id_Contrato FROM tbcontrato WHERE NumeroContrato = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->bindParam(1, $numeroContrato);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Buscar orden por numero de orden
    function buscarOrdenPorNumero($numeroOrden) {
        try {
            // Iniciar transacción (opcional, pero buena práctica si harás más operaciones)
            $this->cnx->beginTransaction();

            // 1. Buscar la orden principal en tborden
            $queryOrden = "SELECT 
                                o.id_Orden, o.orden_Servicio, o.orden_Compra, 
                                o.fecha_Entrega, o.id_TipoOrden, o.id_Contrato,
                                t.Tipo_Orden,
                                c.NumeroContrato,
                                -- Contar equipos activos (estado = 1)
                                SUM(CASE WHEN tbexo.estado = 1 THEN 1 ELSE 0 END) AS TotalEquiposActivos,
                                -- Contar equipos devueltos/cambiados (estado = 0)
                                SUM(CASE WHEN tbexo.estado_devolucion = 0 THEN 1 ELSE 0 END) AS TotalEquiposDevueltos

                           FROM tborden o
                           JOIN tbtipoorden t ON o.id_TipoOrden = t.id_TipoOrden
                           JOIN tb_equipoxorden tbexo ON tbexo.id_Orden=o.id_Orden
                           JOIN tbcontrato c ON o.id_Contrato = c.Id_Contrato
                           WHERE o.id_Orden = ?"; // LIMIT 1 porque solo esperamos una orden por número
            $stmtOrden = $this->cnx->prepare($queryOrden);
            $stmtOrden->bindParam(1, $numeroOrden);
            $stmtOrden->execute();
            $orden = $stmtOrden->fetch(PDO::FETCH_ASSOC);

            if (!$orden) {
                $this->cnx->rollBack(); // Revertir si no se encuentra la orden
                return false; // La orden no existe
            }

            // 2. Buscar los equipos asociados a esta orden en tb_equipoxorden y tbequipos
            $queryEquipos = "SELECT 
                                e.placa, e.serial,eo.estado,eo.estado_devolucion
                             FROM tb_equipoxorden eo
                             JOIN tbequipos e ON eo.id_Equipo = e.id_Equip
                             WHERE eo.id_Orden = ?";
            $stmtEquipos = $this->cnx->prepare($queryEquipos);
            $stmtEquipos->bindParam(1, $orden['id_Orden']);
            $stmtEquipos->execute();
            $equipos = $stmtEquipos->fetchAll(PDO::FETCH_ASSOC);

            // 3. Combinar los datos de la orden y sus equipos
            $orden['equipos'] = $equipos;

            $this->cnx->commit(); // Confirmar la transacción
            return $orden; // Retorna la orden con sus equipos

        } catch (PDOException $e) {
            $this->cnx->rollBack(); // Revertir si hay un error en la base de datos
            error_log("Error en buscarOrdenPorNumero: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            return false; // Indicar un error
        } catch (Exception $e) {
            $this->cnx->rollBack(); // Revertir cualquier otra excepción
            error_log("Error inesperado en buscarOrdenPorNumero: " . $e->getMessage());
            return false;
        }
    }

    //Buscar contrato por numero de contrato
    function buscarContratoPorNumero($numeroContrato){
        try {
            // Iniciar transacción (opcional, pero buena práctica si harás más operaciones)
            $this->cnx->beginTransaction();

            // 1. Buscar el contrato en tbcontrato
            $queryContrato = "SELECT tbc.Id_Contrato, tbc.NumeroContrato FROM tbcontrato tbc 
            WHERE tbc.NumeroContrato = ? AND tbc.estado = 1 LIMIT 1";
            $stmtContrato = $this->cnx->prepare($queryContrato);
            $stmtContrato->bindParam(1, $numeroContrato);
            $stmtContrato->execute();
            $contrato = $stmtContrato->fetch(PDO::FETCH_ASSOC);

            if (!$contrato) {
                $this->cnx->rollBack(); // Revertir si no se encuentra el contrato
                return false; // El contrato no existe
            }

            $this->cnx->commit(); // Confirmar la transacción
            return $contrato; // Retorna el contrato encontrado

        } catch (PDOException $e) {
            $this->cnx->rollBack(); // Revertir si hay un error en la base de datos
            error_log("Error en buscarContratoPorNumero: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            return false; // Indicar un error
        } catch (Exception $e) {
            $this->cnx->rollBack(); // Revertir cualquier otra excepción
            error_log("Error inesperado en buscarContratoPorNumero: " . $e->getMessage());
            return false;
        }

    }

    //Verificar si el equipo ya existe por placa
    function verificarEquipo($placa) {
        //Buscar equipo en la base de datos por placa
        $query = "SELECT id_Equip FROM tbequipos WHERE placa = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->bindParam(1, $placa);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Verificar si el equipo ya esta asignado a una orden activa
    function verificarEquipoxOrdenActivo($idEquipo) {
        //Buscar equipo en la base de datos por idEquipo y estado activo (1)
        $query = "SELECT id_ExO FROM tb_equipoxorden WHERE id_Equipo = ? AND estado = 1 LIMIT 1";
        $stmt = $this->cnx->prepare($query);
        $stmt->bindParam(1, $idEquipo, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Verificar si el equipo ya esta asignado a una orden activa para cambio
    function verificarEquipoxOrdenVerificarCambio($idEquipo, $idOrden) {
        //Buscar equipo en la base de datos por idEquipo y estado activo (1)
        $query = "SELECT id_ExO FROM tb_equipoxorden WHERE id_Equipo = ? AND id_Orden=? AND estado = 0 LIMIT 1";
        $stmt = $this->cnx->prepare($query);
        $stmt->bindParam(1, $idEquipo, PDO::PARAM_INT);
        $stmt->bindParam(2, $idOrden, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Verificar si el equipo ya esta asignado a una orden inactiva
    function verificarEquipoxOrden($id_Equipo){
        //Buscar equipo en la base de datos por id
        $query = "SELECT * FROM tb_equipoxorden WHERE estado = 0 AND id_Equipo = ? LIMIT 1";
        $stmt = $this->cnx->prepare($query);
        $stmt->bindParam(1, $id_Equipo);

        
        if($stmt->execute()){
            if($stmt->rowCount()>0){
                    return $stmt->fetch(PDO::FETCH_ASSOC); //Retorna los datos del Equipo
            } else {
                return false;
            }
    }
}

    //Listar tipos de orden
    function llenarTipoOrden(){
        //Consulta para listar todas las ordenes con su contrato y empresa
        $query = "SELECT * FROM tbtipoorden";
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

    //Actualizar estado de equipo en tb_equipoxorden a 0 (devuelto) y registrar fecha de salida
    function actualizarEstadoEquipoXOrden($idEquipoXOrden, $fechaSalida) {
        //Actualizar estado del equipo en tb_equipoxorden a 0 (devuelto) y registrar fecha de salida
        $query = "UPDATE tb_equipoxorden SET estado = 0, Fecha_Salida = ? WHERE id_ExO = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->bindParam(1, $fechaSalida);
        $stmt->bindParam(2, $idEquipoXOrden);
        return $stmt->execute();
    }


    //Procesar orden (inserta o actualiza según sea necesario)
 public function procesarOrden($ordenCompra, $ordenServicio, $fechaEntrega, $idTipoOrden, $numeroContrato, $equiposParaRegistrar = [], $equiposParaDevolver = [],$numeroRegistro) {
        $this->cnx->beginTransaction();
        $fechaActual = date( 'Y-m-d H:i:s',time());
        // Validar parámetros esenciales
        try {
            // 1. Verificar el contrato
            $datosContrato = $this->verificarContrato($numeroContrato);
            if (!$datosContrato) {
                throw new Exception("El número de contrato proporcionado no existe.");
            }
            $idContrato = $datosContrato['Id_Contrato'];

            // Ajustar fechaEntrega si está vacía
            if (empty($fechaEntrega)) {
                $fechaEntrega = $fechaActual;
            }

            // 2. Insertar la nueva orden en tborden
            // Asegúrate de que el número de parámetros en VALUES coincida con los bindParam
            $queryOrden = "INSERT INTO tborden (orden_Servicio, orden_Compra, fecha_Entrega, id_TipoOrden, id_Contrato)
                             VALUES (?, ?, ?, ?, ?)";
            $stmtOrden = $this->cnx->prepare($queryOrden);
            $stmtOrden->bindParam(1, $ordenServicio);
            $stmtOrden->bindParam(2, $ordenCompra);
            $stmtOrden->bindParam(3, $fechaEntrega);
            $stmtOrden->bindParam(4, $idTipoOrden, PDO::PARAM_INT);
            $stmtOrden->bindParam(5, $idContrato, PDO::PARAM_INT);

            if (!$stmtOrden->execute()) {
                throw new Exception("Error al insertar la orden principal en tborden.");
            }

            $idOrdenGenerado = $this->cnx->lastInsertId();

            // 3. Procesar equipos para Devolver (los que salen de órdenes anteriores)
            // Esto aplica para Devolución (idTipoOrden = 2) y Cambio (idTipoOrden = 3)
            if (!empty($equiposParaDevolver)) {
                foreach ($equiposParaDevolver as $equipo) {
                    $placaDevolver = $equipo['placa'] ?? null;
                    if (empty($placaDevolver)) {
                        error_log("Advertencia: Placa vacía en equipos para devolver. Se salta.");
                        continue;
                    }

                    $equipoExistente = $this->verificarEquipo($placaDevolver);
                    if (!$equipoExistente) {
                        throw new Exception("El equipo con placa '{$placaDevolver}' que se intenta devolver no existe en el inventario de equipos.");
                    }
                    $idEquipoExistente = $equipoExistente['id_Equip'];

                    $equipoXOrdenActivo = $this->verificarEquipoxOrdenActivo($idEquipoExistente);
                    if ($equipoXOrdenActivo) {
                        $idEquipoXOrdenAActualizar = $equipoXOrdenActivo['id_ExO'];
                        if (!$this->actualizarEstadoEquipoXOrden($idEquipoXOrdenAActualizar, $fechaActual)) {
                            throw new Exception("Error al actualizar el estado del equipo '{$placaDevolver}' en tb_equipoxorden.");
                        }
                    } else {
                        // Si no se encuentra un registro activo, decide si es un error o una advertencia.
                        // Para "devolución" o "cambio", el equipo **debería** tener una relación activa.
                        throw new Exception("El equipo '{$placaDevolver}' no tiene una asociación activa en tb_equipoxorden para ser devuelto.");
                    }
                }
            }

            // 4. Procesar equipos para Registrar (nuevos equipos que entran a la orden)
            // Esto aplica para Instalación (idTipoOrden = 1) y Cambio (idTipoOrden = 2)
            if (!empty($equiposParaRegistrar)) {
                foreach ($equiposParaRegistrar as $equipo) {
                    $placa = $equipo['placa'] ?? null;
                    $serial = $equipo['serial'] ?? null;

                    if (empty($placa) || empty($serial)) {
                        error_log("Advertencia: Equipo a registrar con placa o serial vacío. Se salta.");
                        continue;
                    }

                    $idEquipoActual = null;
                    $estadoEquipoTbequipos = 1; // Estado para tbequipos (ej. "activo")

                    $equipoExistente = $this->verificarEquipo($placa);
                    
                    if (!$equipoExistente) {
                        // Si el equipo NO existe, lo insertamos en tbequipos
                        $queryEquipo = "INSERT INTO tbequipos(placa, serial, fecha_creacion, estado) VALUES(?,?,?,?);";
                        $stmtEquipo = $this->cnx->prepare($queryEquipo);
                        $stmtEquipo->bindParam(1, $placa);
                        $stmtEquipo->bindParam(2, $serial);
                        $stmtEquipo->bindParam(3, $fechaActual);
                        $stmtEquipo->bindParam(4, $estadoEquipoTbequipos, PDO::PARAM_INT);

                        if (!$stmtEquipo->execute()) {
                            throw new Exception("Error al insertar un nuevo equipo en tbequipos (placa: {$placa}).");
                        }
                        $idEquipoActual = $this->cnx->lastInsertId();
                    } else {

                        $idEquipoActual = $equipoExistente['id_Equip'];

                    }

                    if ($idEquipoActual === null) {
                        throw new Exception("No se pudo obtener un ID de equipo (nuevo o existente) para la placa: {$placa}.");
                    }

                    // Verificar si el equipo ya está asignado a una orden activa
                    $equipoXOrdenVerificar = $this->verificarEquipoxOrdenActivo($idEquipoActual);

                    if(isset($numeroRegistro)&&empty($numeroRegistro)){
                        $numeroRegistro=0;
                    }

                    if(!$equipoXOrdenVerificar || $idTipoOrden==3){

                        $verificarEquipoxOrdenVerificarCambio=$this->verificarEquipoxOrdenVerificarCambio($idEquipoActual,$numeroRegistro);
                        // Insertar la asociación en tb_equipoxorden para la NUEVA orden
                        // Lógica para diferentes tipos de orden
                        // Devolución (idTipoOrden = 3) y Cambio (idTipoOrden = 2) requieren estadoEquipoXOrdenDevolucion
                        // Instalación (idTipoOrden = 1) no requiere estadoEquipoXOrdenDevolucion
                        // Logica para Cambio
                        if(!$verificarEquipoxOrdenVerificarCambio && $idTipoOrden==2){
                        $estadoEquipoXOrdenDevolucion = 0;
                        $estadoEquipoXOrdenNuevo = 1; // 0 para devolucion
                        $fechaSalidaNueva = "0000-00-00 00:00:00"; // Indicar que aún no ha salido
                        // Insertar la asociación en tb_equipoxorden para la NUEVA orden    
                        $queryEquipoOrden = "INSERT INTO tb_equipoxorden (id_Orden, id_Equipo, fecha_Entrega, Fecha_Salida, estado,estado_devolucion, orden_original) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmtEquipoOrden = $this->cnx->prepare($queryEquipoOrden);
                        $stmtEquipoOrden->bindParam(1, $idOrdenGenerado, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(2, $idEquipoActual, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(3, $fechaActual);
                        $stmtEquipoOrden->bindParam(4, $fechaSalidaNueva);
                        $stmtEquipoOrden->bindParam(5, $estadoEquipoXOrdenNuevo, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(6, $estadoEquipoXOrdenDevolucion, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(7, $numeroRegistro, PDO::PARAM_INT);

                        }else // Lógica para devolución
                         if($idTipoOrden==3){
                        $estadoEquipoXOrdenDevolucion = 1;
                        $estadoEquipoXOrdenNuevo = 0; // 0 para devolucion
                        $queryEquipoOrden = "INSERT INTO tb_equipoxorden (id_Orden, id_Equipo, fecha_Entrega, Fecha_Salida, estado,estado_devolucion, orden_original) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmtEquipoOrden = $this->cnx->prepare($queryEquipoOrden);
                        $stmtEquipoOrden->bindParam(1, $idOrdenGenerado, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(2, $idEquipoActual, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(3, $fechaActual);
                        $stmtEquipoOrden->bindParam(4, $fechaActual);
                        $stmtEquipoOrden->bindParam(5, $estadoEquipoXOrdenNuevo, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(6, $estadoEquipoXOrdenDevolucion, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(7, $numeroRegistro, PDO::PARAM_INT);
                        }else // Lógica para Instalación 
                        if($idTipoOrden=1){
                        $estadoEquipoXOrdenNuevo = 1; // 1 para activo/en uso en la nueva orden
                        $fechaSalidaNueva = "0000-00-00 00:00:00"; // Indicar que aún no ha salido

                        $queryEquipoOrden = "INSERT INTO tb_equipoxorden (id_Orden, id_Equipo, fecha_Entrega, Fecha_Salida, estado, orden_original) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmtEquipoOrden = $this->cnx->prepare($queryEquipoOrden);
                        $stmtEquipoOrden->bindParam(1, $idOrdenGenerado, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(2, $idEquipoActual, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(3, $fechaActual);
                        $stmtEquipoOrden->bindParam(4, $fechaSalidaNueva);
                        $stmtEquipoOrden->bindParam(5, $estadoEquipoXOrdenNuevo, PDO::PARAM_INT);
                        $stmtEquipoOrden->bindParam(6, $numeroRegistro, PDO::PARAM_INT);

                        }   
                        // Ejecutar la inserción en tb_equipoxorden
                        if (!$stmtEquipoOrden->execute()) {
                            throw new Exception("Error al insertar la asociación equipo-orden en tb_equipoxorden (Orden: {$idOrdenGenerado}, Equipo: {$idEquipoActual}).");
                        }
                    }
                }
            }

            $this->cnx->commit();
            return true;
        } catch (Exception $e) {
            $this->cnx->rollBack();
            error_log("Error en procesarOrden: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            throw $e; // Re-lanzar la excepción para que el controlador la capture.
        }
    }

}
?>