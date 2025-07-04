var table;

init();

function init(){
    LlenarTablaregDev();
    cerrarModalUpdate();
    cerrarModal();
}

function LlenarTablaregDev(){
table = $('#Tabla_Devolucion').DataTable({
pageLength:10,
responsive:true,
processing:true,
ajax:"../controller/DevolucionController.php?operador=listar_Devolucion",
columns:[
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
]
});
}

function cerrarModalUpdate(){
    $('#id_Dev').val("");
    $('#txtactaupdate').val("");
    $('#txtasigupdate').val("");
    $('#txtplacaupdate').val("");
    $('#txtdescripcionupdate').val("");
    $('#txtobservacionesupdate').val("");
    $('#updateDevolucion').modal('hide'); // Cerrar el modal después de registrar
}

function cerrarModal(){
    $('#id_Dev').val("");
    $('#txtactacrear').val("");
    $('#txtasigcrear').val("");
    $('#txtplacacrear').val("");
    $('#txtdescripcioncrear').val("");
    $('#txtobservacionescrear').val("");
    $('#createDevolucion').modal('hide'); // Cerrar el modal después de registrar
}

function RegistrarDevolucion(){
    acta_asig = $('#txtasigcrear').val();
    observaciones = $('#txtobservacionescrear').val();
    acta = $('#txtactacrear').val();

    $.ajax({
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
            console.log("Respuesta: " + response);
            console.log("Acta Asignacion: " + acta_asig);
            
            if(response == "success"){
              toastr.success("Devolucion Registrada exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal
            }else if(response == "error"){
              toastr.success("Devolucion no registrada", "Registro Fallido."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal
            }else if(response == "required"){
              toastr.success("Devolucion no registrada", "Faltan Datos."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal           
              }
        }
    });
}

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
            console.log("Respuesta "+response);
            console.log("Acta: " + $acta);
            console.log("Operación: " + op);
            if(response && response.length > 0){
            data = $.parseJSON(response);
            $("#txtasigcrear").val($acta);
            $('#txtasigcrear').val(data[0]['ActaAsig']);
            $('#txtplacacrear').val(data[0]['placa']);
            $('#txtdescripcioncrear').val(data[0]['descripcion']);
            if(data[0]['observaciones']!=null && data[0]['observaciones']!=""){

                if(op=="registrar"){
                    op="editar";
                }

                $('#id_Dev').val(data[0]['id_Dev']);
                $('#txtobservacionescrear').val(data[0]['observaciones']);
                $('#txtactacrear').val(data[0]['ActaDev']);

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
            /*
            if(response && response.length > 0){
                data = $.parseJSON(response);                         
                $("#btnGuardar").hide();
                $("#btnActualizar").hide();
                if(op=="registrar" || op=="editar"){
                        $("#txtplacacrear").val(placa);
                        $('#txtserialcrear').val(data[0]['serial']);
                        if(data[0]['descripcion']!=null && data[0]['descripcion']!=""){
                            $('#id_Registro').val(data[0]['id_Reg']);
                            $("#txtplacacrear").val(placa);
                            $('#txtdescripcioncrear').val(data[0]['descripcion']);
                            $('#txtobservacionescrear').val(data[0]['observaciones']);
                            $('#txtaccesorioscrear').val(data[0]['accesorios']);
                            var nombreEmpresaEncontrada = data[0]['empresa'];

                            // Buscar la opción en el select que coincida con el nombre de la empresa
                            $('#txtempresacrear option').each(function() {
                                if ($(this).text().toUpperCase() === nombreEmpresaEncontrada.toUpperCase()) {
                                    $('#txtempresacrear').val($(this).val()); // Seleccionar la opción por su valor
                                    return false; // Romper el bucle each una vez encontrada la coincidencia
                                }
                            });
                            if(op=="editar"){
                                toastr.info("Actualice los datos del equipo.", "Actualice los datos"); // Mostrar mensaje de éxito      
                                $("#btnGuardar").hide();
                                $("#btnActualizar").show();
                            }else{
                                toastr.error("El equipo ya esta registrado.", "Equipo ya registrado"); // Mostrar mensaje de éxito      
                                $("#btnGuardar").hide();
                                $("#btnActualizar").hide();
                            }
                        }else{
                            $('#txtdescripcioncrear').val("");
                            $('#txtobservacionescrear').val("");
                            $('#txtaccesorioscrear').val("");
                            $('#txtempresacrear').val("");
                            toastr.success("El equipo no se ha registrado.", "Registre los datos del equipo"); // Mostrar mensaje de éxito
                            $("#btnGuardar").show();
                            $("#btnActualizar").hide();
                        }
                    }else if(op=="eliminar"){
                        id_Reg=data[0]['id_Reg'];
                        AlertaDesactivar(id_Reg,data[0]['placa']);
                    }else if(op=="activar"){
                        id_Reg=data[0]['id_Reg'];
                        console.log(id_Reg);
                        AlertaActivar(id_Reg,data[0]['placa']);
                    }

            }else{
            toastr.success("Error al buscar el equipo", "ERROR"); // Mostrar mensaje de éxito
            }*/
        }
        });
}
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