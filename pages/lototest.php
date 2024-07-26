
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

    //Clase para la desviación estándar
    require "classes/StandardDev.Class.php";

    //Clase para el producto de todos los números
    require "classes/TotalProduct.Class.php";

    //Special Variables
    $top = 40; #Number of numbers to play
    $balls = 6; #Number of balls
    /*****************/

    //Head
    require "partials/head.php";
    
    //Nav
    require "partials/nav.php";  
?>
<main class="container p-4">
    <div class="row justify-content-center text-center mt-4"> 
        <div class="mb-3">
            <a href="<?php echo root . 'loto/agregar';?>" class="btn btn-outline-info">Agregar</a>
        </div>
        <div class="col-auto">
            <form action="" method="POST">          
                <label for="numbers" class="form-label">Ingresa la jugada</label>                
                <?php
                $html = '<div class="d-flex flex-row justify-content-center flex-wrap">';
                //Se crean los inputs para los números
                for($i = 0; $i < $balls; $i++) {
                    //Se verifica si ya se han enviado los números
                    $value = isset($_SESSION["numbers"][$i]) ? $_SESSION["numbers"][$i] : '';
                    $html .= '<input name="numbers[]" value="' . $value . '" class="form-control m-2 px-2" style="max-width:3rem;" type="number" id="numbers" required min="1" max="'. $top .'">';
                }          
                
                $html .= '</div>';
        
                echo $html;

                 //Se eliminan los números de la sesión
                 unset($_SESSION["numbers"]);
                ?>
                <input class="btn btn-primary m-2" type="submit" value="Probar">
            </form>
        </div>
        <?php
        //Si se han enviado los números
        if(isset($_POST["numbers"])){
            //Se recibe la jugada
            $numbers = $_POST["numbers"];   
            
            //Se convierten los números a enteros
            for($i = 0; $i < count($numbers); $i++) {
                $numbers[$i] = (int) $numbers[$i];
            }             

            //Se guardan los números en la sesión
            $_SESSION ["numbers"] = $numbers;

            //Se verifica que no se repitan los números
            if(count(array_unique($numbers, SORT_NUMERIC)) < $balls) {
                $html = '<div class="mt-3">';
                $html .= '<h4 class = "text-center text-danger">No se pueden repetir los números</h4>';
                $html .= '</div>';
                echo $html;                    
            } else {                   
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
                $case = new Average($test, $conn, $numbers, $balls);
                $test = $case -> averagePastGames();

                //Se prueba de desviación estándar
                $case = new StandardDeviation($test, $numbers, $balls, $conn);
                $test = $case -> StdDev();     
                
                //Se prueba el producto total
                $case = new TotalProduct($test, $numbers, $balls, $conn);
                $test = $case -> testTotalProduct ();

                if($test){
                    $html = '<div class="mt-3">';
                    $html .= '<h4 class = "text-center text-success">La jugada es probable</h4>';
                    $html .= '</div>';
                    echo $html;
                } else {
                    $html = '<div class="mt-3">';
                    $html .= '<h4 class = "text-center text-danger">La jugada no es probable</h4>';
                    $html .= '</div>';
                    echo $html;
                }
            }
        }    
        ?>
    </div>
</main>
<?php
//Se cierra la conexión
    $conn -> close();
    //Footer
    require ("partials/footer.php")
?>