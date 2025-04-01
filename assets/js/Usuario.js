function ValidarUsuario(){ 
    correo = $('#user-name').val();
    clave = $('#user-password').val();
    msg="";

    parametros = {
        "correo": correo,
        "clave": clave
    };
    $.ajax({
        data:  parametros,
        url:   'controller/UsuarioController.php?operador=ValidarUsuario',
        type:  'post',
        beforeSend: function () {},
        success: function (response) {
            console.log(response);
            if(response == "success"){
                console.log(response);
                location.href = "/pages/welcome.php";
            } else if(response == "not found"){
                console.log(response);
               msg ='<div class="alert alert-danger mb-2" role="alert"><strong>Usuario o Contraseña incorrecta </strong>'+
                'Las Credenciales son incorrectas. Por favor verifique.</div>';
            } else if(response == "requerido"){
                console.log(response);
               msg ='<div class="alert alert-danger mb-2" role="alert"><strong>Usuario no existe </strong>'+
                'Por favor registrese.</div>';
            }
            $('#status_logic').html(msg);
        }
    });
}


