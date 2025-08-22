var table;

init();// Función para inicializar el DataTable


function init(){
// Inicializar el DataTable
$('#txtcedula').val("");
$('#txtnombre').val("");
$('#txtapellido').val("");
$('#txtemail').val("");
$('#txtareacrear').val("");
$('#txtcargo').val("");

listarEmpleados();
LlenarSelectArea();
}

// Función para listar los empleados en la tabla
function listarEmpleados(){
    // Configuración del DataTable
    table = $('#Tabla_Empleados').DataTable({
    pageLength:10,
    responsive:true,
    processing:true,
    ajax:"../controller/EmpleadosController.php?operador=listar_Empleados",
    columns:[
        {data:"Numero de Registro", 'visible': false},
        {data:"Cedula"},
        {data:"Nombre"},
        {data:"Apellido"},
        {data:"Email"},
        {data:"Cargo"},
        {data:"Area"},
        {data:"Estado"},
        {data:"op"}
    ],
    
            "autoWidth": false, 
    });
    }

    function cerrarModal(){
    // Limpiar los campos del modal
    $('#txtcedula').val("");
    $('#txtnombre').val("");
    $('#txtapellido').val("");
    $('#txtemail').val("");
    $('#txtareacrear').val("");
    $('#txtcargo').val("");
    $('#txtcedulaupdate').val("");
    $('#txtnombreupdate').val("");
    $('#txtapellidoupdate').val("");
    $('#txtemailupdate').val("");
    $('#txtareaupdate').val("");
    $('#txtcargoupdate').val("");
    
    $("#btnActualizar").show();
    $("#btnGuardar").show();
    $('#createEmpleado').modal('hide'); // Cerrar el modal después de registrar
}



    function RegistrarEmpleado(){
        // Obtener los valores de los campos del formulario
    cedula=$('#txtcedula').val();
    nombre=$('#txtnombre').val();
    apellido=$('#txtapellido').val();
    email=$('#txtemail').val();
    area=$('#txtareacrear').val();
    cargo=$('#txtcargo').val();
        // Validar que los campos no estén vacíos
     parametros = {
        "cedula": cedula,
        "nombre": nombre,
        "apellido": apellido,
        "email": email,
        "area": area,
        "cargo": cargo
        },
    $.ajax({
        // Enviar los datos al servidor
        data: parametros,
        url: '../controller/EmpleadosController.php?operador=RegistrarEmpleado',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response);
                        if (response == "sucess") {
                            toastr.success("Empleado registrado exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
                            table.ajax.reload(); // Recargar la tabla después de registrar el equipo
                            cerrarModal(); // Limpiar los campos del modal
                        } 
                        else if(response == "registered"){
                            toastr.info("El empleado ya está registrado en la base de datos", "El empleado ya existe.");
                            cerrarModal(); // Limpiar los campos del modal
                        }else if(response == "required"){
                            toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                            table.ajax.reload(); // Recargar la tabla
                            cerrarModal(); // Limpiar los campos del modal
                        } else{
                            toastr.error("Intente nuevamente", "Error al registrar el empleado.");
                            table.ajax.reload(); // Recargar la tabla
                            cerrarModal(); // Limpiar los campos del modal
                        }
        }
        });
    }

function BuscarEmpleado(id,op){
    // Realizar una solicitud AJAX para buscar el empleado por ID
    $.ajax({
        data: {"id_Empl": id},
        url: '../controller/EmpleadosController.php?operador=buscarEmpleado',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
             data = $.parseJSON(response);
                if(data.length > 0){
                    // Llenar los campos del modal con los datos del empleado encontrado
                    if(op=="editar"){
                        // Llenar los campos del modal de actualización
                        $('#id_Empleado').val(data[0]['id_Empl']);
                        $('#txtcedulaupdate').val(data[0]['cedula']);
                        $('#txtnombreupdate').val(data[0]['nombre']);
                        $('#txtapellidoupdate').val(data[0]['apellido']);
                        $('#txtemailupdate').val(data[0]['correo']);
                        var nombreAreaEncontrada = data[0]['area'];
                        $('#txtareaupdate option').each(function() {
                                if ($(this).text().toUpperCase() === nombreAreaEncontrada.toUpperCase()) {
                                    $('#txtareaupdate').val($(this).val()); // Seleccionar la opción por su valor
                                    return false; // Romper el bucle each una vez encontrada la coincidencia
                                }
                            }); 
                    }else if(op=="eliminar"){
                        //Alerta de desactivación
                        id_Empleado=data[0]['id_Empl'];
                        AlertaDesactivar(id_Empleado,data[0]['cedula']);
                    }else if(op=="activar"){
                        //Alerta de activación
                        id_Empleado=data[0]['id_Empl'];
                        AlertaActivar(id_Empleado,data[0]['cedula']);
                    }
            }
            }
        });
}

