var table;

init();

function init(){
    LlenarTablaregDev();
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
            if(response.status === "success"){
              toastr.success("Devolucion Registrada exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal
            }else if(response.status === "error"){
              toastr.success("Devolucion no registrada", "Registro Fallido."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal
            }else if(response.status === "required"){
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
        "acta": acta,},
    $.ajax({
        data: parametros,
        url: '../controller/AsignacionController.php?operador=buscarDevolucion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response);
            if(response && response.length > 0){
            data = $.parseJSON(response);
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