<?php
//Se requiere el archivo head.php para mostrar los estilos de bootstrap
require "pages/partials/head.php";

//Se requiere el archivo nav.php para mostrar la barra de navegación
require "pages/partials/nav.php";

/***************Edit interfaz */




//Receive date
if(isset($_GET["date"])) {
    $date = $_GET["date"];



} 
?>


<?php
    $conn -> close();
    require ("pages/partials/footer.php")
?>
