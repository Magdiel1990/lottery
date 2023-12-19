<?php
//Directory root
define("root", "/lottery/");

//Path requested
$uri = parse_url($_SERVER["REQUEST_URI"])['path']; 

$routes = [
    root => "controllers/index.controller.php", 
    root . "estadistica" => "controllers/statistic.controller.php" 
    
    /lottery/pages/test.php
];

//If the uri exists the controllers is called
if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
}
?>
