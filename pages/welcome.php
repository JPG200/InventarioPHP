<!-- ============== | head | =================-->
<?php  
session_start();
if(isset($_SESSION["user"])){
include "layouts/head.php";     ?>
<!--==========================================-->


<!-- =========== | contenido | ===============-->
<div class="app-content content container-fluid">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
        </div>
    </div>
</div>
<!--==========================================-->

<!-- ========= | scripts robust | ============-->
<?php  include "../pages/layouts/main_scripts.php"; ?>
<!--==========================================-->

<!-- ============= | footer | ================-->
<?php  include "../pages/layouts/footer.php";     
}else{
header(header: "Location: ../");
}
?>
<!--==========================================-->



