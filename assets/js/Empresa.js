var table;

init();// Función para inicializar el DataTable


function init(){
  // Inicializar el DataTable
LlenarTablaEmpresa();
}

// Función para llenar la tabla de empresas
function LlenarTablaEmpresa(){
  // Configuración del DataTable
table = $('#Tabla_Empresa').DataTable({
pageLength:10,
responsive:true,
processing:true,
ajax:"../controller/EmpresasController.php?operador=listar_Empresa",
columns:[
    {data:"Numero de Registro",'visible': false},
    {data:"Empresa"},
    {data:"NIT"},
    {data:"Numero de Contrato"},
    {data:"Fecha de Inicio"},
    {data:"Fecha de Final"},
    {data:"Vigencia"},
    {data:"Estado"},
    {data:"op", "orderable": false, "searchable": false, "className": "text-center", "width": "10%"}
],
// Configuración de idioma
            "autoWidth": false, 
});
}

// Función para eliminar una empresa
function eliminarEmpresa(id_Empresa, NumeroContrato){
// Mostrar alerta de confirmación antes de eliminar
     Swal.fire({
        title: '¿Seguro?',
        html: "¿Está seguro de desactivar el contrato: <h5>"+ NumeroContrato +"</h5>?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
    })
    .then((result) => {
        if (result.value) {
            $.ajax({
              // Enviar solicitud AJAX para eliminar la empresa
                url: "../controller/EmpresasController.php?operador=eliminar_Empresa",
                type: "POST",
                data: {
                    "id_Empresa": id_Empresa,
                    "NumeroContrato": NumeroContrato
                },
                beforeSend: function(response){
                },
                success: function(response){
                  // Manejar la respuesta del servidor
                    if(response == "success"){
                        toastr.success("Empresa eliminada exitosamente", "Eliminación Exitosa."); // Mostrar mensaje de éxito
                        table.ajax.reload(); // Recargar la tabla después de eliminar la empresa
                    }else{
                        toastr.error("Empresa no eliminada", "Eliminación Fallida."); // Mostrar mensaje de error
                    }
                }
            });
        }
    });
}

// Función para activar una empresa
function activarEmpresa(id_Empresa, NumeroContrato){
  // Mostrar alerta de confirmación antes de activar
    Swal.fire({
        title: '¿Seguro?',
        html: "¿Está seguro de activar el contrato: <h5>"+ NumeroContrato +"</h5>?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!"
    })
    .then((result) => {
        if (result.value) {
            $.ajax({
              // Enviar solicitud AJAX para activar la empresa
                url: "../controller/EmpresasController.php?operador=activar_Empresa",
                type: "POST",
                data: {
                    "id_Empresa": id_Empresa,
                    "NumeroContrato": NumeroContrato
                },
                beforeSend: function(response){
                },
                success: function(response){
                  // Manejar la respuesta del servidor
                    if(response == "success"){
                        toastr.success("Empresa activada exitosamente", "Activación Exitosa."); // Mostrar mensaje de éxito
                        table.ajax.reload(); // Recargar la tabla después de activar la empresa
                    }else{
                        toastr.error("Empresa no activada", "Activación Fallida."); // Mostrar mensaje de error
                    }
                }
            });
        }
    });
}

function cerrarModalUpdate(){
  // Limpiar los campos del modal de actualización
    $('#id_Empresa').val("");
    $('#txtempresaupdate').val("");
    $('#txtNITupdate').val("");
    $('#txtFechaIupdate').val("");
    $('#txtFechaFupdate').val("");
    $('#txtNumeroContratoupdate').val("");
    $('#updateEmpresa').modal('hide'); // Cerrar el modal después de registrar
}

function cerrarModal(){
  // Limpiar los campos del modal de creación
    $('#id_Empresa').val("");
    $('#txtempresacrear').val("");
    $('#txtNITcrear').val("");
    $('#txtFechaIcrear').val("");
    $('#txtFechaFcrear').val("");
    $('#txtNumeroContratoCrear').val("");
    
    $('#txtFechaIcrear').attr('readonly', false);
    $('#txtFechaFcrear').attr('readonly', false);
    $('#txtNumeroContratocrear').attr('readonly', false);
    $("#btnGuardar").show();
    $('#createEmpresa').modal('hide'); // Cerrar el modal después de registrar
}
				
function AlertaBuscarEmpresa(NIT, NumeroContrato,op){
// Validar que los campos NIT y NumeroContrato no estén vacíos
    if(NIT == "" || NumeroContrato == ""){
        toastr.error("Debe ingresar el NIT y el Numero de Contrato", "Campos Vacios"); // Mostrar mensaje de error
    }else{
        BuscarEmpresa(NIT, NumeroContrato, op);
    }
}



