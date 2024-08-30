<?php
//Se requiere el archivo head.php para mostrar los estilos de bootstrap
require "pages/partials/head.php";

//Se requiere el archivo nav.php para mostrar la barra de navegación
require "pages/partials/nav.php";

//Clase para la interfaz numerica
require "classes/Interface.Class.php";

//Clase para llevar de strings a arrays
require "classes/StringArray.Class.php";

//Special Variables
$balls = 6;
$top = 40;
?>
<main class="container p-4">
    <div class="row justify-content-center">
        <div class="col-auto">               
        <?php
        //Receive date
        if(isset($_GET["date"])) {
            $html = "";
            $html .= '<form action="' . root . 'update" method="POST" class="text-center">';
            $html .= '<input type="hidden" name="date" value="'. $_GET["date"] .'">';
            $html .= '<label for="numbers" class="form-label">Números</label>';

            //Receive date
            $date = $_GET["date"];

            //Instance of the class to get the string of the play
            $stringArray = new StringArray();
            $string = $stringArray -> getNumbers($date);

            //Convert the string into an array
            $plays = $stringArray -> stringtoArray($string);

            //Se crea la interfaz numérica
            $interface = new NumbersEntriesInterface($balls, $top, $plays);
            $html = $interface -> createInputs($html);            
        }

        $html .= '<input class="btn btn-primary m-2" type="submit" value="Editar">';
        $html .= '</form>';

        echo $html; 
        ?> 
        </div>
    </div>
</main>
<?php
//Se cierra la conexión
$conn -> close();
require ("pages/partials/footer.php")
?>