// Función para mostrar una alerta de confirmación antes de activar un empleado
function AlertaActivar(id_Empl,cedula)
{
    // Mostrar una alerta de confirmación utilizando SweetAlert
    // Si el usuario confirma, llamar a la función ActivarEmpleado
    Swal.fire({
        title: "Seguro?",
        html: "Se Activara el empleado con la cedula: <h5>" + cedula+"?</h5>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: "Activado!",
            text: "El Empleado ha sido activado.",
            type: "success"
          });
          ActivarEmpleado(id_Empl);
        }
      });

}

// Función para activar un empleado
function ActivarEmpleado(id_Empl){
    // Realizar una solicitud AJAX para activar el empleado 
    $.ajax({
        data: {"id_Empl": id_Empl},
        url: '../controller/EmpleadosController.php?operador=Activar_Empleado',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
            if (response == "sucess") {
                toastr.success("Registro Activado.", "Empleado Activado exitosamente"); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al Activar el Empleado.");
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

// Función para buscar un empleado por cédula al hacer clic en un botón
function BuscarEmpleadoBoton(cedula,op){
    // Realizar una solicitud AJAX para buscar el empleado por cédula
    $.ajax({
        // Enviar los datos al servidor
        data: {"cedula": cedula},
        url: '../controller/EmpleadosController.php?operador=buscarEmpleadoBoton',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
            data = $.parseJSON(response);
                if(data.length > 0){
                    if(op=="editar"){
                        $('#id_Empleado').val(data[0]['id_Empleado']);
                        $('#txtcedula').val(data[0]['cedula']);
                        $('#txtnombre').val(data[0]['nombre']);
                        $('#txtapellido').val(data[0]['apellido']);
                        $('#txtemail').val(data[0]['correo']);
                        $('#txtareacrear').val(data[0]['estado']);
                    }else if(op=="eliminar"){
                        id_Empleado=data[0]['id_Empleado'];
                        AlertaDesactivar(id_Empleado,data[0]['cedula']);
                    }else if(op=="activar"){
                        id_Empleado=data[0]['id_Empleado'];
                        AlertaActivar(id_Equip,data[0]['cedula']);
                    }else if(op=="buscar"){
                        $('#id_Empleado').val(data[0]['id_Empl']);
                        $('#txtcedula').val(data[0]['cedula']);
                        $('#txtnombre').val(data[0]['nombre']);
                        $('#txtapellido').val(data[0]['apellido']);
                        $('#txtemail').val(data[0]['correo']);
                        $('#txtareacrear').val(data[0]['estado']);
                        var nombreAreaEncontrada = data[0]['area'];
                        $('#txtareacrear option').each(function() {
                                if ($(this).text().toUpperCase() === nombreAreaEncontrada.toUpperCase()) {
                                    $('#txtareacrear').val($(this).val()); // Seleccionar la opción por su valor
                                    return false; // Romper el bucle each una vez encontrada la coincidencia
                                }
                            });
                        $("#btnGuardar").hide();
                    }
            }
            }
        });
}

// Función para actualizar un empleado
    function ActualizarEmpleado(){
        // Obtener los valores de los campos del formulario de actualización
        id_Empleado=$('#id_Empleado').val();
        cedula=$('#txtcedulaupdate').val();
        nombre=$('#txtnombreupdate').val();
        apellido=$('#txtapellidoupdate').val();
        email=$('#txtemailupdate').val();
        area=$('#txtareaupdate').val();
        cargo=$('#txtcargoupdate').val();
        parametros = {
            "id_Empleado":id_Empleado,
            "cedula": cedula,
            "nombre": nombre,
            "apellido": apellido,
            "email": email,
            "area": area,
            "cargo": cargo
        },$.ajax({
            // Enviar los datos al servidor para actualizar el empleado
            data: parametros,
            url: '../controller/EmpleadosController.php?operador=Actualizar_Empleado',
            type: 'POST',
            beforeSend: function(response){},
            success:function(response){
                // Procesar la respuesta del servidor
                if (response == "sucess") {
                    toastr.success("Empleado actualizado exitosamente", "Registro Actualizado."); // Mostrar mensaje de éxito
                    table.ajax.reload(); // Recargar la tabla
                    $('#updateEmpleado').modal('hide'); // Cerrar el modal después de registrar
                    LimpiarModel(); // Limpiar los campos del modal
                }else if(response == "error"){
                    toastr.error("Intente nuevamente", "Error al actualizar el empleado.");
                    table.ajax.reload(); // Recargar la tabla
                    LimpiarModel(); // Limpiar los campos del modal
                    $('#updateEmpleado').modal('hide'); // Cerrar el modal después de registrar
                } else if(response = "required"){
                    toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                    LimpiarModel(); // Limpiar los campos del modal
                    $('#updateEmpleado').modal('hide'); // Cerrar el modal después de registrar
                } else{
                    toastr.error("ERROR", "ERROR.");
                    LimpiarModel(); // Limpiar los campos del modal
                    $('#updateEmpleado').modal('hide'); // Cerrar el modal después de registrar
                }
            },
            error: function() {
                toasrt.error("Error en la conexión. Intente nuevamente.","ERROR");
            }
        });
    }

    // Función para llenar el select de áreas al crear o actualizar un empleado
    function LlenarSelectArea(){
        // Realizar una solicitud AJAX para obtener las áreas
    $.ajax({
        // Enviar los datos al servidor
        url: '../controller/EmpleadosController.php?operador=LlenarSelectArea',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
            if(response && response.length > 0){
                data = $.parseJSON(response);
                var options = '<option value="">Seleccione un Area</option>';
                for (var i = 0; i < data.length; i++) {
                    options += '<option value="' + data[i]['id_Area'] + '">' + data[i]['Area'] + '</option>';
                }
                $('#txtareacrear').html(options);
                $('#txtareaupdate').html(options);
            }else{
            toastr.error("Error al cargar las Areas", "ERROR"); // Mostrar mensaje de éxito
            }
        }
        });
}

// Función para eliminar un empleado
// Esta función se llama cuando se confirma la desactivación de un empleado
function EliminarEmpleado(id_Empl){
    // Realizar una solicitud AJAX para eliminar el empleado
    $.ajax({
        // Enviar los datos al servidor para eliminar el empleado
        data: {"id_Empl": id_Empl},
        url: '../controller/EmpleadosController.php?operador=Eliminar_Empleado',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            // Procesar la respuesta del servidor
            if (response == "sucess") {
                toastr.success("Empleado desactivado exitosamente", "Registro Desactivado."); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Error al desactivar el Empleado.", "Intente nuevamente");
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

// Función para mostrar una alerta de confirmación antes de desactivar un empleado
function AlertaDesactivar(id_Empl,cedula)
{
    // Mostrar una alerta de confirmación utilizando SweetAlert
    Swal.fire({
        // Si el usuario confirma, llamar a la función EliminarEmpleado
        title: "Seguro?",
        html: "Se desactivara el Empleado con el serial: <h5>" + cedula+"?</h5>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: "Desactivado!",
            text: "El equipo ha sido desactivado.",
            type: "success"
          });
          EliminarEmpleado(id_Empl);
        }
      });

}
// Función para limpiar los campos del modal de creación y actualización
    function LimpiarModel(){
        $('#txtcedula').val("");
        $('#txtnombre').val("");
        $('#txtapellido').val("");
        $('#txtemail').val("");
        $('#txtarea').val("");
    }
    // Función para limpiar los campos del modal de actualización
    function LimpiarModelUpdate(){
        $('#txtcedulaupdate').val("");
        $('#txtnombreupdate').val("");
        $('#txtapellidoupdate').val("");
        $('#txtemailupdate').val("");
        $('#txtareaupdate').val("");
    }
