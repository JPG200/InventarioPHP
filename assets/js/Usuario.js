function ValidarUsuario(){ 
    // Función para validar el usuario
    correo = $('#user-name').val();
    clave = $('#user-password').val();
    msg="";

    parametros = {
        "correo": correo,
        "clave": clave
    };
    $.ajax({
        // Configuración de la solicitud AJAX
        data:  parametros,
        url:   'controller/UsuarioController.php?operador=ValidarUsuario',
        type:  'post',
        beforeSend: function () {},
        success: function (response) {
            if(response == "success"){
                location.href = "/pages/welcome.php";
            } else if(response == "not found"){
                console.log(response);

               msg ='<div class="alert alert-danger mb-2" role="alert"><strong>Usuario o Contraseña incorrecta </strong>Las Credenciales son incorrectas. Por favor verifique.</div>;'
               LimpiarController();
            } else if(response == "requerido"){
                console.log(response);
                
               msg ='<div class="alert alert-danger mb-2" role="alert"><strong>Usuario no existe </strong> Por favor registrese.</div>;'
               LimpiarController();
            }
            $('#status_login').html(msg);
        }
    });
}

function LimpiarController(){
    // Limpiar los campos de entrada
    $('#user-name').val("");
    $('#user-password').val("");
}


