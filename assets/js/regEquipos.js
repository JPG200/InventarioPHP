var table;


init();// Función para inicializar el DataTable

function init(){
    // Inicializar el DataTable
LlenarTablaregEquipos();
LlenarSelectorEmpresa();
$("#btnGuardar").hide();
$("#btnActualizar").hide();
$('#txtplacacrear').val("");
$('#txtserialcrear').val("");
$('#txtdescripcioncrear').val("");
$('#txtobservacionescrear').val("");
$('#txtaccesorioscrear').val("");
$('#txtempresacrear').val("");
}

function LlenarTablaregEquipos(){
// Configuración del DataTable
table = $('#Tabla_regEquipos').DataTable({
pageLength:10,
responsive:true,
processing:true,
ajax:"../controller/regEquiposController.php?operador=listar_regEquipos",
// Configuración de la tabla
columns:[
    {data:"Numero de Registro",'visible': false},
    {data:"Placa"},
    {data:"Serial"},
    {data:"Descripcion"},
    {data:"Observaciones"},
    {data:"Accesorios"},
    {data:"Empresa"},
    {data:"Fecha de Ingreso"},
    {data:"Fecha de Finalizacion"},
    {data:"Estado"},
    {data:"op"}
],

            "autoWidth": false, 
});
}

function cerrarModal(){
    // Limpiar los campos del modal
    $('#txtplacacrear').val("");
    $('#txtserialcrear').val("");
    $('#txtdescripcioncrear').val("");
    $('#txtobservacionescrear').val("");
    $('#txtaccesorioscrear').val("");
    $('#txtempresacrear').val("");
    $("#btnGuardar").hide();
    $("#btnActualizar").hide();
    $('#createregEquipo').modal('hide'); // Cerrar el modal después de registrar
}

function BuscarEquipo(placa,op){
    // Obtener los valores de los campos de entrada
    parametros = {
        "placa": placa,
        },
    $.ajax({
        // Enviar los parámetros al servidor
        data: parametros,
        url: '../controller/regEquiposController.php?operador=buscarEquipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            if(response && response.length > 0){
                // Si la respuesta es exitosa, procesar los datos
                data = $.parseJSON(response);                         
                $("#btnGuardar").hide();
                $("#btnActualizar").hide();
                if(op=="registrar" || op=="editar"){
                    // Si es un registro o edición, mostrar los datos del equipo
                        $("#txtplacacrear").val(placa);
                        $('#txtserialcrear').val(data[0]['serial']);
                        if(data[0]['descripcion']!=null && data[0]['descripcion']!=""){
                            // Si la descripción no es nula o vacía, mostrar los datos del equipo
                            $('#id_Registro').val(data[0]['id_Reg']);
                            $("#txtplacacrear").val(placa);
                            $('#txtdescripcioncrear').val(data[0]['descripcion']);
                            $('#txtobservacionescrear').val(data[0]['observaciones']);
                            $('#txtaccesorioscrear').val(data[0]['accesorios']);
                            var nombreEmpresaEncontrada = data[0]['empresa'];

                            // Buscar la opción en el select que coincida con el nombre de la empresa
                            $('#txtempresacrear option').each(function() {
                                // Comparar el texto de la opción con el nombre de la empresa
                                if ($(this).text().toUpperCase() === nombreEmpresaEncontrada.toUpperCase()) {
                                    $('#txtempresacrear').val($(this).val()); // Seleccionar la opción por su valor
                                    return false; // Romper el bucle each una vez encontrada la coincidencia
                                }
                            });
                            if(op=="editar"){
                                // Si es una edición, mostrar los datos del equipo
                                toastr.info("Actualice los datos del equipo.", "Actualice los datos"); // Mostrar mensaje de éxito      
                                $("#btnGuardar").hide();
                                $("#btnActualizar").show();
                            }else{
                                // Si es un registro, mostrar los datos del equipo
                                toastr.error("El equipo ya esta registrado.", "Equipo ya registrado"); // Mostrar mensaje de éxito      
                                $("#btnGuardar").hide();
                                $("#btnActualizar").hide();
                            }
                        }else{
                            // Si la descripción es nula o vacía, limpiar los campos del modal
                            $('#txtdescripcioncrear').val("");
                            $('#txtobservacionescrear').val("");
                            $('#txtaccesorioscrear').val("");
                            $('#txtempresacrear').val("");
                            toastr.success("El equipo no se ha registrado.", "Registre los datos del equipo"); // Mostrar mensaje de éxito
                            $("#btnGuardar").show();
                            $("#btnActualizar").hide();
                        }
                    }else if(op=="eliminar"){
                        // Si es una eliminación, mostrar los datos del equipo
                        id_Reg=data[0]['id_Reg'];
                        AlertaDesactivar(id_Reg,data[0]['placa']);
                    }else if(op=="activar"){
                        // Si es una activación, mostrar los datos del equipo
                        id_Reg=data[0]['id_Reg'];
                        console.log(id_Reg);
                        AlertaActivar(id_Reg,data[0]['placa']);
                    }

            }else{
            toastr.success("Error al buscar el equipo", "ERROR"); // Mostrar mensaje de éxito
            }
        }
        });
}

function AlertaActivar(id_Reg,placa)
{
    // Mostrar una alerta de confirmación antes de activar el registro
    Swal.fire({
        title: "Seguro?",
        html: "Se activara el registro con el serial: <h5>" + placa+"?</h5>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
      }).then((result) => {
        if (result.value) {
            console.log(id_Reg);
          Swal.fire({
            title: "Activado!",
            text: "El registro ha sido activado.",
            type: "success"
          });
          ActivarRegistroEquipo(id_Reg);
        }
      });

}

