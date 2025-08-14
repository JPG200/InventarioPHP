var table;

init(); // Inicializar la tabla y cerrar los modales

// Función para inicializar la tabla y cerrar los modales
function init(){
    LlenarTablaregDev();
    cerrarModalUpdate();
    cerrarModal();
}

// Función para llenar la tabla de devoluciones
function LlenarTablaregDev(){
table = $('#Tabla_Devolucion').DataTable({
pageLength:10,
responsive:true,
processing:true,
ajax:"../controller/DevolucionController.php?operador=listar_Devolucion",
columns:[
    // Definición de las columnas de la tabla
    {data:"Numero de Registro",'visible': false},
    {data:"Acta"},
    {data:"Empleado"},
    {data:"Placa"},
    {data:"Empresa"},
    {data:"Acta Devolucion"},
    {data:"Fecha de Entrega"},
    {data:"Fecha de Devolucion"},
    {data:"Estado"},
    {data:"op"}
],
// Configuración de la tabla
            "autoWidth": false, 
});
}


function cerrarModalUpdate(){
    // Limpiar los campos del modal de actualización
    $('#id_Dev').val("");
    $('#txtactaupdate').val("");
    $('#txtasigupdate').val("");
    $('#txtplacaupdate').val("");
    $('#txtdescripcionupdate').val("");
    $('#txtobservacionesupdate').val("");
    $('#updateDevolucion').modal('hide'); // Cerrar el modal después de registrar
}

function cerrarModal(){
    // Limpiar los campos del modal de creación
    $('#id_Dev').val("");
    $('#txtactacrear').val("");
    $('#txtasigcrear').val("");
    $('#txtplacacrear').val("");
    $('#txtdescripcioncrear').val("");
    $('#txtobservacionescrear').val("");
    $('#createDevolucion').modal('hide'); // Cerrar el modal después de registrar
}

