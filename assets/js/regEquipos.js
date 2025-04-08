var table;


init();// Función para inicializar el DataTable

function init(){
LlenarTablaregEquipos();
LlenarSelectorEmpresa();
}

function LlenarTablaregEquipos(){
table = $('#Tabla_regEquipos').DataTable({
pageLength:10,
responsive:true,
processing:true,
ajax:"../controller/regEquiposController.php?operador=listar_regEquipos",
columns:[
    {data:"Numero de Registro",'visible': false},
    {data:"Placa"},
    {data:"Serial"},
    {data:"Descripcion"},
    {data:"Observaciones"},
    {data:"Accesorios"},
    {data:"Empresa"},
    {data:"Fecha de Ingreso"},
    {data:"Estado"},
    {data:"op"}
]
});
}

function cerrarModal(){
    $('#txtplacacrear').val("");
    $('#txtserialcrear').val("");
    $('#txtdescripcioncrear').val("");
    $('#txtobservacionescrear').val("");
    $('#txtaccesorioscrear').val("");
    $('#txtempresacrear').val("");
    $('#createregEquipo').modal('hide'); // Cerrar el modal después de registrar
}

function BuscarEquipo(placa,op){
    // Obtener los valores de los campos de entrada
    parametros = {
        "placa": placa,
        },
    $.ajax({
        data: parametros,
        url: '../controller/regEquiposController.php?operador=buscarEquipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
            if(response && response.length > 0){
                data = $.parseJSON(response);
                if(op=="registrar"){
                        $("txtplacacrear").val(placa);
                        $('#txtserialcrear').val(data[0]['serial']);
                        if(data[0]['descripcion']!=null && data[0]['descripcion']!=""){
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
                            toastr.success("El Equipo ya se encuentra registrado.", "Equipo Registrado"); // Mostrar mensaje de éxito      
                        }else{
                            $('#txtdescripcioncrear').val("");
                            $('#txtobservacionescrear').val("");
                            $('#txtaccesorioscrear').val("");
                            $('#txtempresacrear').val("");
                            toastr.success("El equipo no se ha registrado.", "Registre los datos del equipo"); // Mostrar mensaje de éxito
                        }
                    }else if(op=="editar"){
                        $('#txtplacaupdate').val(placa);     
                        $('#txtserialupdate').val(data[0]['serial']);
                        if(data[0]['descripcion']!=null && data[0]['descripcion']!=""){
                            $('#txtdescripcionupdate').val(data[0]['descripcion']);
                            $('#txtobservacionesupdate').val(data[0]['observaciones']);
                            $('#txtaccesoriosupdate').val(data[0]['accesorios']);

                            var nombreEmpresaEncontrada = data[0]['empresa'];

                            // Buscar la opción en el select que coincida con el nombre de la empresa
                            $('#txtempresaupdate option').each(function() {
                                if ($(this).text().toUpperCase() === nombreEmpresaEncontrada.toUpperCase()) {
                                    $('#txtempresaupdate').val($(this).val()); // Seleccionar la opción por su valor
                                    return false; // Romper el bucle each una vez encontrada la coincidencia
                                }
                            });
                            toastr.success("Actualice el registro del equipo.", "Actualice el Registro"); // Mostrar mensaje de éxito      
                        }                   
                    }else if(op=="eliminar"){

                    }

            }else{
            toastr.success("Error al buscar el equipo", "ERROR"); // Mostrar mensaje de éxito
            }
        }
        });
}

function ConfirmarInformacion(){
    placa = $("#txtplacacrear").val();
    parametros = {
        "placa": placa,
        },
    $.ajax({
        data: parametros,
        url: '../controller/regEquiposController.php?operador=confirmarInformacion',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
            if(response && response.length > 0){
                data = $.parseJSON(response);


            }else{
            toastr.success("Error al buscar el equipo", "ERROR"); // Mostrar mensaje de éxito
            }
        }
        });
}

function LlenarSelectorEmpresa(){
    $.ajax({
        url: '../controller/regEquiposController.php?operador=LlenarSelectEmpresas',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
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

    parametros = {
        "placa": placa,
        "serial": serial,
        "descripcion": descripcion,
        "observaciones": observaciones,
        "accesorios": accesorios,
        "empresa": empresa
        },
    $.ajax({
        data: parametros,
        url: '../controller/regEquiposController.php?operador=registrarEquipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
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
    placa = $("#txtplacaupdate").val();
    serial = $("#txtserialupdate").val();
    descripcion = $("#txtdescripcionupdate").val();
    observaciones = $("#txtobservacionesupdate").val();
    accesorios = $("#txtaccesoriosupdate").val();
    empresa = $("#txtempresaupdate").val();

    parametros = {
        "placa": placa,
        "serial": serial,
        "descripcion": descripcion,
        "observaciones": observaciones,
        "accesorios": accesorios,
        "empresa": empresa
        },
    $.ajax({
        data: parametros,
        url: '../controller/regEquiposController.php?operador=editarEquipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
            if(response.length > 0){
                data = $.parseJSON(response);
                if(data.length > 0){
                        $('#txtplacaupdate').val(placa);
                        $('#txtserialupdate').val(data[0]['serial']);
                        $('#txtdescripcionupdate').val(data[0]['descripcion']);
                        $('#txtobservacionesupdate').val(data[0]['observaciones']);
                        $('#txtaccesoriosupdate').val(data[0]['accesorios']);
                        $('#txtempresaupdate').val(data[0]['empresa']);
                        toastr.success("El Equipo ya se encuentra registrado.", "Equipo Registrado"); // Mostrar mensaje de éxito      
                }
            }else{
            toastr.success("El equipo no se ha registrado.", "Registre los datos del equipo"); // Mostrar mensaje de éxito
            }
        }
        });
}

function EliminarEquipo(id){
    // Obtener los valores de los campos de entrada
    parametros = {
        "id": id,
        },
    $.ajax({
        data: parametros,
        url: '../controller/regEquiposController.php?operador=eliminarEquipo',
        type: 'POST',
        beforeSend: function(response){
        },
        success: function(response){
            console.log(response); // Para depuración
            if(response.length > 0){
                data = $.parseJSON(response);
                if(data.length > 0){
                        $('#txtserialcrear').val(data[0]['serial']);
                        $('#txtdescripcioncrear').val(data[0]['descripcion']);
                        $('#txtobservacionescrear').val(data[0]['observaciones']);
                        $('#txtaccesorioscrear').val(data[0]['accesorios']);
                        $('#txtempresacrear').val(data[0]['empresa']);
                        toastr.success("El Equipo ya se encuentra registrado.", "Equipo Registrado"); // Mostrar mensaje de éxito      
                }
            }else{
            toastr.success("El equipo no se ha registrado.", "Registre los datos del equipo"); // Mostrar mensaje de éxito
            }
        }
        });
}