<!-- main menu-->
<div data-scroll-to-active="true" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow">

      <div class="main-menu-header">
        <div class="media">
          <div class="media-left">
          <span class="avatar avatar-sm avatar-online rounded-circle">
            <img src="../app-assests/images/portrait/small/avatar-s-1.png" alt="avatar" class="rounded-circle"><i></i>   
            </span>      
          </div>
          <div class="media-body">
            <h6 class="media-heading"><?php echo $_SESSION["user"]["nombre"].' '.$_SESSION["user"]["apellidos"];?>
              <p class="notification-text font-small-3 text-muted">
              <?php 
                  echo $_SESSION["user"]["correo"];
              ?>
              </p> 
            </h6>
            <span class="font-small-3 text-muted">Administrador</span>

          </div>
       </div> 
      </div>

      <div class="main-menu-content">
        <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">


          <li class=" nav-item"><a href="welcome.php"><i class="icon-home3"></i><span class="menu-title">Dashboard</span></a>
          </li>
          <li class=" nav-item"><a href="#"><i class="icon-gears"></i><span class="menu-title">Configuración</span></a>
          </li>
          <li class=" nav-item"><a href="#"><i class="icon-user-secret"></i><span  class="menu-title">Gestion de usuarios</span></a>
            <ul class="menu-content">
              <li><a href="#" class="menu-item">Usuarios</a></li>
            </ul>
          </li>
          <li class=" nav-item"><a href="#"><i class="icon-mobile2"></i><span class="menu-title">Colaboradores</span></a>
            <ul class="menu-content">
              <li><a href="/pages/empleados.php" class="menu-item">Empleados</a></li>
              <li><a href="/pages/area.php" class="menu-item">Area</a></li>
            </ul>
          </li>
          <li class=" nav-item"><a href="#"><i class="icon-dropbox"></i><span class="menu-title">Productos</span></a>
            <ul class="menu-content">
              <li><a href="/pages/equipos.php" class="menu-item">Lista de Equipos</a></li>
              <li><a href="/pages/categorias.php" class="menu-item">Registrar Equipos</a></li>
            </ul>
          </li>
          <li class=" nav-item"><a href="#"><i class="icon-briefcase4"></i><span class="menu-title">Asignacion</span></a>
            <ul class="menu-content">
              <li><a href="/pages/Asignacion.php" class="menu-item">Realizar Asignacion</a></li>
              <li><a href="/pages/Devolucion.php" class="menu-item">Realizar Devolucion</a></li>
            </ul>
          </li>
          <li class=" nav-item"><a href="#"><i class="icon-ios-cart-outline"></i><span class="menu-title">Compras</span></a>
            <ul class="menu-content">
              <li><a href="#" class="menu-item">Realizar compras</a></li>
              <li><a href="#" class="menu-item">Lista de compras</a></li>
            </ul>
          </li>

          <li class=" navigation-header"><span>Informes</span>
          </li>
          <li class=" nav-item"><a href="#"><i class="icon-file-text2"></i><span class="menu-title">Reportes </span></a>
          </li>
          <li class=" nav-item"><a href="#"><i class="icon-stats-dots"></i><span  class="menu-title">Gráficas</span></a>
          </li>
          <li class=" navigation-header"><span>Soporte</span>
          </li>
          <li class=" nav-item"><a href="#"><i class="icon-database2"></i><span  class="menu-title">Backup</span></a>
          </li>
        </ul>
      </div>

</div>
<!-- / main menu-->