function ActivarRegistroEquipo(id_Reg){
    // Activar el registro del equipo
    // Enviar una solicitud AJAX al servidor para activar el registro
    console.log(id_Reg);
    $.ajax({
        data: {"id_Reg": id_Reg},
        url: '../controller/regEquiposController.php?operador=activarRegEquipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
            if (response == "sucess") {
                toastr.success("Registro Activado.", "Registro de Equipo Activado exitosamente"); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al Activar el registro.");
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

function AlertaDesactivar(id_Reg,placa)
{
    // Mostrar una alerta de confirmación antes de desactivar el registro
    Swal.fire({
        title: "Seguro?",
        html: "Se desactivara el registro con el serial: <h5>" + placa+"?</h5>",
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
          // Llamar a la función para eliminar el equipo
          EliminarEquipo(id_Reg);
        }
      });

}


function LlenarSelectorEmpresa(){
    // Llenar el selector de empresas
    $.ajax({
        // Enviar una solicitud AJAX al servidor para obtener las empresas
        url: '../controller/regEquiposController.php?operador=LlenarSelectEmpresas',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
            if(response && response.length > 0){
                data = $.parseJSON(response);
                var options = '<option value="">Seleccione una empresa</option>';
                for (var i = 0; i < data.length; i++) {
                    options += '<option value="' + data[i]['id_Empresa'] + '">' + data[i]['Empresa'] + '</option>';
                }
                $('#txtempresacrear').html(options);
                $('#txtempresaupdate').html(options);
            }else{
            toastr.error("Error al cargar las empresas", "ERROR"); // Mostrar mensaje de éxito
            }
        }
        });
}

function RegistrarEquipo(){
    // Obtener los valores de los campos de entrada
    placa = $("#txtplacacrear").val();
    serial = $("#txtserialcrear").val();
    descripcion = $("#txtdescripcioncrear").val();
    observaciones = $("#txtobservacionescrear").val();
    accesorios = $("#txtaccesorioscrear").val();
    empresa = $("#txtempresacrear").val();
    // Validar que los campos no estén vacíos
    parametros = {
        "placa": placa,
        "serial": serial,
        "descripcion": descripcion,
        "observaciones": observaciones,
        "accesorios": accesorios,
        "empresa": empresa
        },
    $.ajax({
        // Enviar los parámetros al servidor para registrar el equipo
        data: parametros,
        url: '../controller/regEquiposController.php?operador=registrarEquipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
                        if (response == "sucess") {
                            toastr.success("Equipo registrado exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
                            table.ajax.reload(); // Recargar la tabla después de registrar el equipo
                            cerrarModal(); // Limpiar los campos del modal
                        } 
                        else if(response == "registered"){
                            toastr.info("El equipo ya está registrado en la base de datos", "El equipo ya existe.");
                            cerrarModal(); // Limpiar los campos del modal
                        }else if(response == "error"){
                            toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                            table.ajax.reload(); // Recargar la tabla
                            cerrarModal(); // Limpiar los campos del modal
                        } else{
                            toastr.error("Intente nuevamente", "Error al registrar el equipo.");
                            table.ajax.reload(); // Recargar la tabla
                            cerrarModal(); // Limpiar los campos del modal
                        }
        }
        });
}

function EditarEquipo(){
    // Obtener los valores de los campos de entrada
    id_Reg=$("#id_Registro").val();
    placa = $("#txtplacacrear").val();
    descripcion = $("#txtdescripcioncrear").val();
    observaciones = $("#txtobservacionescrear").val();
    accesorios = $("#txtaccesorioscrear").val();
    empresa = $("#txtempresacrear").val();
// Validar que los campos no estén vacíos
    parametros = {
        "id_Reg":id_Reg,
        "placa": placa,
        "descripcion": descripcion,
        "observaciones": observaciones,
        "accesorios": accesorios,
        "empresa": empresa
        },
    $.ajax({
        // Enviar los parámetros al servidor para editar el equipo
        data: parametros,
        url: '../controller/regEquiposController.php?operador=editarEquipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
                if(data.length > 0){
                    if (response == "sucess") {
                        toastr.success("Registro actualizado exitosamente", "Registro Actualizado."); // Mostrar mensaje de éxito
                        table.ajax.reload(); // Recargar la tabla
                        $('#createregEquipo').modal('hide'); // Cerrar el modal después de registrar
                        cerrarModal(); // Limpiar los campos del modal
                    }else if(response == "error"){
                        toastr.error("Intente nuevamente", "Error al actualizar el registro.");
                        table.ajax.reload(); // Recargar la tabla
                        $('#createregEquipo').modal('hide'); // Cerrar el modal después de registrar
                    } else if(response = "required"){
                        toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                        $('#createregEquipo').modal('hide'); // Cerrar el modal después de registrar
                        cerrarModal(); // Limpiar los campos del modal
                    } else{
                        toastr.error("ERROR", "ERROR.");
                        $('#createregEquipo').modal('hide'); // Cerrar el modal después de registrar
                        cerrarModal(); // Limpiar los campos del modal
                    }   
                }
        }
        });
}

function EliminarEquipo(id_Reg){
    // Obtener los valores de los campos de entrada
    parametros = {
        "id_Reg": id_Reg,
        },
    $.ajax({
        // Enviar los parámetros al servidor para eliminar el equipo
        data: parametros,
        url: '../controller/regEquiposController.php?operador=eliminarEquipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
            if (response == "sucess") {
                toastr.success("Equipo desactivado exitosamente", "Registro Desactivado."); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Error al desactivar el equipo.", "Intente nuevamente");
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