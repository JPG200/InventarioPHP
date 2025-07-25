var table;


init();// Función para inicializar el DataTable

function init(){
LlenarTablaArea();
LimpiarModel();
}

function LimpiarModel(){
    $('#txtArea').val("");
    $('#txtCentroCostos').val("");
}


function RegistrarArea(){
    centro_costos = $("#txtCentroCostos").val();
    area = $("#txtArea").val();
    parametros = {
        "area": area,
        "centro_costos": centro_costos
    },
    $.ajax({
        url: "../controller/AreaController.php?operador=registrar_Area",
        type: "POST",
        data: parametros,
        beforeSend: function(response){},
        success: function(response) {
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
            toasrt.error("Error en la conexión. Intente nuevamente.","ERROR");
        }
    });
}

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
                    if(op=="editar"){
                        $('#id_Areaupdate').val(data[0]['Numero de Registro']);
                        $('#txtAreaupdate').val(data[0]['Area']);
                        $('#txtcentrocostosupdate').val(data[0]['centro_costos']);
                    }else if(op=="eliminar"){
                        id_Area=data[0]['Numero de Registro'];
                        AlertaDesactivar(id_Area,data[0]['Area'],data[0]['centro_costos']);
                    }else if(op=="activar"){
                        id_Area=data[0]['Numero de Registro'];
                        AlertaActivar(id_Area,data[0]['Area'],data[0]['centro_costos']);
                    }
            }
            }
        });
}
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