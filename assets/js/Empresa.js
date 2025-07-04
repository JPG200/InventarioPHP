var table;

init();// Función para inicializar el DataTable

function init(){
LlenarTablaEmpresa();
}


function LlenarTablaEmpresa(){
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
    {data:"op"}
]
});
}

											