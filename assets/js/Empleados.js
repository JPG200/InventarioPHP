var table;

init();// Función para inicializar el DataTable

function init(){
$('#txtcedula').val("");
$('#txtnombre').val("");
$('#txtapellido').val("");
$('#txtemail').val("");
$('#txtareacrear').val("");
listarEmpleados();
LlenarSelectArea();
}

function listarEmpleados(){
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
        {data:"Area"},
        {data:"Estado"},
        {data:"op"}
    ]
    });
    }

    function cerrarModal(){
    $('#txtcedula').val("");
    $('#txtnombre').val("");
    $('#txtapellido').val("");
    $('#txtemail').val("");
    $('#txtareacrear').val("");
    $("#btnGuardar").show();
    $('#createEmpleado').modal('hide'); // Cerrar el modal después de registrar
}

    function RegistrarEmpleado(){
    cedula=$('#txtcedula').val();
    nombre=$('#txtnombre').val();
    apellido=$('#txtapellido').val();
    email=$('#txtemail').val();
    area=$('#txtareacrear').val();

     parametros = {
        "cedula": cedula,
        "nombre": nombre,
        "apellido": apellido,
        "email": email,
        "area": area
        },
    $.ajax({
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
    /*cedula=$('#txtcedula').val();
    nombre=$('#txtnombre').val();
    apellido=$('#txtapellido').val();
    email=$('#txtemail').val();
    area=$('#txtareacrear').val();
    */
    $.ajax({
        data: {"id_Empl": id},
        url: '../controller/EmpleadosController.php?operador=buscarEmpleado',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
             data = $.parseJSON(response);
                if(data.length > 0){
                    if(op=="editar"){
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
                        id_Empleado=data[0]['id_Empl'];
                        AlertaDesactivar(id_Empleado,data[0]['cedula']);
                    }else if(op=="activar"){
                        id_Empleado=data[0]['id_Empl'];
                        AlertaActivar(id_Empleado,data[0]['cedula']);
                    }
                    /*else if(op=="buscar"){
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
                    }*/
            }
            }
        });
}

function AlertaActivar(id_Empl,cedula)
{
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

function ActivarEmpleado(id_Empl){
    $.ajax({
        data: {"id_Empl": id_Empl},
        url: '../controller/EmpleadosController.php?operador=Activar_Empleado',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
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

function BuscarEmpleadoBoton(cedula,op){
    /*cedula=$('#txtcedula').val();
    nombre=$('#txtnombre').val();
    apellido=$('#txtapellido').val();
    email=$('#txtemail').val();
    area=$('#txtareacrear').val();
    */
    $.ajax({
        data: {"cedula": cedula},
        url: '../controller/EmpleadosController.php?operador=buscarEmpleadoBoton',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response);
            console.log(cedula);
            console.log(op);
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

    function ActualizarEmpleado(){
        id_Empleado=$('#id_Empleado').val();
        cedula=$('#txtcedulaupdate').val();
        nombre=$('#txtnombreupdate').val();
        apellido=$('#txtapellidoupdate').val();
        email=$('#txtemailupdate').val();
        area=$('#txtareaupdate').val();
        parametros = {
            "id_Empleado":id_Empleado,
            "cedula": cedula,
            "nombre": nombre,
            "apellido": apellido,
            "email": email,
            "area": area
        },$.ajax({
            data: parametros,
            url: '../controller/EmpleadosController.php?operador=Actualizar_Empleado',
            type: 'POST',
            beforeSend: function(response){},
            success:function(response){
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

    function LlenarSelectArea(){
    $.ajax({
        url: '../controller/EmpleadosController.php?operador=LlenarSelectArea',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
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

function EliminarEmpleado(id_Empl){
    $.ajax({
        data: {"id_Empl": id_Empl},
        url: '../controller/EmpleadosController.php?operador=Eliminar_Empleado',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
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

function AlertaDesactivar(id_Empl,cedula)
{
    Swal.fire({
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
    function LimpiarModel(){
        $('#txtcedula').val("");
        $('#txtnombre').val("");
        $('#txtapellido').val("");
        $('#txtemail').val("");
        $('#txtarea').val("");
    }
    function LimpiarModelUpdate(){
        $('#txtcedulaupdate').val("");
        $('#txtnombreupdate').val("");
        $('#txtapellidoupdate').val("");
        $('#txtemailupdate').val("");
        $('#txtareaupdate').val("");
    }