// Función para registrar una devolución
// Esta función se llama cuando se hace clic en el botón "Registrar Devolución"
// y envía los datos del formulario al servidor para su procesamiento
function RegistrarDevolucion(){
    // Obtener los valores de los campos de entrada
    acta_asig = $('#txtasigcrear').val();
    observaciones = $('#txtobservacionescrear').val();
    acta = $('#txtactacrear').val();

    $.ajax({
        // Enviar los datos al servidor
        url: "../controller/DevolucionController.php?operador=registrar_Devolucion",
        type: "POST",
        data: {
            "acta_asig": acta_asig,
            "observaciones": observaciones,
            "acta": acta
        },
        beforeSend: function(response){
        },
        success: function(response){
            if(response == "success"){
              toastr.success("Devolucion Registrada exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal
            }else if(response == "error"){
              toastr.error("Devolucion no registrada", "Registro Fallido."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal
            }else if(response == "required"){
              toastr.info("Devolucion no registrada", "Faltan Datos."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal           
              }
        }
    });
}

// Función para buscar información de un acta de asignación
// Esta función se llama cuando se ingresa un número de acta en el campo de búsqueda
function BuscarInformacionActaAsignacion($acta,op){
    // Obtener los valores de los campos de entrada
    parametros = {
        "acta": $acta,},
    $.ajax({
        data: parametros,
        url: '../controller/DevolucionController.php?operador=buscarDevolucion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
            if(response && response.length > 0){
            data = $.parseJSON(response);
            $("#txtasigcrear").val($acta);
            $('#txtasigcrear').val(data[0]['ActaAsig']);
            $('#txtplacacrear').val(data[0]['placa']);
            $('#txtdescripcioncrear').val(data[0]['descripcion']);
            if(data[0]['observaciones']!=null && data[0]['observaciones']!=""){
                // Si hay observaciones se hace el cambio de la operacion
                if(op=="registrar"){
                    op="editar";
                }

                // Asignar los valores a los campos del formulario
                $('#id_Dev').val(data[0]['id_Dev']);
                $('#txtobservacionescrear').val(data[0]['observaciones']);
                $('#txtactacrear').val(data[0]['ActaDev']);

                // lógica para manejar la operación según el caso
                switch(op) {
                    case "editar":
                        $("#txtasigupdate").val($acta);
                        $('#txtasigupdate').val(data[0]['ActaAsig']);
                        $('#txtplacaupdate').val(data[0]['placa']);
                        $('#txtdescripcionupdate').val(data[0]['descripcion']);
                        $('#id_Dev').val(data[0]['id_Dev']);
                        $('#txtobservacionesupdate').val(data[0]['observaciones']);
                        $('#txtactaupdate').val(data[0]['ActaDev']);
                        toastr.info("Actualice los datos del equipo.", "Actualice los datos"); // Mostrar mensaje de éxito      
                        $("#btnGuardar").hide();
                        $("#btnActualizar").show();
                    break;
                    case "eliminar":
                        id_Reg=data[0]['id_Dev'];
                        AlertaDesactivar(id_Reg,data[0]['placa']);
                    break;
                    case "activar":
                        id_Reg=data[0]['id_Dev'];
                        AlertaActivar(id_Reg,data[0]['placa']);
                    break;
                }
            }else{
             toastr.success("No se ha registrado la devolucion del equipo.", "Realice la devolucion"); // Mostrar mensaje de éxito
             $("#btnGuardar").show();
             $("#btnActualizar").hide();
            }
           } else{
            toastr.success("Error al buscar la Asignacion", "ERROR"); // Mostrar mensaje de éxito
            }
            
        }
        });
}

// Funciones para mostrar alertas de confirmación antes de activar o desactivar una devolución
    function AlertaActivar(id_Reg,placa){
    Swal.fire({
        title: '¿Seguro?',
        html: "¿Está seguro de Actualizar la Devolucion del Equipo con la placa: <h5>"+ placa +"</h5>?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
    }).then((result) => {
        if (result.value) {
            ActivarDevolucion(id_Reg);
            toastr.success("Devolucion Actualizada exitosamente", "Actualizacion Exitosa."); // Mostrar mensaje de éxito
            table.ajax.reload(); // Recargar la tabla después de registrar el equipo
            cerrarModalUpdate(); // Limpiar los campos del modal
            cerrarModal(); // Limpiar los campos del modal
        }
        else {
            toastr.info("Actualizacion cancelada", "Operación Cancelada."); // Mostrar mensaje de cancelación
        }
    });
    }

    // Función para mostrar una alerta de confirmación antes de desactivar una devolución
    function AlertaDesactivar(id_Reg,placa){
    Swal.fire({
        title: '¿Seguro?',
        html: "¿Está seguro de entregar el Equipo con la placa: <h5>"+ placa +"</h5>?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
    }).then((result) => {
        if (result.value) {
            EliminarDevolucion(id_Reg);
            toastr.success("Equipo Entregado exitosamente", "Entrega Exitosa."); // Mostrar mensaje de éxito
            table.ajax.reload(); // Recargar la tabla después de registrar el equipo
            cerrarModalUpdate(); // Limpiar los campos del modal
        }
        else {
            toastr.info("Entrega cancelada", "Operación Cancelada."); // Mostrar mensaje de cancelación
        }
    });
    }

    // Función para activar una devolución
    // Esta función se llama cuando se confirma la activación de una devolución
    function ActivarDevolucion(id_Dev){
    console.log(id_Dev);
    $.ajax({
        data: {"id_Dev": id_Dev},
        url: '../controller/DevolucionController.php?operador=activar_Devolucion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            if (response == "success") {
                toastr.success("Registro Activado.", "Devolucion Actualizada exitosamente"); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al Actualizar la Devolucion.");
                table.ajax.reload(); // Recargar la tabla
            }
        },
        error: function() {
            toasrt.error("ERROR","Error en la conexión. Intente nuevamente.");
        }
    });
    }

    // Función para eliminar una devolución
    // Esta función se llama cuando se confirma la eliminación de una devolución
    function EliminarDevolucion(id_Dev){
    parametros = {
        "id_Dev": id_Dev
        },
    $.ajax({
        data: parametros,
        url: '../controller/DevolucionController.php?operador=eliminar_Devolucion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response);

            if (response == "success") {
                toastr.success("Devolucion eliminada exitosamente", "Eliminación Exitosa."); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla después de registrar el equipo
                cerrarModalUpdate(); // Limpiar los campos del modal
            } else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al eliminar.");
                table.ajax.reload(); // Recargar la tabla
                cerrarModalUpdate(); // Limpiar los campos del modal
            }
        }
    });
    }

    // Función para actualizar una devolución
    // Esta función se llama cuando se hace clic en el botón "Actualizar Devolución"
    function ActualizarDevolucion(){
    acta_asig = $("#txtasigupdate").val();
    placa = $("#txtplacaupdate").val();
    descripcion = $("#txtdescripcionupdate").val();
    id_Dev = $("#id_Dev").val();
    observaciones = $("#txtobservacionesupdate").val();
    acta_dev = $("#txtactaupdate").val();
    parametros = {
        "acta_asig": acta_asig,
        "placa": placa,
        "descripcion": descripcion,
        "id_Dev": id_Dev,
        "observaciones": observaciones,
        "acta_dev": acta_dev
        },
    $.ajax({
        data: parametros,
        url: '../controller/DevolucionController.php?operador=actualizar_Devolucion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response);
                        if (response == "success") {
                            toastr.success("Devolucion actualizada exitosamente", "Actualización Exitosa."); // Mostrar mensaje de éxito
                            table.ajax.reload(); // Recargar la tabla después de registrar el equipo
                            cerrarModalUpdate(); // Limpiar los campos del modal
                        } 
                        else if(response == "required"){
                            toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                            cerrarModalUpdate(); // Limpiar los campos del modal
                        }else{
                            toastr.error("Intente nuevamente", "Error al Actualizar.");
                            table.ajax.reload(); // Recargar la tabla
                            cerrarModalUpdate(); // Limpiar los campos del modal
                        }
        }
        });
    
    }