var table;


init();// Función para inicializar el DataTable

    function init(){
    LlenarTablaArea();
    LimpiarModel();
    }

    // Función para limpiar los campos del modal
    function LimpiarModel(){
        $('#txtArea').val("");
        $('#txtCentroCostos').val("");
    }

    // Función para registrar un área
    function RegistrarArea(){
        // Obtener los valores de los campos del formulario (Centro de Costos y Área)
        centro_costos = $("#txtCentroCostos").val();
        area = $("#txtArea").val();
        // Validar que los campos no estén vacíos
        if (centro_costos == "" || area == "") {
            toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
            return; // Salir de la función si los campos están vacíos
        }
        // Enviar los datos al servidor para registrar el área
        parametros = {
            "area": area,
            "centro_costos": centro_costos
        },
        $.ajax({
            // Enviar los datos al servidor
            url: "../controller/AreaController.php?operador=registrar_Area",
            type: "POST",
            data: parametros,
            beforeSend: function(response){},
            success: function(response) {
                // Manejar la respuesta del servidor
                if (response == "sucess") {
                    toastr.success("Area registrada exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
                    table.ajax.reload(); // Recargar la tabla después de registrar el equipo
                    LimpiarModel(); // Limpiar los campos del modal
                    $('#createArea').modal('hide'); // Cerrar el modal después de registrar
                } 
                else if(response == "registered"){
                    toastr.info("El Area ya está registrada en la base de datos", "El Area ya existe.");
                    LimpiarModel(); // Limpiar los campos del modal
                    $('#createArea').modal('hide'); // Cerrar el modal después de registrar
                }else if(response == "error"){
                    toastr.error("Intente nuevamente", "Error al registrar el Area.");
                    table.ajax.reload(); // Recargar la tabla
                    LimpiarModel(); // Limpiar los campos del modal
                    $('#createArea').modal('hide'); // Cerrar el modal después de registrar
                } else{
                    toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                    LimpiarModel(); // Limpiar los campos del modal
                    $('#createArea').modal('hide'); // Cerrar el modal después de registrar
                }
            },
            error: function() {
                // Manejar errores de conexión
                toasrt.error("Error en la conexión. Intente nuevamente.","ERROR");
            }
        });
    }

    // Función para llenar la tabla de áreas
    // Utiliza DataTables para mostrar los datos de las áreas
    function LlenarTablaArea(){
    table = $('#Tabla_Area').DataTable({
    pageLength:10,
    responsive:true,
    processing:true,
    ajax:"../controller/AreaController.php?operador=Area_listar",
    columns:[
        {data:"Numero de Registro", 'visible': false},
        {data:"Area"},
        {data:"Centro de Costos"},
        {data:"Fecha de Inicio"},
        {data:"Fecha de Terminacion"},
        {data:"Estado"},
        {data:"op"}
    ],
    "autoWidth": false, 
    });
    }

    // Función para buscar un área por su ID
    // Utiliza AJAX para obtener los datos del área y llenar el formulario de actualización o mostrar alertas de confirmación para eliminar o activar el área
    function BuscarArea(id_Area,op){
        $.ajax({
            data: {"id_Area": id_Area},
            url: '../controller/AreaController.php?operador=buscar_area',
            type: 'POST',
            beforeSend: function(response){
            },
            success: function(response){
                data = $.parseJSON(response);
                    if(data.length > 0){
                        // Si se encuentra el área, llenar los campos del formulario de actualización
                        if(op=="editar"){
                            $('#id_Areaupdate').val(data[0]['Numero de Registro']);
                            $('#txtAreaupdate').val(data[0]['Area']);
                            $('#txtcentrocostosupdate').val(data[0]['centro_costos']);
                        }else if(op=="eliminar"){
                            // Si se selecciona eliminar, mostrar alerta de confirmación
                            id_Area=data[0]['Numero de Registro'];
                            AlertaDesactivar(id_Area,data[0]['Area'],data[0]['centro_costos']);
                        }else if(op=="activar"){
                            // Si se selecciona activar, mostrar alerta de confirmación
                            id_Area=data[0]['Numero de Registro'];
                            AlertaActivar(id_Area,data[0]['Area'],data[0]['centro_costos']);
                        }
                }
                }
            });
    }

// Funciones para mostrar alertas de confirmación antes de desactivar o activar un área
// Utilizan SweetAlert para mostrar mensajes personalizados y manejar la respuesta del usuario
    function AlertaDesactivar(id_Area,area,centro_costos)
    {
    Swal.fire({
        title: "Seguro?",
        html: "Se desactivara el Area: <h5>" + area+"</h5> con el Centro de Costos: <h5>" + centro_costos+"</h5>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
      }).then((result) => {
        console.log(result);
        if (result.value) {
          Swal.fire({
            title: "Desactivado!",
            text: "El equipo ha sido desactivado.",
            type: "success"
          });
          EliminarArea(id_Area);
        }
      });

    }

    // Función para activar un área
    // Muestra una alerta de confirmación antes de activar el área seleccionada
function AlertaActivar(id_Area,area,centro_costos)
{
    Swal.fire({
        title: "Seguro?",
        html: "Se Activara el Area: <h5>" + area+"</h5> Con el Centro de Costos: <h5>" + centro_costos+"</h5>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
      }).then((result) => {
        console.log(result);
        if (result.value) {
          Swal.fire({
            title: "Activado!",
            text: "El equipo ha sido activado.",
            type: "success"
          });
          ActivarArea(id_Area);
        }
      });

}

// Funciones para eliminar o activar un área
// Utilizan AJAX para enviar la solicitud al servidor y manejar la respuesta
function EliminarArea(id_Area){
    $.ajax({
        data: {"id_Area": id_Area},
        url: '../controller/AreaController.php?operador=Eliminar_Area',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            if (response == "sucess") {
                toastr.success("Area desactivada exitosamente", "Registro Desactivado."); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Error al desactivar el Area.", "Intente nuevamente");
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

// Función para activar un área
// Envía una solicitud AJAX al servidor para activar el área seleccionada y maneja la respuesta
function ActivarArea(id_Area){
    $.ajax({
        data: {"id_Area": id_Area},
        url: '../controller/AreaController.php?operador=Activar_Area',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
            if (response == "sucess") {
                toastr.success("Registro Activado.", "Area Activada exitosamente"); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al Activar el Area.");
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

// Función para actualizar un área
// Envía una solicitud AJAX al servidor con los datos actualizados del área y maneja la respuesta
function ActualizarArea(){
    id_Area=$('#id_Areaupdate').val();
    Area=$('#txtAreaupdate').val();
    Centro_costos=$('#txtcentrocostosupdate').val();
    parametros = {
        "id_Area":id_Area,
        "Area": Area,
        "Centro_costos": Centro_costos
    },$.ajax({
        data: parametros,
        url: '../controller/AreaController.php?operador=Actualizar_Area',
        type: 'POST',
        beforeSend: function(response){},
        success:function(response){
            if (response == "sucess") {
                toastr.success("Area actualizada exitosamente", "Registro Actualizado."); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
                $('#updateArea').modal('hide'); // Cerrar el modal después de registrar
                LimpiarModel(); // Limpiar los campos del modal
            }else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al actualizar el Area.");
                table.ajax.reload(); // Recargar la tabla
                LimpiarModel(); // Limpiar los campos del modal
                $('#updateArea').modal('hide'); // Cerrar el modal después de registrar
            } else if(response = "required"){
                toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                LimpiarModel(); // Limpiar los campos del modal
                $('#updateArea').modal('hide'); // Cerrar el modal después de registrar
            } else{
                toastr.error("ERROR", "ERROR.");
                LimpiarModel(); // Limpiar los campos del modal
                $('#updateArea').modal('hide'); // Cerrar el modal después de registrar
            }
        },
        error: function() {
            toasrt.error("Error en la conexión. Intente nuevamente.","ERROR");
        }
    });
}