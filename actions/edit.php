<?php
//Se requiere el archivo head.php para mostrar los estilos de bootstrap
require "pages/partials/head.php";

//Se requiere el archivo nav.php para mostrar la barra de navegación
require "pages/partials/nav.php";

//Clase para la interfaz numerica
require "classes/Interface.Class.php";

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
            $html .= ' <form action="' . root . 'update" method="POST">';
            $html .= '<input type="hidden" name="date" value="'. $_GET["date"] .'">';
            $html .= '<label for="numbers" class="form-label">Números</label>';

            echo $html;

            //Receive date
            $date = $_GET["date"];

            //Query to get the numbers
            $result = $conn -> query ("SELECT `position`, `number` FROM `numbers` WHERE `date` = '$date' ORDER BY `position` ASC;");

            //If there are numbers
            if($result -> num_rows > 0) {
                $plays = [];
                //Get the numbers
                while($row = $result -> fetch_assoc()) {
                    $plays[] = intval($row["number"]);                           
                }   

                //Se crea la interfaz numérica
                $interface = new NumbersEntriesInterface($balls, $top, $plays);
                $interface -> createInputs();
            }
        }
        $html = "";
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
