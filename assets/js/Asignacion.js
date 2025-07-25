var table;

init();// Función para inicializar el DataTable

function init(){
LlenarTablaregAsig();
LlenarSelectorEmpleado();
LlenarSelectorEmpleadoActualizar();
}


function BuscarAsignacionPorId(id_Asig){
    parametros = {
        "id_Asig": id_Asig,
    },
    $.ajax({
        data: parametros,
        url: '../controller/AsignacionController.php?operador=buscarAsignacionId',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            if(response && response.length > 0){
                data = $.parseJSON(response);
                id_Asig=data[0]['id_Asig'];
                AlertaActivar(id_Asig,data[0]['placa']);
            } else{
                toastr.error("Error al buscar la Asignacion", "ERROR"); // Mostrar mensaje de éxito
            }
        }
    });
}

function BuscarAsignacion(placa,op){
    // Obtener los valores de los campos de entrada
    parametros = {
        "placa": placa,},
    $.ajax({
        data: parametros,
        url: '../controller/AsignacionController.php?operador=buscarAsignacion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            if(response && response.length > 0){
              data = $.parseJSON(response);
              $("#txtplacacrear").val(placa);
              if(data[0]['Acta']!="" && data[0]['Acta']!=null && op=="editar"){
                toastr.info("Actualice los datos de la Asignacion.", "Actualice los datos"); // Mostrar mensaje de éxito      
                $("#btnGuardar").hide();
                $("#btnActualizar").show();
                $('#id_Asig').val(data[0]['id_Asig']);
                $("#txtplacaupdate").val(placa);
                $('#txtdescripcionupdate').val(data[0]['descripcion']);
                $('#txtobservacionesupdate').val(data[0]['observaciones']);
                $('#txtactaupdate').val(data[0]['Acta']);
                var nombreEmpleadoEncontrada = data[0]['Empleado'];

                // Buscar la opción en el select que coincida con el nombre de la empresa
                $('#txtempleadoupdate option').each(function() {
                if ($(this).text().toUpperCase() === nombreEmpleadoEncontrada.toUpperCase()) {
                    $('#txtempleadoupdate').val($(this).val()); // Seleccionar la opción por su valor
                    return false; // Romper el bucle each una vez encontrada la coincidencia
                    }
                }); 
              }   
              if(data[0]['Acta']!="" && data[0]['Acta']!=null && op=="registrar"){
                toastr.info("Actualice los datos.", "Actualice los datos"); // Mostrar mensaje de éxito  
                $('#id_Asig').val(data[0]['id_Asig']);
                $("#txtplacacrear").val(placa);
                $('#txtdescripcioncrear').val(data[0]['descripcion']);
                $('#txtobservacionescrear').val(data[0]['observaciones']);
                $('#txtactacrear').val(data[0]['Acta']);
                var nombreEmpleadoEncontrada = data[0]['Empleado'];
                console.log(nombreEmpleadoEncontrada);
                // Buscar la opción en el select que coincida con el nombre del empleado
                $('#txtempleadocrear option').each(function() {
                if ($(this).text().toUpperCase() === nombreEmpleadoEncontrada.toUpperCase()) {
                    $('#txtempleadocrear').val($(this).val()); // Seleccionar la opción por su valor
                    return false; // Romper el bucle each una vez encontrada la coincidencia
                    }
                });    
                $("#btnGuardar").hide();
              }else if(op=="registrar"){
                toastr.info("Equipo no Asignado.", "Asigne el equipo"); // Mostrar mensaje de éxito  
                $("#txtplacacrear").val(placa);
                $('#txtdescripcioncrear').val(data[0]['descripcion']);
                $('#txtobservacionescrear').val(data[0]['observaciones']);
                $("#btnGuardar").show();
              }else if(op=="eliminar"){
                        id_Asig=data[0]['id_Asig'];
                        AlertaDesactivar(id_Asig,data[0]['placa']);
            }             
           } else{
            toastr.success("Error al buscar la Asignacion", "ERROR"); // Mostrar mensaje de éxito
            }
        }
        });
}

