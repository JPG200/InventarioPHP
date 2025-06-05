var table;

init();// Función para inicializar el DataTable

function init(){
LlenarTablaregAsig();
LlenarSelectorEmpleado();
/*
$("#btnGuardar").hide();
$("#btnActualizar").hide();
$('#txtplacacrear').val("");
$('#txtserialcrear').val("");
$('#txtdescripcioncrear').val("");
$('#txtobservacionescrear').val("");
$('#txtaccesorioscrear').val("");
$('#txtempresacrear').val("");*/
}


function BuscarAsignacion(placa,op){
    // Obtener los valores de los campos de entrada
    parametros = {
        "placa": placa,
        },
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
              $('#txtserialcrear').val(data[0]['serial']);              
              if(data[0]['Acta']!="" && data[0]['Acta']!=null && op=="registrar"){
                toastr.info("Actualice los datos.", "Actualice los datos"); // Mostrar mensaje de éxito  
                $('#id_Asig').val(data[0]['id_Asig']);
                $("#txtplacacrear").val(placa);
                $('#txtdescripcioncrear').val(data[0]['descripcion']);
                $('#txtobservacionescrear').val(data[0]['observaciones']);
                $('#txtactacrear').val(data[0]['Acta']);
                var nombreEmpleadoEncontrada = data[0]['Empleado'];

                // Buscar la opción en el select que coincida con el nombre de la empresa
                $('#txtempleadocrear option').each(function() {
                if ($(this).text().toUpperCase() === nombreEmpleadoEncontrada.toUpperCase()) {
                    $('#txtempleadocrear').val($(this).val()); // Seleccionar la opción por su valor
                    return false; // Romper el bucle each una vez encontrada la coincidencia
                    }
                });    
                $("#btnGuardar").hide();
              }
              if(op=="editar"){
                toastr.info("Actualice los datos del equipo.", "Actualice los datos"); // Mostrar mensaje de éxito      
                $("#btnGuardar").hide();
                $("#btnActualizar").show();
                                toastr.info("Actualice los datos.", "Actualice los datos"); // Mostrar mensaje de éxito  
                $('#id_Asig').val(data[0]['id_Asig']);
                $("#txtplacacrear").val(placa);
                $('#txtdescripcioncrear').val(data[0]['descripcion']);
                $('#txtobservacionescrear').val(data[0]['observaciones']);
                $('#txtactacrear').val(data[0]['Acta']);
                var nombreEmpleadoEncontrada = data[0]['Empleado'];

                // Buscar la opción en el select que coincida con el nombre de la empresa
                $('#txtempleadocrear option').each(function() {
                if ($(this).text().toUpperCase() === nombreEmpleadoEncontrada.toUpperCase()) {
                    $('#txtempleadocrear').val($(this).val()); // Seleccionar la opción por su valor
                    return false; // Romper el bucle each una vez encontrada la coincidencia
                    }
                }); 
              }
              
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
]
});
}

function cerrarModal(){
    $('#id_Asig').val("");
    $('#txtempleadocrear').val("");
    $('#txtplacacrear').val("");
    $('#txtdescripcioncrear').val("");
    $('#txtobservacionescrear').val("");
    $('#txtactacrear').val("");
    $('#modalAsignacion').modal('hide'); // Cerrar el modal después de registrar
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
                $('#txtempleadoupdate').html(options);
            }else{
            toastr.error("Error al cargar los Empleados", "ERROR"); // Mostrar mensaje de éxito
            }
        }
        });
}