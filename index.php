<?php
session_start();
if(isset($_SESSION["user"])){
    header("Location: pages/welcome.php");
}else{
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <script  type="text/javascript" src="/app-assests/js/core/libraries/jquery.min.js"></script> 
    <script type="text/javascript" src="/assets/js/Usuario.js"></script> 
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Robust admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, robust admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="">
    <title>Ingreso Sistema Inventario y Asignacion TI</title>
    <link rel="shortcut icon" type="image/x-icon" href="/app-assests/images/ico/favicon.ico">
    <link rel="shortcut icon" type="image/png" href="/app-assests/images/ico/favicon-32.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assests/css/bootstrap.css">
    <!-- font icons-->
    <link rel="stylesheet" type="text/css" href="/app-assests/fonts/icomoon.css">
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assests/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="/app-assests/css/app.css">
    <!-- END ROBUST CSS-->
  </head>

  <body class="vertical-layout vertical-menu 1-column  blank-page blank-page">
    
    <div class="app-content content container-fluid">
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body"><section class="flexbox-container">
            <div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1  box-shadow-2 p-0">
                <div class="card border-grey border-lighten-3 m-0">
                    <div class="card-header no-border">
                        <div class="card-title text-xs-center">
                            <div class="p-1"><img src="/app-assests/images/logo/robust-logo-dark.png"></div>
                        </div>
                        <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>Ingreso al sistema</span></h6>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block">
                            <form class="form-horizontal form-simple" >
                                <fieldset class="form-group position-relative has-icon-left mb-0">
                                    <input type="text" class="form-control form-control-lg input-lg" id="user-name" placeholder="Ingrese su correo">
                                    <div class="form-control-position">
                                        <i class="icon-head"></i>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="password" class="form-control form-control-lg input-lg" id="user-password" placeholder="Ingrese su contraseña">
                                    <div class="form-control-position">
                                        <i class="icon-key3"></i>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group row">
                                    <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                                        <fieldset>
                                            <input type="checkbox" id="remember-me" class="chk-remember">
                                            <label for="remember-me"> Remember Me</label>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6 col-xs-12 text-xs-center text-md-right"><a href="" class="card-link">Olvidaste tu contraseña ?</a></div>
                                </fieldset>
                                <button type="button" class="btn btn-primary btn-lg btn-block" onclick="ValidarUsuario();"><a href="" style="color:white;"><i class="icon-unlock2"></i> Iniciar sesión</a></button>
                            </form>
                        </div>
                    </div>
                    <div id="status_login" class="card-footer"></div>
                    <div class="card-footer">
                        <div class="">
                            <p class="float-sm-left text-xs-center m-0"><a href="" class="card-link">Recover password</a></p>
                            <p class="float-sm-right text-xs-center m-0">New to Robust? <a href="" class="card-link">Sign Up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </body>
</html>
<?php
}
?>
