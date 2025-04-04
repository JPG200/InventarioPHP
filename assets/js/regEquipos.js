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
}

function BuscarEquipo(){
    // Obtener los valores de los campos de entrada
    placa = $("#txtplacacrear").val();
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