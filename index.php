<?php
//Path requested
$uri = parse_url($_SERVER["REQUEST_URI"])['path']; 

$routes = [
    "/lottery/" => "controllers/index.controller.php",    
];

//If the uri exists the controllers is called
if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
}
?>