function LlenarTablaregAsig(){
table = $('#Tabla_Asignacion').DataTable({
pageLength:10,
responsive:true,
processing:true,
ajax:"../controller/AsignacionController.php?operador=listar_Asignacion",
columns:[
    {data:"Numero de Registro",'visible': false},
    {data:"Placa"},
    {data:"Empleado"},
    {data:"Area"},
    {data:"Acta"},
    {data:"Empresa"},
    {data:"Fecha Ingreso"},
    {data:"Fecha Devolucion"},
    {data:"Estado"},
    {data:"op"}
],
 "autoWidth": false,
});
}

function cerrarModal(){
    $('#id_Asig').val("");
    $('#txtempleadocrear').val("");
    $('#txtempleadoupdate').val("");
    $('#txtplacacrear').val("");
    $('#txtdescripcioncrear').val("");
    $('#txtobservacionescrear').val("");
    $('#txtactacrear').val("");
    $('#modalAsignacion').modal('hide'); // Cerrar el modal después de registrar
}

function cerrarModalUpdate(){
    $('#id_Asig').val("");
    $('#txtempleadoupdate').val("");
    $('#txtplacaoupdate').val("");
    $('#txtdescripcionoupdate').val("");
    $('#txtobservacionesoupdate').val("");
    $('#txtactaoupdate').val("");
    $('#updateAsignacion').modal('hide'); // Cerrar el modal después de registrar
}


function AlertaActivar(id_Asig,placa)
{
    Swal.fire({
        title: "Seguro?",
        html: "Se activara la Asignacion con del Equipo: <h5>" + placa+"?</h5>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
      }).then((result) => {
        if (result.value) {
            console.log(id_Asig);
          Swal.fire({
            title: "Activado!",
            text: "El registro ha sido activado.",
            type: "success"
          });
          ActivarAsignacion(id_Asig);
        }
      });

}

function ActivarAsignacion(id_Asig){
    console.log(id_Asig);
    $.ajax({
        data: {"id_Asig": id_Asig},
        url: '../controller/AsignacionController.php?operador=Activar_Asignacion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            if (response == "sucess") {
                toastr.success("Registro Activado.", "Asignacion Activada exitosamente"); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al Activar la Asignacion.");
                table.ajax.reload(); // Recargar la tabla
            }else{
                toastr.error("ERROR", "ERROR "+response);
            }
        },
        error: function() {
            toasrt.error("ERROR","Error en la conexión. Intente nuevamente.");
        }
    });

}

function AlertaDesactivar(id_Asig,placa)
{
    Swal.fire({
        title: "Seguro?",
        html: "Se desactivara la Asignacion del Equipo: <h5>" + placa+"?</h5>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: "Desactivado!",
            text: "El registro ha sido desactivado.",
            type: "success"
          });
          EliminarAsignacion(id_Asig);
        }
      });

}

function EliminarAsignacion(id_Asig){
    // Obtener los valores de los campos de entrada
    parametros = {
        "id_Asig": id_Asig,
        },
    $.ajax({
        data: parametros,
        url: '../controller/AsignacionController.php?operador=Eliminar_Asignacion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            if (response == "sucess") {
                toastr.success("La Asignacion desactivado exitosamente", "Registro Desactivado."); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Error al desactivar la Asignacion.", "Intente nuevamente");
                table.ajax.reload(); // Recargar la tabla
            }else{
                toastr.error("ERROR", "ERROR.");
            }
        },
        error: function() {
            toasrt.error("ERROR","Error en la conexión. Intente nuevamente.");
        }
        });
}

