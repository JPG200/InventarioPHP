var table;


init();// Función para inicializar el DataTable

function init(){
LlenarTablaregEquipos();
}

function LlenarTablaregEquipos(){
table = $('#Tabla_regEquipos').DataTable({
pageLength:10,
responsive:true,
processing:true,
ajax:"../controller/regEquiposController.php?operador=listar_categorias",
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