function BuscarEmpresa(NIT,NumeroContrato,op){
  // Validar que los campos NIT y NumeroContrato no estén vacíos
parametros = {
    "NIT": NIT,
    "NumeroContrato": NumeroContrato
};
$.ajax({
  // Enviar solicitud AJAX para buscar la empresa
    url: "../controller/EmpresasController.php?operador=buscar_Empresa",
    type: "POST",
    data: parametros,
    beforeSend: function(response){
        // Aquí puedes mostrar un mensaje de carga si lo deseas
    },
    success: function(response){
      // Manejar la respuesta del servidor
        if(response && response.length > 0){
          data = $.parseJSON(response);
          $('#id_Empresa').val(data[0]['id_Empresa']);
          $('#txtempresacrear').val(data[0]['Empresa']);
          $('#txtNITcrear').val(data[0]['NIT']);
          switch(op){
            case 'registrar':
              if(data[0]['id_Empresa']!=null && data[0]['id_Empresa']!=""){
                $('#txtFechaIcrear').val(data[0]['FechaI']);
                $('#txtFechaFcrear').val(data[0]['FechaF']);
                $('#txtNumeroContratocrear').val(data['NumeroContrato']);
                toastr.info("Empresa ya registrada.", "Actualice la Informacion"); // Mostrar mensaje de éxito

                $('#txtFechaIcrear').attr('readonly', true);
                $('#txtFechaFcrear').attr('readonly', true);
                $('#txtNumeroContratocrear').attr('readonly', true);
                $("#btnGuardar").hide();
              }else{
                $('#txtFechaIcrear').val(data[0]['FechaI']);
                $('#txtFechaFcrear').val(data[0]['FechaF']);
                $('#txtNumeroContratocrear').val(data[0]['NumeroContrato']);
                toastr.success("Empresa no Registrada.", "Registre la Informacion"); // Mostrar mensaje de éxito
              }
            break;
            case 'eliminar':
              if(data[0]['id_Empresa']!=null && data[0]['id_Empresa']!=""){
                eliminarEmpresa(data[0]['id_Empresa'], data[0]['NumeroContrato']);
              }else{
                toastr.error("Empresa no registrada.", "No se puede eliminar"); // Mostrar mensaje de error
              }
            break;
            case 'editar':
              $('#id_Empresa').val(data[0]['id_Empresa']);
              $('#txtempresaupdate').val(data[0]['Empresa']);
              $('#txtNITupdate').val(data[0]['NIT']);
              $('#txtNumeroContratoupdate').attr('readonly', true);
                if(data[0]['id_Empresa']!=null && data[0]['id_Empresa']!=""){
                  $('#txtFechaIupdate').val(data[0]['FechaI']);
                  $('#txtFechaFupdate').val(data[0]['FechaF']);
                  $('#txtNumeroContratoupdate').val(data[0]['NumeroContrato']);
                  toastr.info("Actualice la empresa o el Contrato.", "Realice la Actualizacion"); // Mostrar mensaje de éxito
                  $("#btnGuardar").hide();
              }else{
                  $('#txtFechaIupdate').val(data[0]['FechaI']);
                  $('#txtFechaFupdate').val(data[0]['FechaF']);
                  $('#txtNumeroContratoupdate').val(data[0]['NumeroContrato']);
                  toastr.info("No se ha registrado la Empresa ni el Contrato.", "Realice el Registro"); // Mostrar mensaje de éxito
              }
            break;
            case 'activar':
              if(data[0]['id_Empresa']!=null && data[0]['id_Empresa']!=""){
                activarEmpresa(data[0]['id_Empresa'], data[0]['NumeroContrato']);
              }else{
                toastr.error("Empresa no registrada.", "No se puede activar"); // Mostrar mensaje de error
              }
            break;
            default:
              toastr.error("Operación no reconocida.", "Error"); // Mostrar mensaje de error
              break;
          }  
        }
    }
});
}

// Función para actualizar una empresa
function actualizarEmpresa(){
  // Obtener los valores de los campos del formulario de actualización
    id_Empresa = $('#id_Empresa').val();
    empresa = $('#txtempresaupdate').val();
    NIT = $('#txtNITupdate').val();
    fecha_I = $('#txtFechaIupdate').val();
    fecha_F = $('#txtFechaFupdate').val();
    NumeroContrato = $('#txtNumeroContratoupdate').val();

    $.ajax({
      // Enviar solicitud AJAX para actualizar la empresa
        url: "../controller/EmpresasController.php?operador=actualizar_Empresa",
        type: "POST",
        data: {
            "id_Empresa": id_Empresa,
            "empresa": empresa,
            "NIT": NIT,
            "FechaI": fecha_I,
            "FechaF": fecha_F,
            "NumeroContrato": NumeroContrato
        },
        beforeSend: function(response){
        },
        success: function(response){
          // Manejar la respuesta del servidor
            if(response == "success"){
              toastr.success("Empresa Actualizada exitosamente", "Actualización Exitosa."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModalUpdate(); // Limpiar los campos del modal
            }else if(response == "error"){
              toastr.success("Empresa no actualizada", "Actualización Fallida."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModalUpdate(); // Limpiar los campos del modal
            }else if(response == "required"){
              toastr.success("Empresa no actualizada", "Faltan Datos."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModalUpdate(); // Limpiar los campos del modal           
            }
        }
    });
}

// Función para registrar una nueva empresa
function RegistrarEmpresa(){
  // Obtener los valores de los campos del formulario de creación
    empresa = $('#txtempresacrear').val();
    NIT = $('#txtNITcrear').val();
    fecha_I = $('#txtFechaIcrear').val();
    fecha_F = $('#txtFechaFcrear').val();
    Numero_Contrato = $('#txtNumeroContratoCrear').val();

    $.ajax({
      // Enviar solicitud AJAX para registrar la empresa
        url: "../controller/EmpresasController.php?operador=registrar_Empresa",
        type: "POST",
        data: {
            "empresa": empresa,
            "NIT": NIT,
            "FechaI": fecha_I,
            "FechaF": fecha_F,
            "NumeroContrato": Numero_Contrato
        },
        beforeSend: function(response){
        },
        success: function(response){
          // Manejar la respuesta del servidor
            if(response == "success"){
              toastr.success("Empresa Registrada exitosamente", "Registro Exitoso."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal
            }else if(response == "error"){
              toastr.success("Empresa no registrada", "Registro Fallido."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal
            }else if(response == "required"){
              toastr.success("Empresa no registrada", "Faltan Datos."); // Mostrar mensaje de éxito
              table.ajax.reload(); // Recargar la tabla después de registrar el equipo
              cerrarModal(); // Limpiar los campos del modal           
            }
        }
    });
}