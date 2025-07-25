var table;


init();// Función para inicializar el DataTable

function init(){
LlenarTablaEquipos();
}

function LimpiarModel(){
    $('#txtplaca').val("");
    $('#txtserial').val("");
}

function ActualizarEquipo(){
    id_Equip=$('#id_Equipupdate').val();
    placa=$('#txtplacaupdate').val();
    serial=$('#txtserialupdate').val();
    parametros = {
        "id_Equip":id_Equip,
        "placa": placa,
        "serial": serial
    },$.ajax({
        data: parametros,
        url: '../controller/EquiposController.php?operador=Actualizar_Equipos',
        type: 'POST',
        beforeSend: function(response){},
        success:function(response){
            if (response == "sucess") {
                toastr.success("Equipo actualizado exitosamente", "Registro Actualizado."); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
                $('#updateEquipo').modal('hide'); // Cerrar el modal después de registrar
                LimpiarModel(); // Limpiar los campos del modal
            }else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al actualizar el equipo.");
                table.ajax.reload(); // Recargar la tabla
                LimpiarModel(); // Limpiar los campos del modal
                $('#updateEquipo').modal('hide'); // Cerrar el modal después de registrar
            } else if(response = "required"){
                toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                LimpiarModel(); // Limpiar los campos del modal
                $('#updateEquipo').modal('hide'); // Cerrar el modal después de registrar
            } else{
                toastr.error("ERROR", "ERROR.");
                LimpiarModel(); // Limpiar los campos del modal
                $('#updateEquipo').modal('hide'); // Cerrar el modal después de registrar
            }
        },
        error: function() {
            toasrt.error("Error en la conexión. Intente nuevamente.","ERROR");
        }
    });
}

function LlenarTablaEquipos(){
table = $('#Tabla_Equipos').DataTable({
pageLength:10,
responsive:true,
processing:true,
ajax:"../controller/EquiposController.php?operador=listar_categorias",
columns:[
    {data:"Numero de Registro", 'visible': false},
    {data:"Placa"},
    {data:"Serial"},
    {data:"Fecha de Ingreso"},
    {data:"Estado"},
    {data:"op"}
],
            "autoWidth": false, 
});
}

function RegistrarEquipo(){
    placa = $("#txtplaca").val();
    serial = $("#txtserial").val();
    parametros = {
        "placa": placa,
        "serial": serial
    },
    $.ajax({
        url: "../controller/EquiposController.php?operador=registrar_equipo",
        type: "POST",
        data: parametros,
        beforeSend: function(response){},
        success: function(response) {
            console.log(response); // Para depuración
            if (response == "sucess") {
                toastr.success("Equipo registrado exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla después de registrar el equipo
                LimpiarModel(); // Limpiar los campos del modal
                $('#createEquipo').modal('hide'); // Cerrar el modal después de registrar
            } 
            else if(response == "registered"){
                toastr.info("El equipo ya está registrado en la base de datos", "El equipo ya existe.");
                LimpiarModel(); // Limpiar los campos del modal
                $('#createEquipo').modal('hide'); // Cerrar el modal después de registrar
            }else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al registrar el equipo.");
                table.ajax.reload(); // Recargar la tabla
                LimpiarModel(); // Limpiar los campos del modal
                $('#createEquipo').modal('hide'); // Cerrar el modal después de registrar
            } else{
                toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                LimpiarModel(); // Limpiar los campos del modal
                $('#createEquipo').modal('hide'); // Cerrar el modal después de registrar
            }
        },
        error: function() {
            toasrt.error("Error en la conexión. Intente nuevamente.","ERROR");
        }
    });
}

function AlertaDesactivar(id_Equip,serial)
{
    Swal.fire({
        title: "Seguro?",
        html: "Se desactivara el equipo con el serial: <h5>" + serial+"?</h5>",
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
          EliminarEquipo(id_Equip);
        }
      });

}

function AlertaActivar(id_Equip,serial)
{
    Swal.fire({
        title: "Seguro?",
        html: "Se activara el equipo con el serial: <h5>" + serial+"?</h5>",
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
          ActivarEquipo(id_Equip);
        }
      });

}

function EliminarEquipo(id_Equip){
    $.ajax({
        data: {"id_Equip": id_Equip},
        url: '../controller/EquiposController.php?operador=Eliminar_Equipos',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
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

function ActivarEquipo(id_Equip){
    $.ajax({
        data: {"id_Equip": id_Equip},
        url: '../controller/EquiposController.php?operador=Activar_Equipos',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
            if (response == "sucess") {
                toastr.success("Registro Activado.", "Equipo Activado exitosamente"); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla
            }else if(response == "error"){
                toastr.error("Intente nuevamente", "Error al Activar el equipo.");
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

function BuscarEquipo(id_Equip,op){
    $.ajax({
        data: {"id_Equip": id_Equip},
        url: '../controller/EquiposController.php?operador=buscar_equipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            data = $.parseJSON(response);
                if(data.length > 0){
                    if(op=="editar"){
                        $('#id_Equipupdate').val(data[0]['Numero de Registro']);
                        $('#txtplacaupdate').val(data[0]['placa']);
                        $('#txtserialupdate').val(data[0]['serial']);
                    }else if(op=="eliminar"){
                        id_Equip=data[0]['Numero de Registro'];
                        AlertaDesactivar(id_Equip,data[0]['serial']);
                    }else if(op=="activar"){
                        id_Equip=data[0]['Numero de Registro'];
                        AlertaActivar(id_Equip,data[0]['serial']);
                    }
            }
            }
        });
}
        