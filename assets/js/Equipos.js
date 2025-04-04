var table;


init();// Función para inicializar el DataTable

function init(){
LlenarTablaEquipos();
}

function LlenarTablaEquipos(){
table = $('#Tabla_Equipos').DataTable({
pageLength:10,
responsive:true,
processing:true,
ajax:"../controller/EquiposController.php?operador=listar_categorias",
columns:[
    {data:"Numero de Registro"},
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