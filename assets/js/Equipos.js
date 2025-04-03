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
                toastr.sucess("Equipo actualizado exitosamente", "Registro Actualizado."); // Mostrar mensaje de éxito
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
]
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
        beforeSend: function(){},
        success: function(response) {
            console.log(parametros);
            if (response == "sucess") {
                console.log(response); // Para depuración
                toastr.sucess("Equipo registrado exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
                table.ajax.reload(); // Recargar la tabla después de registrar el equipo
                LimpiarModel(); // Limpiar los campos del modal
                $('#createEquipo').modal('hide'); // Cerrar el modal después de registrar
            } 
            else if(response == "registered"){
                console.log(response); // Para depuración
                toastr.info("El equipo ya está registrado en la base de datos", "El equipo ya existe.");
                LimpiarModel(); // Limpiar los campos del modal
                $('#createEquipo').modal('hide'); // Cerrar el modal después de registrar
            }else if(response == "error"){
                console.log(response); // Para depuración
                toastr.error("Intente nuevamente", "Error al registrar el equipo.");
                table.ajax.reload(); // Recargar la tabla
                LimpiarModel(); // Limpiar los campos del modal
                $('#createEquipo').modal('hide'); // Cerrar el modal después de registrar
            } else{
                console.log(response); // Para depuración
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

function BuscarEquipo(id_Equip){
    $.ajax({
        data: {"id_Equip": id_Equip},
        url: '../controller/EquiposController.php?operador=buscar_equipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
            data = $.parseJSON(response);
            if(data.length > 0){
                $('#id_Equipupdate').val(data[0]['Numero de Registro']);
                $('#txtplacaupdate').val(data[0]['placa']);
                $('#txtserialupdate').val(data[0]['serial']);
            }
            }
        });
}
        