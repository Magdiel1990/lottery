
<?php
    //Conexión a la base de datos
    require "classes/Database.Class.php";
    $conn = DatabaseClassLoto::dbConnection();

    //Clase para probar el rango
    require "classes/RangeTest.Class.php";

    //Clase para excluir las jugadas anteriores
    require "classes/PreviousPlaysOut.Class.php";

    //Clase para la suma total
    require "classes/TotalSum.Class.php";
    
    //Clase para el promedio
    require "classes/Average.Class.php";

    //Special Variables
    $top = 40; #Number of numbers to play
    $balls = 6; #Number of balls
    /*****************/

    require "methods/view_methods.php";

    require "partials/head.php";
    require "partials/nav.php";

    if(isset($_POST["numbers"])){
        //Se ordenan los números
        $numbers = sort($_POST["numbers"]);

        //Se prueba el rango
        $case = new RangeClass($numbers, $conn);
        $test = $case -> testRange();

        //Se excluyen las jugadas anteriores
        $case = new PreviousPlaysOut ($test, $conn, $balls, $numbers);
        $test = $case -> lastNumbersExceptions();

        //Se prueba la suma total
        $case = new TotalSum($test, $conn, $numbers);
        $test = $case -> testTotalSum();
        
        //Se prueba el promedio
        $case = new Average($test, $conn, $numbers);
        $test = $case -> average();



           
    }    
    $conn -> close();
?>

<main class="container p-4">
    <?php
        if(isset($_SESSION ["message"])){
            $html = '<div class="mt-3">';
            $html .= '<h4 class = "text-center text-'. $_SESSION ["message-alert"] .'">' . $_SESSION ["message"] . '</h4>';
            $html .= '</div>';

            echo $html;
            
            unset($_SESSION ["message"], $_SESSION ["message-alert"]);
        }
    ?>  
    <div class="row justify-content-center text-center mt-4"> 
        <div class="mb-3">
            <a href="<?php echo root . 'loto/agregar';?>" class="btn btn-outline-info">Agregar</a>
        </div>
        <div class="col-auto">
            <form action="" method="POST">          
                <label for="numbers" class="form-label">Ingresa la jugada</label>                
                <?php
                    echo add_numbers_input($balls, $top);
                ?>
                <input class="btn btn-primary m-2" type="submit" value="Probar">
            </form>
        </div>
    </div>
</main>
<?php
    require ("partials/footer.php")
?>