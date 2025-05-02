var table;

init();// Función para inicializar el DataTable

function init(){
listarEmpleados();
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

    function RegistrarEmpleado(){
    cedula=$('#txtcedula').val();
    nombre=$('#txtnombre').val();
    apellido=$('#txtapellido').val();
    email=$('#txtemail').val();
    area=$('#txtarea').val();
    }

    function ActualizarEmpleado(){
        id_Empleado=$('#id_Empleupdate').val();
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
            url: '../controller/EmpleadosController.php?operador=Actualizar_Empleados',
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
    function EliminarEmpleado(id_Empleado){
        Swal.fire({
            title: '¿Está seguro de eliminar el empleado?',
            text: "¡No podrá recuperar este registro!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/EmpleadosController.php?operador=Eliminar_Empleados',
                    type: 'POST',
                    data: {id_Empleado:id_Empleado},
                    success:function(response){
                        if (response == "sucess") {
                            toastr.success("Empleado eliminado exitosamente", "Registro Eliminado."); // Mostrar mensaje de éxito
                            table.ajax.reload(); // Recargar la tabla
                        }else if(response == "error"){
                            toastr.error("Intente nuevamente", "Error al eliminar el empleado.");
                            table.ajax.reload(); // Recargar la tabla
                        } else if(response = "required"){
                            toastr.info("Por favor, complete todos los campos", "Datos incompletos.");
                        } else{
                            toastr.error("ERROR", "ERROR.");
                        }
                    },
                    error: function() {
                        toasrt.error("Error en la conexión. Intente nuevamente.","ERROR");
                    }
                });
            }
        })
    }
    function AlertaDesactivar(id_Empleado,cedula){
        Swal.fire({
            title: "Seguro?",
            html: "Se desactivara el empleado con la cedula: <h5>" + cedula+"?</h5>",
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
                text: "El empleado ha sido desactivado.",
                type: "success"
              });
              EliminarEmpleado(id_Empleado);
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

    function ActualizarEmpleado(){
    
    }