/*function EliminarAsignacion(id_Asig,placa){
    Swal.fire({
        title: '¿Está seguro de eliminar la Asignación?',
        text: "¡Confirme si es correcta la Informacion!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../controller/AsignacionController.php?operador=Eliminar_Asignacion',
                type: 'POST',
                data: {id_Asig: id_Asig, placa: placa},
                success: function(response){
                    if(response == "sucess"){
                        toastr.success("Asignación eliminada exitosamente", "Eliminación Exitosa."); // Mostrar mensaje de éxito
                        table.ajax.reload(); // Recargar la tabla después de eliminar
                    } else {
                        toastr.error("Error al eliminar la Asignación", "Error"); // Mostrar mensaje de error
                    }
                },
                error: function(){
                    toastr.error("Error al procesar la solicitud", "Error"); // Mostrar mensaje de error
                }
            });
        }
    }
    );
}
*/

function RegistrarAsignacion(){
    placa = $("#txtplacacrear").val();
    descripcion = $("#txtdescripcioncrear").val();
    observaciones = $("#txtobservacionescrear").val();
    acta = $("#txtactacrear").val();
    empleado = $("#txtempleadocrear").val();
    parametros = {
        "placa": placa,
        "descripcion": descripcion,
        "observaciones": observaciones,
        "acta": acta,
        "id_Empl": empleado
        },
    $.ajax({
        data: parametros,
        url: '../controller/AsignacionController.php?operador=registrarAsignacion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
                        if (response == "sucess") {
                            toastr.success("Equipo Asignado exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
                            table.ajax.reload(); // Recargar la tabla después de registrar el equipo
                            cerrarModal(); // Limpiar los campos del modal
                        } 
                        else if(response == "registered"){
                            toastr.info("El equipo ya está Asignado en la base de datos", "Ya esta Asignado.");
                            cerrarModal(); // Limpiar los campos del modal
                        }else if(response == "error"){
                            toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                            table.ajax.reload(); // Recargar la tabla
                            cerrarModal(); // Limpiar los campos del modal
                        } else{
                            toastr.error("Intente nuevamente", "Error al Asignar el equipo.");
                            table.ajax.reload(); // Recargar la tabla
                            cerrarModal(); // Limpiar los campos del modal
                        }
        }
        });
}

function LlenarSelectorEmpleado(){
    $.ajax({
        url: '../controller/AsignacionController.php?operador=LlenarSelectEmpleados',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            if(response && response.length > 0){
                data = $.parseJSON(response);
                var options = '<option value="">Seleccione un Empleado</option>';
                for (var i = 0; i < data.length; i++) {
                    options += '<option value="' + data[i]['id_Empl'] + '">' + data[i]['Empleado'] + '</option>';
                }
                $('#txtempleadocrear').html(options);
            }else{
            toastr.error("Error al cargar los Empleados", "ERROR"); // Mostrar mensaje de éxito
            }
        }
        });
}
function LlenarSelectorEmpleadoActualizar(){
    $.ajax({
        url: '../controller/AsignacionController.php?operador=LlenarSelectEmpleadosUpdate',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            if(response && response.length > 0){
                data = $.parseJSON(response);
                var options = '<option value="">Seleccione un Empleado</option>';
                for (var i = 0; i < data.length; i++) {
                    options += '<option value="' + data[i]['id_Empl'] + '">' + data[i]['Empleado'] + '</option>';
                }
                $('#txtempleadoupdate').html(options);
            }else{
            toastr.error("Error al cargar los Empleados", "ERROR"); // Mostrar mensaje de éxito
            }
        }
        });
    }
    function ActualizarAsignacion(){
    placa = $("#txtplacaupdate").val();
    descripcion = $("#txtdescripcionupdate").val();
    observaciones = $("#txtobservacionesupdate").val();
    acta = $("#txtactaupdate").val();
    empleado = $("#txtempleadoupdate").val();
    id_Asig = $("#id_Asig").val();
    parametros = {
        "placa": placa,
        "descripcion": descripcion,
        "observaciones": observaciones,
        "acta": acta,
        "id_Empl": empleado,
        "id_Asig": id_Asig
        },
    $.ajax({
        data: parametros,
        url: '../controller/AsignacionController.php?operador=Actualizar_Asignacion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
                        if (response == "sucess") {
                            toastr.success("Equipo Asignado actualizado exitosamente", "Actualización Exitosa."); // Mostrar mensaje de éxito
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