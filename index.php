<?php
//Directory root
define("root", "/lottery/");
//Memory limit
ini_set('memory_limit', '2048M');

//Path requested
$uri = parse_url($_SERVER["REQUEST_URI"])['path']; 

//Parameters coming with that path
$param = isset(parse_url($_SERVER["REQUEST_URI"])['query']) ? parse_url($_SERVER["REQUEST_URI"])['query'] : "";

//No parameters
if($param == "") {
    $routes = [
        root => "controllers/index.controller.php", 
        root . "estadistica" => "controllers/statistic.controller.php",
        root . "loto/agregar" => "controllers/Loto.agregar.controller.php",
        root . "loto/test" => "controllers/lototest.controller.php",
        root . "update" => "controllers/update.controller.php",
        root . "create" => "controllers/create.controller.php"            
    ];
} else {
    $routes = [
        root . "delete" => "controllers/delete.controller.php",
        root . "reset" => "controllers/reset.controller.php",
        root . "edit" => "controllers/edit.controller.php"
    ];
}

//If the uri exists the controllers is called
if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else if ($uri == root . "loto") {
    require "controllers/index.controller.php";
} else {
    http_response_code(404);
    require "pages/404.php";
}
?>