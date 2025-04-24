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
        {data:"Area"},
        {data:"Estado"},
        {data:"op"}
    ]
    });
    }