<?php
//Directory root
define("root", "/lottery/");

//Path requested
$uri = parse_url($_SERVER["REQUEST_URI"])['path']; 

//Parameters coming with that path
$param = isset(parse_url($_SERVER["REQUEST_URI"])['query']) ? parse_url($_SERVER["REQUEST_URI"])['query'] : "";

//No parameters
if($param == "") {
    $routes = [
        root => "controllers/index.controller.php", 
        root . "estadistica" => "controllers/statistic.controller.php",
        root . "probar" => "controllers/test.controller.php",
        root . "LP/agregar" => "controllers/LP.agregar.controller.php",
        root . "Loto/agregar" => "controllers/Loto.agregar.controller.php",
        root . "Kino/agregar" => "controllers/Kino.agregar.controller.php",
        root . "LP/generar" => "controllers/LP.generar.controller.php",
        root . "Loto/generar" => "controllers/Loto.generar.controller.php",
        root . "Kino/generar" => "controllers/Kino.generar.controller.php"
    ];
} 

//If the uri exists the controllers is called
if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else {
    http_response_code(404);
    require "pages/404.php";
}
?>