var table;

init();// Función para inicializar el DataTable

function init(){
    // Inicializar el DataTable
    listarOrden();
    cerrarModal();
    llenarSelectTipoOrden();
    $('#txtNumeroRegistro').attr('readonly', true);
    $('#btnBuscarNumero').hide();
    $('#btnGuardarOrden').hide();
    $('#tablaEquiposModalCambio').hide();                  
    $('#btnAddEquipoCambio').hide(); // Ocultar el botón de agregar equipo
    $('#tituloEquiposNuevos').hide();
}

 function cerrarModal(){
    //  Cierra el modal de Bootstrap y limpia los campos del formulario
        $('#createOrden').modal('hide'); // Cierra el modal de Bootstrap
        // Limpiar todos los campos del formulario de la orden
        $('#id_Orden').val('');
        $('#txtOrdenCompra').val('');
        $('#txtOrdenServicio').val('');
        $('#txtNumeroRegistro').val('');
        $('#txtFechaEntrega').val('');
        $('#sltTipoOrden').val(''); // Limpia el select de tipo de orden
        $('#txtNumeroContrato').val('');
        // Limpiar la tabla de equipos agregados dinámicamente
        $('#tablaEquiposModal tbody').empty();
        $('#txtNumeroRegistro').attr('readonly', true);
        $('#btnBuscarNumero').show();
        $('#btnGuardarOrden').hide();      
        $('#btnAddEquipoCambio').hide(); // Ocultar el botón de agregar equipo
        $('#tablaEquiposModalCambio').hide();                  
        $('#tituloEquiposNuevos').hide();
        $('#btnBuscarContrato').show();     
        $('#tablaEquiposModalCambio tbody').empty();
        equipoRowCounter = 0; // Reinicia el contador de filas de equipo
        equipoRowCounterCambio = 0; // Reinicia el contador de filas de equipo
    }


    function buscarContratoExistente(numeroContrato) {
    $.ajax({
        // Cambia la URL al controlador correcto para buscar un contrato
        url: "../controller/OrdenController.php?operador=buscarContrato",
        type: "POST",
        data: {
            "numeroContrato": numeroContrato
        },
        beforeSend: function() {
            // Puedes mostrar un spinner o deshabilitar el botón de búsqueda
            $('#btnBuscarContrato').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...');
        },
        success: function(response) {
            
            let res;
            try {
                res = JSON.parse(response);
            } catch (e) {
                console.error("Error al parsear la respuesta del servidor (buscarOrden):", response, e);
                toastr.error("Respuesta inesperada del servidor al buscar la orden. (Error de formato)", "Error de Comunicación.");
                return;
            }
            // Manejo de respuesta del servidor
            if (res.status === "success") {
                toastr.success(res.message, "Búsqueda Exitosa.");
                // Rellenar el formulario con los datos de la orden
                const ordenData = res.data;
                $('#btnGuardarOrden').show(); // Mostrar el botón de guardar orden
                $('#txtNumeroContrato').val(ordenData.NumeroContrato); // Mostrar el número de contrato
                $('#txtNumeroContrato').attr('readonly', true); // Hacer readonly el campo de número de contrato
            } else if (res.status === "error"){
                toastr.error(res.message || "Ocurrió un error al buscar la orden.", "Búsqueda Fallida.");
            } else if( res.status === "required") {            
                    toastr.error(res.message || "Ocurrió un error al buscar la orden.", "Faltan Datos.");
            }   
            else{
                toastr.info(res.message || "La búsqueda se completó con un estado desconocido.", "Estado Desconocido.");
                console.warn("Estado de respuesta desconocido del servidor (buscarOrden):", res);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Manejo de errores en la solicitud AJAX
            console.error("Error en la solicitud AJAX (buscarOrden):", textStatus, errorThrown, jqXHR.responseText);
            let errorMessage = "No se pudo conectar con el servidor para buscar la orden.";
            if (jqXHR.status) {
                errorMessage += ` (Código de estado: ${jqXHR.status})`;
            }
            if (jqXHR.responseText) {
                errorMessage += "\nDetalles: " + jqXHR.responseText.substring(0, 150) + "...";
            }
            toastr.error(errorMessage, "Error de Conexión o Servidor.");
        },
        complete: function() {
            // Habilitar el botón de búsqueda y restaurar su texto original
            $('#btnBuscarContrato').prop('disabled', false).html('<i class="icon-search"></i> Buscar');
        }
    });
}

function registrarCambioConEquipos() {
    // Recopila los datos del formulario de la orden
        const ordenCompra = $('#txtOrdenCompra').val();
        const ordenServicio = $('#txtOrdenServicio').val();
        const fechaEntrega = $('#txtFechaEntrega').val();
        const idTipoOrden = $('#sltTipoOrden').val();
        const numeroContrato = $('#txtNumeroContrato').val();

        // Validación básica de los campos de la orden
        if (!ordenCompra || !ordenServicio || !fechaEntrega || !idTipoOrden || !numeroContrato) {
            toastr.warning("Por favor, complete todos los campos principales de la orden.", "Datos Incompletos.");
            return;
        }

        // Recopila los datos de los equipos de la tabla
        const equipos = [];
        $('#tablaEquiposModal tbody tr').each(function() {
            const placa = $(this).find('.input-placa').val();
            const serial = $(this).find('.input-serial').val();

            // Solo agrega el equipo si ambos campos (placa y serial) tienen valor
            if (placa && serial) {
                equipos.push({
                    placa: placa,
                    serial: serial
                });
            }
        });

        const equiposCambio = [];
        // Recopila los datos de los equipos de cambio
        $('#tablaEquiposModalCambio tbody tr').each(function() {
            const placa = $(this).find('.input-placa').val();
            const serial = $(this).find('.input-serial').val();

            // Solo agrega el equipo si ambos campos (placa y serial) tienen valor
            if (placa && serial) {
                equiposCambio.push({
                    placa: placa,
                    serial: serial
                });
            }
        });

        // Validación para asegurar que al menos un equipo ha sido agregado y tiene datos
        if (equipos.length === 0) {
            toastr.warning("Por favor, agregue al menos un equipo con Placa y Serial a la orden.", "Equipos Faltantes.");
            return;
        }
        // Validación específica para el tipo de orden "Cambio" (idTipoOrden == 2)
        if (idTipoOrden==2 && equiposCambio.length === 0) {
            toastr.warning("Por favor, agregue al menos un equipo con Placa y Serial a la orden de cambio.", "Equipos Faltantes.");
            return;
        }
        // Envía los datos mediante AJAX
        $.ajax({
            url: "../controller/OrdenController.php?operador=registrarCambio",
            type: "POST",
            data: {
                "ordenCompra": ordenCompra,
                "ordenServicio": ordenServicio,
                "fechaEntrega": fechaEntrega,
                "idTipoOrden": idTipoOrden,
                "numeroContrato": numeroContrato,
                "equipos": equipos, // Aquí va la matriz de equipos
                "equiposCambio": equiposCambio // Aquí va la matriz de equipos de cambio
            },
            beforeSend: function(response) {
            },
            success: function(response) {
                data = JSON.parse(response);
                // Manejo de la respuesta del servidor
                if (data === "success") {
                    toastr.success("Orden registrada exitosamente.", "Registro Exitoso.");
                    table.ajax.reload(); // Recargar tu tabla principal
                    cerrarModal(); // Limpiar y cerrar el modal
                } else if(data ==="error"){
                    toastr.error("Ocurrió un error al registrar la orden.", "Registro Fallido.");
                    cerrarModal(); // Limpiar y cerrar el modal
                } else if(data ==="required"){
                    toastr.warning("Por favor, complete todos los campos requeridos.", "Campos Incompletos.");
                    cerrarModal(); // Limpiar y cerrar el modal
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown, jqXHR.responseText);
                toastr.error("No se pudo conectar con el servidor. Intente de nuevo.", "Error de Conexión.");
            },
            complete: function() {
                // Habilita el botón de guardar y restaura su texto
                $('#btnGuardarOrden').prop('disabled', false).text('Guardar Orden');
            }
        });
    }

function buscarOrdenExistente(numeroOrden) {
    $.ajax({
        url: "../controller/OrdenController.php?operador=buscarOrden",
        type: "POST",
        data: {
            "numeroOrden": numeroOrden
        },
        beforeSend: function() {
            // Puedes mostrar un spinner o deshabilitar el botón de búsqueda
            $('#btnBuscarNumero').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...');
        },
        success: function(response) {
            let res;
            try {
                res = JSON.parse(response);
            } catch (e) {
                console.error("Error al parsear la respuesta del servidor (buscarOrden):", response, e);
                toastr.error("Respuesta inesperada del servidor al buscar la orden. (Error de formato)", "Error de Comunicación.");
                return;
            }
            // Corregido el log para usar el parámetro correcto
            if (res.status === "success") {
                toastr.success(res.message, "Búsqueda Exitosa.");
                // Rellenar el formulario con los datos de la orden
                const ordenData = res.data;

                $('#btnBuscarContrato').hide(); 
                $('#btnGuardarOrden').show();
                $('#txtNumeroContrato').attr('readonly',true); 

                $('#txtOrdenCompra').val(ordenData.orden_Compra);
                $('#txtOrdenServicio').val(ordenData.orden_Servicio);
                $('#txtFechaEntrega').val(ordenData.fecha_Entrega);
                $('#txtNumeroContrato').val(ordenData.NumeroContrato); // Mostrar el número de contrato
                $('#txtNumeroRegistro').attr('readonly', true); // Hacer editable

                $('#tablaEquiposModalCambio tbody').empty(); // Limpiar tabla actual
                // Rellenar la tabla de equipos en el modal
                $('#tablaEquiposModal tbody').empty(); // Limpiar tabla actual
                equipoRowCounter = 0; // Resetear contador
                equipoRowCounterCambio=0;

                if (ordenData.equipos && ordenData.equipos.length > 0) {
                    // Iterar sobre los equipos y agregarlos a la tabla
                    ordenData.equipos.forEach(function(equipo) {
                        if(equipo.estado==1){
                            // Equipo Activo
                        equipoRowCounter++;
                        const newRow = `
                            <tr id="equipo-row-${equipoRowCounter}">
                                <td>${equipoRowCounter}</td>
                                <td><input type="text" class="form-control form-control-sm input-placa" placeholder="Placa" value="${equipo.placa}" required></td>
                                <td><input type="text" class="form-control form-control-sm input-serial" placeholder="Serial" value="${equipo.serial}" required></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-equipo" data-row-id="${equipoRowCounter}">
                                        <i class="icon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        $('#tablaEquiposModal tbody').append(newRow);
                        }
                        
                        if(equipo.estado==0&&equipo.estado_devolucion==1){
                            // Equipo Cambio
                        equipoRowCounterCambio++;
                        const newRow = `
                            <tr id="equipo-row-${equipoRowCounter}">
                                <td>${equipoRowCounter}</td>
                                <td><input type="text" class="form-control form-control-sm input-placa" placeholder="Placa" value="${equipo.placa}" required></td>
                                <td><input type="text" class="form-control form-control-sm input-serial" placeholder="Serial" value="${equipo.serial}" required></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-equipo" data-row-id="${equipoRowCounterCambio}">
                                        <i class="icon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        $('#tablaEquiposModalCambio tbody').append(newRow);
                        }
                    });

                    if(res.data['TotalEquiposActivos']==0){
                        // Si no hay equipos activos, mostrar mensaje y ocultar botones
                         toastr.info(res.message, "Orden sin Equipos Activos.");
                         $('#btnBuscarContrato').hide();
                         $('#btnGuardarOrden').hide();     
                    }
                } else {
                    toastr.info("La orden encontrada no tiene equipos asociados.", "Info");
                }

                // Opcional: Abre el modal si no está ya abierto
                // $('#createOrden').modal('show'); 

            } else if (res.status === "error"){
                toastr.error(res.message || "Ocurrió un error al buscar la orden.", "Búsqueda Fallida.");
            } else if( res.status === "required") {            
                    toastr.error(res.message || "Ocurrió un error al buscar la orden.", "Faltan Datos.");
            }   
            else{
                toastr.info(res.message || "La búsqueda se completó con un estado desconocido.", "Estado Desconocido.");
                console.warn("Estado de respuesta desconocido del servidor (buscarOrden):", res);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX (buscarOrden):", textStatus, errorThrown, jqXHR.responseText);
            let errorMessage = "No se pudo conectar con el servidor para buscar la orden.";
            if (jqXHR.status) {
                errorMessage += ` (Código de estado: ${jqXHR.status})`;
            }
            if (jqXHR.responseText) {
                errorMessage += "\nDetalles: " + jqXHR.responseText.substring(0, 150) + "...";
            }
            toastr.error(errorMessage, "Error de Conexión o Servidor.");
        },
        complete: function() {
            // Habilitar el botón de búsqueda y restaurar su texto original
            $('#btnBuscarNumero').prop('disabled', false).html('<i class="icon-search"></i> Buscar');
        }
    });
}


function buscarEquiposActivosDevueltos(id_Orden) { // Cambiado a id_Orden para coincidir con la data
    $.ajax({
        url: "../controller/OrdenController.php?operador=buscarOrden",
        type: "POST",
        data: {
            "numeroOrden": id_Orden // Asegúrate de que este sea el nombre correcto del parámetro esperado por el backend
        },
        beforeSend: function() {
        },
        success: function(response) {
            let res;
            try {
                res = JSON.parse(response);
            } catch (e) {
                console.error("Error al parsear la respuesta del servidor (buscarOrden):", response, e);
                toastr.error("Respuesta inesperada del servidor al buscar la orden. (Error de formato)", "Error de Comunicación.");
                return;
            }
            // Manejo de respuesta del servidor
            if (res.status === "success") {
                toastr.success(res.message, "Búsqueda Exitosa.");
                const ordenData = res.data;

                // Limpiar ambas tablas antes de llenarlas
                $('#searchtablaEquiposModal tbody').empty();
                $('#searchtablaEquiposModalCambio tbody').empty();

                // Reiniciar contadores para las filas
                let equipoRowCounter = 0;
                let equipoRowCounterCambio = 0;

                if (ordenData.equipos && ordenData.equipos.length > 0) {
                    ordenData.equipos.forEach(function(equipo) {
                        let estadoTexto = '';
                        let targetTableBody = '';
                        let currentRowCounter; // Usaremos un contador específico para la fila actual

                        // Determinar el estado del equipo y la tabla de destino
                        if (equipo.estado_devolucion == 1 && equipo.estado == 0) {
                            estadoTexto = 'Devuelto';
                            targetTableBody = '#searchtablaEquiposModalCambio tbody';
                            equipoRowCounterCambio++;
                            currentRowCounter = equipoRowCounterCambio;
                        } else if (equipo.estado_devolucion == 0 && equipo.estado == 0) {
                            estadoTexto = 'Cambio';
                            targetTableBody = '#searchtablaEquiposModalCambio tbody';
                            equipoRowCounterCambio++;
                            currentRowCounter = equipoRowCounterCambio;
                        } else if (equipo.estado_devolucion == 0 && equipo.estado == 1) {
                            estadoTexto = 'Activo';
                            targetTableBody = '#searchtablaEquiposModal tbody';
                            equipoRowCounter++;
                            currentRowCounter = equipoRowCounter;
                        } else {
                            // En caso de un estado no contemplado, puedes decidir qué hacer
                            estadoTexto = 'Estado Desconocido';
                            targetTableBody = '#searchtablaEquiposModal tbody'; // Por defecto, a la tabla principal
                            equipoRowCounter++;
                            currentRowCounter = equipoRowCounter;
                            console.warn("Equipo con estado desconocido:", equipo);
                        }

                        // Construir la nueva fila con los campos readonly y el estado textual
                        const newRow = `
                            <tr id="equipo-row-${currentRowCounter}">
                                <td>${currentRowCounter}</td>
                                <td><input type="text" class="form-control form-control-sm input-placa" placeholder="Placa" value="${equipo.placa}" required readonly></td>
                                <td><input type="text" class="form-control form-control-sm input-serial" placeholder="Serial" value="${equipo.serial}" required readonly></td>
                                <td><input type="text" class="form-control form-control-sm input-estado" placeholder="Estado" value="${estadoTexto}" required readonly></td>
                            </tr>
                        `;
                        $(targetTableBody).append(newRow);
                    });
                } else {
                    toastr.info("La orden encontrada no tiene equipos asociados.", "Info");
                }

                // Opcional: Abre el modal si no está ya abierto
                // $('#createOrden').modal('show');

            } else if (res.status === "error") {
                toastr.error(res.message || "Ocurrió un error al buscar los equipos.", "Búsqueda Fallida.");
            } else if (res.status === "required") {
                toastr.error(res.message || "Ocurrió un error al buscar los equipos.", "Faltan Datos.");
            } else {
                toastr.info(res.message || "La búsqueda se completó con un estado desconocido.", "Estado Desconocido.");
                console.warn("Estado de respuesta desconocido del servidor (buscarOrden):", res);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX (buscarOrden):", textStatus, errorThrown, jqXHR.responseText);
            let errorMessage = "No se pudo conectar con el servidor para buscar la orden.";
            if (jqXHR.status) {
                errorMessage += ` (Código de estado: ${jqXHR.status})`;
            }
            if (jqXHR.responseText) {
                errorMessage += "\nDetalles: " + jqXHR.responseText.substring(0, 150) + "...";
            }
            toastr.error(errorMessage, "Error de Conexión o Servidor.");
        },
        complete: function() {
            // Habilitar el botón de búsqueda y restaurar su texto original
            $('#btnBuscarNumero').prop('disabled', false).html('<i class="icon-search"></i> Buscar');
            // Asegurarse de que el spinner se oculte incluso si hay un error en el success/error
            $('#loadingOverlay').hide();
        }
    });
}


$(document).ready(function() {

    // Contador global para dar IDs únicos a las filas de equipos
    let equipoRowCounter = 0;

     $('#btnBuscarNumero').on('click', function() {
        // Obtiene el valor del campo de número de registro
        const numeroNumerooBuscar = $('#txtNumeroRegistro').val();
        if (numeroNumerooBuscar) {
            // Llama a la función para buscar la orden existente
            buscarOrdenExistente(numeroNumerooBuscar); // Llama a la nueva función de búsqueda
        } else {
            // Si el campo está vacío, muestra un mensaje de advertencia
            toastr.warning("Por favor, ingrese un contrato para buscar.", "Campo Vacío");
        }
    });

     $('#btnBuscarContrato').on('click', function() {
        // Obtiene el valor del campo de número de contrato
        const numeroContratoBuscar = $('#txtNumeroContrato').val();
        if (numeroContratoBuscar) {
            // Llama a la función para buscar el contrato existente
            buscarContratoExistente(numeroContratoBuscar); // Llama a la nueva función de búsqueda
        } else {
            // Si el campo está vacío, muestra un mensaje de advertencia
            toastr.warning("Por favor, ingrese un Contrato para buscar.", "Campo Vacío");
        }
    });

    $('#btnGuardarOrden').on('click', function() {
        // Recopila los datos del formulario de la orden
        const ordenCompra = $('#txtOrdenCompra').val();
        const ordenServicio = $('#txtOrdenServicio').val();
        const fechaEntrega = $('#txtFechaEntrega').val();
        const idTipoOrden = $('#sltTipoOrden').val();
        const numeroRegistro = $('#txtNumeroRegistro').val();
        const textoTipoOrden = $('#sltTipoOrden').find('option:selected').text();
        const numeroContrato = $('#txtNumeroContrato').val();

        // Recopila equipos de la tabla principal (Equipos a instalar / a devolver)
        const equiposParaRegistrar = []; // Estos son los que se insertarán como nuevos
        const equiposParaDevolver = []; // Estos son los que ya existen y se marcarán como "salida"

        // Recopila los equipos de la tabla principal. La interpretación depende del Tipo de Orden.
        $('#tablaEquiposModal tbody tr').each(function() {
            // Obtiene los valores de placa y serial de cada fila
            const placa = $(this).find('.input-placa').val();
            const serial = $(this).find('.input-serial').val();
            if (placa && serial) {
                // Si es Instalación o Cambio (los "nuevos" de la tabla original) 
                    equiposParaRegistrar.push({ placa: placa, serial: serial });
            }
        });

        // Para el tipo "Cambio", los "equiposParaRegistrar" son los de la segunda tabla
        if (idTipoOrden==2) {
            // Recopila los equipos de la tabla de cambio (Equipos a devolver)
            $('#tablaEquiposModalCambio tbody tr').each(function() {
                const placa = $(this).find('.input-placa').val();
                const serial = $(this).find('.input-serial').val();
                if (placa && serial) {
                    equiposParaDevolver.push({ placa: placa, serial: serial });
                }
            });
        }

        // --- Validaciones en Frontend antes de enviar ---
        if (!ordenCompra || !ordenServicio || !fechaEntrega || !idTipoOrden || !numeroContrato) {
            // Validación básica de los campos de la orden
            toastr.warning("Por favor, complete todos los campos principales de la orden.", "Datos Incompletos.");
            return;
        }

        if (idTipoOrden == 1 && equiposParaRegistrar.length === 0) { // Instalación
            toastr.warning("Para una Instalación, debe agregar al menos un equipo.", "Equipos Faltantes.");
            return;
        }

        if (idTipoOrden == 2 && equiposParaDevolver.length == 0 && equiposParaRegistrar.length == 0) { // Cambio
            toastr.warning("Para un Cambio, debe seleccionar los equipos a devolver.", "Equipos Faltantes.");
            return;
        }

        if (idTipoOrden == 3) { // Devolucion
            if (equiposParaRegistrar.length == 0) {
                toastr.warning("Para un Cambio, se requieren tanto los equipos a devolver como los nuevos equipos.", "Equipos Faltantes.");
                return;
            }
        }
        // Fin de las validaciones en Frontend

        // Envío de datos al controlador
        $.ajax({
            url: "../controller/OrdenController.php?operador=registrarOrden",
            type: "POST",
            dataType: "json", // Esperamos una respuesta JSON
            data: {
                "ordenCompra": ordenCompra,
                "ordenServicio": ordenServicio,
                "fechaEntrega": fechaEntrega,
                "idTipoOrden": idTipoOrden,
                "numeroRegistro": numeroRegistro,
                "numeroContrato": numeroContrato,
                "equiposParaRegistrar": equiposParaRegistrar, // Equipos que entran (nuevos o instalados)
                "equiposParaDevolver": equiposParaDevolver   // Equipos que salen (devueltos o parte de un cambio)
            },
            beforeSend: function() {
                $('#btnGuardarOrden').prop('disabled', true).text('Guardando...');
            },
            success: function(res) {
                // Manejo de la respuesta del servidor
                if (res.status === "success") {
                    toastr.success(res.message || "Operación de orden completada exitosamente.", "Éxito.");
                    if (window.table && typeof window.table.ajax !== 'undefined') {
                        window.table.ajax.reload(); // Recargar la tabla principal de DataTables
                    }
                    cerrarModal(); // Limpiar y cerrar el modal
                } else if (res.status === "error" || res.status === "required") {
                    toastr.error(res.message || "Ocurrió un error al procesar la orden.", "Fallo.");
                } else {
                    toastr.info(res.message || "La operación se completó con un estado desconocido.", "Estado Desconocido.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown, jqXHR.responseText);
                toastr.error("No se pudo conectar con el servidor o hubo un error interno. Detalles: " + jqXHR.responseText, "Error de Conexión o Servidor.");
            },
            complete: function() {
                $('#btnGuardarOrden').prop('disabled', false).text('Guardar Orden');
            }
        });
    });

    $('#sltTipoOrden').on('change', function() {
    idTipoOrden = $('#sltTipoOrden').val(); // Obtiene el valor del select de tipo de orden
        // Cuando el valor cambia, llama a la función que gestiona el estado de los campos
    
    switch (idTipoOrden) {
        case "1":
            // Para 'Instalacion', txtNumeroOrden debe ser de solo lectura y el botón de búsqueda oculto.
            $('#txtNumeroRegistro').attr('readonly', true); // Hacer de solo lectura
            $('#btnBuscarNumero').hide();   // Ocultar el botón
            $('#btnAddEquipoCambio').hide(); // Ocultar el botón de agregar equipo
            $('#tablaEquiposModalCambio').hide();
            $('#tituloEquiposNuevos').hide(); // Ocultar el título de equipos nuevos  
            $('#btnAddEquipo').show();
            $('#tablaEquiposModal input').attr('readonly', false);
            $('#tablaEquiposModal button').show();                             
            break;
        case "2":
            // Para 'Cambio', txtNumeroOrden debe ser editable y el botón de búsqueda visible.
            $('#btnBuscarContrato').hide();     
            $('#txtNumeroRegistro').attr('readonly', false); // Hacer editable
            $('#btnBuscarNumero').show();                     // Mostrar el botón
            $('#btnAddEquipoCambio').show(); // Ocultar el botón de agregar equipo
            $('#tablaEquiposModalCambio').show();   
            $('#tituloEquiposNuevos').show(); // Mostrar el título de equipos nuevos
            $('#btnAddEquipo').show();
            $('#tablaEquiposModal input').attr('readonly', false);
            $('#tablaEquiposModal button').show();      
            break;
        case "3":
            // Para 'Devolucion' , txtNumeroOrden debe ser editable y el botón de búsqueda visible.
            $('#txtNumeroRegistro').attr('readonly', false); // Hacer editable
            $('#btnBuscarNumero').show();                     // Mostrar el botón
            $('#btnAddEquipoCambio').hide(); // Ocultar el botón de agregar equipo
            $('#tablaEquiposModalCambio').hide();  
            $('#tituloEquiposNuevos').hide(); // Ocultar el título de equipos nuevos
            $('#btnAddEquipoCambio').hide();
            $('#btnAddEquipo').hide();
            $('#tablaEquiposModal input').attr('readonly', true);
            $('#tablaEquiposModal button').hide();
            break;
        default:
            // Para la opción "Seleccione un Tipo de Orden" o cualquier otra no especificada,
            // txtNumeroOrden es editable y el botón de búsqueda oculto.
            $('#txtNumeroRegistro').attr('readonly', true); // Hacer editable
            $('#btnBuscarNumero').hide();                     // Ocultar el botón
            $('#btnAddEquipoCambio').hide(); // Ocultar el botón de agregar equipo
            $('#tablaEquiposModalCambio').hide();                  
            $('#tituloEquiposNuevos').hide(); // Ocultar el título de equipos nuevos
            $('#tablaEquiposModal input').attr('readonly', false);
            $('#tablaEquiposModal button').show();               
            break;
}   
 });

        $('#btnAddEquipoCambio').on('click', function() {
            // Incrementa el contador para el ID de la nueva fila
        equipoRowCounterCambio++; // Incrementa el contador para el ID de la nueva fila
        const newRow = `
            <tr id="equipo-row-${equipoRowCounterCambio}">
                <td>${equipoRowCounterCambio}</td>
                <td><input type="text" class="form-control form-control-sm input-placa" placeholder="Placa" required></td>
                <td><input type="text" class="form-control form-control-sm input-serial" placeholder="Serial" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-remove-equipo" data-row-id="${equipoRowCounterCambio}">
                        <i class="icon-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#tablaEquiposModalCambio tbody').append(newRow); // Añade la fila al cuerpo de la tabla
    });

    $('#tablaEquiposModalCambio tbody').on('click', '.btn-remove-equipo', function() {
        // Maneja la eliminación de filas de la tabla de equipos de cambio
        const rowIdToRemove = $(this).data('row-id');
        $(`#equipo-row-${rowIdToRemove}`).remove(); // Elimina la fila completa
        actualizarNumeracionFilasCambio(); // Vuelve a numerar las filas para mantener el orden visual
    });

    /**
     * Actualiza la numeración de la primera columna de la tabla de equipos después de agregar/eliminar.
     */
    function actualizarNumeracionFilasCambio() {
        // Actualiza la numeración de las filas en la tabla de equipos de cambio
        $('#tablaEquiposModalCambio tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1); // Actualiza el número de la primera celda
        });
    }

        $('#btnAddEquipo').on('click', function() {
        equipoRowCounter++; // Incrementa el contador para el ID de la nueva fila
        const newRow = `
            <tr id="equipo-row-${equipoRowCounter}">
                <td>${equipoRowCounter}</td>
                <td><input type="text" class="form-control form-control-sm input-placa" placeholder="Placa" required></td>
                <td><input type="text" class="form-control form-control-sm input-serial" placeholder="Serial" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-remove-equipo" data-row-id="${equipoRowCounter}">
                        <i class="icon-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#tablaEquiposModal tbody').append(newRow); // Añade la fila al cuerpo de la tabla
    });

    /**
     * Maneja la eliminación de filas de la tabla de equipos.
     * Utiliza delegación de eventos para manejar clics en botones de eliminación dinámicamente agregados.
     */
    $('#tablaEquiposModal tbody').on('click', '.btn-remove-equipo', function() {
        // Maneja la eliminación de filas de la tabla de equipos
        const rowIdToRemove = $(this).data('row-id');
        $(`#equipo-row-${rowIdToRemove}`).remove(); // Elimina la fila completa
        actualizarNumeracionFilas(); // Vuelve a numerar las filas para mantener el orden visual
    });

    /**
     * Actualiza la numeración de la primera columna de la tabla de equipos después de agregar/eliminar.
     */
    function actualizarNumeracionFilas() {
        $('#tablaEquiposModal tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1); // Actualiza el número de la primera celda
        });
    }
});

function llenarSelectTipoOrden(){
    $.ajax({
        // Realiza una solicitud AJAX para llenar el select de tipo de orden
        url: '../controller/OrdenController.php?operador=llenarTipoOrden',
        type: 'POST',
        beforeSend: function(response) {
            // Puedes mostrar un spinner aquí si lo deseas
        },
        success: function(response) {
            if (response) { // Verificar si hay alguna respuesta
                try {
                    let data = $.parseJSON(response); // O JSON.parse(response)
                    var options = '<option value="">Seleccione un Tipo de Orden</option>';
                    if (data && data.length > 0) { // Asegúrate de que 'data' sea un array y no esté vacío
                        for (var i = 0; i < data.length; i++) {
                            options += '<option value="' + data[i]['id_TipoOrden'] + '">' + data[i]['Tipo_Orden'] + '</option>';
                        }
                    } else {
                        console.warn("La respuesta de llenarTipoOrden está vacía o no tiene datos.");
                        options += '<option value="">No hay tipos de orden disponibles</option>';
                    }
                    $('#sltTipoOrden').html(options);
                    // Una vez que el select se llena, llama a manejarEstadoNumeroOrden
                    // para establecer el estado inicial correcto del campo y botón.
                } catch (e) {
                    console.error("Error al parsear la respuesta de llenarTipoOrden:", response, e);
                    toastr.error("Error de formato al cargar los tipos de orden.", "ERROR");
                }
            } else {
                toastr.error("Respuesta vacía al cargar los tipos de orden.", "ERROR");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error AJAX al cargar tipos de orden:", textStatus, errorThrown, jqXHR.responseText);
            toastr.error("Error al cargar los tipos de orden.", "ERROR de Conexión");
        }
    });
}

function listarOrden(){
// Inicializa la tabla de órdenes con DataTables
    table = $('#Tabla_Orden').DataTable({
        // Configuración de DataTables
        pageLength:10,
        responsive:true,
        processing:true,
        ajax: "../controller/OrdenController.php?operador=listarOrden",         
        columns: [
            { data: "Orden De Compra" },
            { data: "Orden de Servicio" },
            { data: "Numero de Registro" },
            { data: "Fecha de Entrega" },
            { data: "Tipo de Orden" },
            { data: "Numero de Contrato" },
            { data: "Total de Equipos Activos" },
            { data: "Total de Equipos Devueltos" },  
            { data: "Orden Original"},   
            { data: "Empresa"},
            { data: "op", "orderable": false }
            ],
            "autoWidth": false, 
            // Configuración de los anchos de las columnas
        columnDefs: [
        { "width": "10%", "targets": 0 },
        { "width": "10%", "targets": 1 },
        { "width": "5%", "targets": 2 },
        { "width": "15%", "targets": 3 },
        { "width": "15%", "targets": 4 },
        { "width": "15%", "targets": 5 }, // Ajusta los porcentajes para que sumen 100%
        { "width": "5%", "targets": 6 },
        { "width": "5%", "targets": 7 },
        { "width": "10%", "targets": 8 },
        { "width": "5%", "targets": 9 }, // Ancho para la columna "op"
        { "width": "5%", "targets": 10 } // Ancho para la nueva columna "Empresa"
        ]
    });
}