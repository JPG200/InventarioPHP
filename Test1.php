<?php
require "model/Usuario.php";
$usuario = new Usuario();

$u_nombre ="JUAN";
$u_apelidos="GIRALDO";
$u_correo="practicanteti@jbotanico.org";
$u_contraseña="J4rd1n2025";
$u_tipo=1;

/*f($usuario->RegistrarUsuario(u_nombre: $u_nombre,u_apelidos: $u_apelidos,u_correo: $u_correo,u_contraseña: $u_contraseña, u_tipo: $u_tipo)){
    echo "Usuario registrado correctamente";
}
else{
echo "ERROR: No se pudo registrar el usuario";
}*/
#echo'Hello World!';

// Validar usuario
if($usuario->ValidarUsuario(u_correo: $u_correo,u_contraseña: $u_contraseña)){
    echo "Usuario validado correctamente";
}
else{
    echo "ERROR: No se pudo validar el usuario";
}
?>