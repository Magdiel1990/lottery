
<?php
    //Head
    require "partials/head.php";
    
    //Nav
    require "partials/nav.php";
    
    //Clase para la interfaz numerica
    require "classes/Interface.Class.php";

    //Clase para las inserciones numéricas en el storages
    require "classes/StoredNumbersQuery.Class.php";

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

    //Clase para la diferencia de las jugadas
    require "classes/Difference.Class.php";

    //Clase para la suma de las posiciones de los números
    require "classes/PartialSum.Class.php";

    //Clase de la cantidad de múltiplos
    require "classes/Multiple.Class.php";    

    //Special Variables
    $top = 40; #Number of numbers to play
    $balls = 6; #Number of balls
    /*****************/  
?>
<main class="container-fluid p-4">
    <div class="row justify-content-center text-center mt-4"> 
        <div class="mb-3">
            <a href="<?php echo root . 'loto/agregar';?>" class="btn btn-outline-info">Agregar</a>
        </div>
        <div class="col-auto">
            <form action="" method="POST">          
                <label for="numbers" class="form-label">Ingresa la jugada</label>                
                <?php
                //Se crea la interfaz numérica
                $interface = new NumbersEntriesInterface($balls, $top);
                $interface -> createInputs();
                ?>
                <input class="btn btn-primary m-2" type="submit" value="Probar">
            </form>
        </div>
        <div class="overflow">
        <?php
        $result = $conn -> query("SELECT id, numbers FROM `stored`;"); //Se seleccionan los números insertados
                            
        $html = ''; 

        while($row = $result -> fetch_assoc()) {
            $numbers = explode(" ", $row["numbers"]); //Se convierten los números en un array

             //Se convierten los números a enteros
            for($i = 0; $i < count($numbers); $i++) {
                $numbers[$i] = (int) $numbers[$i];
            }    

            //Se calcula el promedio de los números insertados
            $averageClass = new Average (true, $conn, $numbers, $balls); 
            //Se obtiene el promedio
            $average = $averageClass -> average($numbers);
            //Se obtiene el rango
            $rangeAvg = $averageClass -> maxMinRange();
            //Se obtiene el color del texto
            $textcoloraverage = ($average < $rangeAvg[0] || $average > $rangeAvg[1] ? "danger" : "success");


            //Se calcula la desviación estándar de la jugada
            $stdDev = new StandardDeviation(true, $numbers, $balls, $conn);
            $stdDevArray = $stdDev -> standardDeviation($numbers);
            //Se obtiene el rango de las desviaciones estándares totales
            $stdDevTotal = $stdDev -> StdDevRange();
            //Se obtiene el color del texto
            $textcolordev = ($stdDevArray < $stdDevTotal[0] || $stdDevArray > $stdDevTotal[1] ? "danger" : "success");


            //Se calcula el producto de los números insertados
            $product = new TotalProduct(true, $numbers, $balls, $conn);
            $productArray = $product -> product( $numbers);
            //Se obtiene el rango de los productos
            $productTotal = $product-> rangeProducts(); 
            //Se obtiene el color del texto
            $textcolorpro = ($productArray < $productTotal[0] || $productArray > $productTotal[1] ? "danger" : "success");


            //Se calcula la suma total de los números insertados
            $sum = new TotalSum(true, $conn, $numbers);
            $sumArray = $sum -> sumArray($numbers);
            //Se obtiene el rango de las sumas
            $sumTotal = $sum -> sumsArrayNumbers();     
            //Se obtiene el color del texto
            $textcolorsum = ($sumArray < $sumTotal[0] || $sumArray > $sumTotal[1] ? "danger" : "success");

            //Se excluyen las jugadas anteriores
            $previous = new PreviousPlaysOut (true, $conn, $balls, $numbers);
            $previous = $previous -> lastNumbersExceptions();
            //Se obtiene el color del texto y el resultado
            if($previous == false) {    
                $previousPlaysResult = 'Se ha jugado';
                $previousPlays = 'danger';               
            } else {
                $previousPlaysResult = 'No se ha jugado';
                $previousPlays = 'success';               
            }               

            $html .= '<div class="card my-2 mx-md-2" style="min-width: 12rem;">';
            $html .= '<div class="card-body">';
            for ($i = 0; $i < count($numbers); $i++) {
                $html .= '<span class="mx-1">' . $numbers[$i] . '</span>';
            }                        
            $html .= '<a href="' . root . 'delete?storedId=' . $row ['id']. '" class="text-danger mx-2">Eliminar</a>';
            $html .= '<div class="row my-4">';
            $html .= '<div class="col-4 bg-warning border"><b>Promedio</b> (' . round($rangeAvg[0], 2) . ', ' . round ($rangeAvg[1], 2) . ') <br><p class="text-' .  $textcoloraverage .'">' . round($average, 2) . '</p></div>';
            $html .= '<div class="col-4 bg-warning border"><b>Desv. Est.</b> (' . round($stdDevTotal[0], 2) . ', ' . round ($stdDevTotal[1], 2) . ') <br><p class="text-' .  $textcolordev .'">' . round($stdDevArray, 2) . '</div>';
            $html .= '<div class="col-4 bg-warning border"><b>Producto</b> (' . round($productTotal[0], 2) . ', ' . round ($productTotal[1], 2) . ') <br><p class="text-' .  $textcolorpro .'">' . round($productArray, 2) . '</p></div>';
            $html .= '<div class="col-4 bg-warning border"><b>Suma</b> (' . round($sumTotal[0], 2) . ', ' . round ($sumTotal[1], 2) . ') <br><p class="text-' .  $textcolorsum .'">' . round($sumArray, 2) . '</p></div>';
            $html .= '<div class="col-4 bg-warning border"><b>Anteriores</b><br><p class="text-' .  $previousPlays .'">' . $previousPlaysResult .  '</p></div>';

            //Diferencia de las posiciones de las jugadas
            $diff = new DiffClass(true , $conn, $balls, $numbers);

            for ($i = 1; $i < $balls; $i++) {
                for ($j = $i + 1; $j <= $balls; $j++) {
                    //Se obtienen las diferencias de las posiciones
                    $minDiff = $diff -> minMaxDiffRange ($i, $j) [0];
                    $maxDiff = $diff -> minMaxDiffRange ($i, $j) [1];
                    //Se obtiene la diferencia de las posiciones
                    $diffArray = $diff -> diffArray($numbers, $i, $j);

                    //Se obtiene el color del texto
                    if($diffArray < $minDiff || $diffArray > $maxDiff) {
                        $textcolordiff = 'danger';
                    } else {
                        $textcolordiff = 'success';
                    }

                    $html .= '<div class="col-4 bg-warning border"><b>Dif.</b> ('. $i . '° y ' . $j .'°) (' . $minDiff . ', ' . $maxDiff . ')<br><p class="text-' . $textcolordiff . '">' . $diffArray .  '</p></div>';
                }
            }    
            
            //Suma de las posiciones de las jugadas
            $sum = new PartialSumClass (true , $conn, $balls, $numbers);

            for ($i = 1; $i < $balls; $i++) {
                for ($j = $i + 1; $j <= $balls; $j++) {
                    //Se obtienen las sumas de las posiciones
                    $minSum = $sum -> minMaxSumRange ($i, $j) [0];
                    $maxSum = $sum -> minMaxSumRange ($i, $j) [1];
                    //Se obtiene la suma de las posiciones
                    $sumArray = $sum -> sumArray($numbers, $i, $j);

                    //Se obtiene el color del texto
                    if($sumArray < $minSum || $sumArray > $maxSum) {
                        $textcolorsum = 'danger';
                    } else {
                        $textcolorsum = 'success';
                    }

                    $html .= '<div class="col-4 bg-warning border"><b>Suma</b> ('. $i . '° y ' . $j .'°) (' . $minSum . ', ' . $maxSum . ')<br><p class="text-' . $textcolorsum . '">' . $sumArray .  '</p></div>';
                }
            }  
            
            //Se prueba la cantidad de múltiplos presentes
            for ($i = 2; $i <= 20; $i++) {
                $multiple = new MultipleClass(true, $numbers, $i, $balls, $conn);
                //Se obtiene el rango de los múltiplos
                $multipleTotal = $multiple -> multipleTotalCal();

                $minMax = $multiple -> minMaxMultiple(); //Se obtiene el mínimo y el máximo de los múltiplos

                //Se obtiene el múltiplo
                $multipleArray = $multiple -> multipleArrayCal($numbers, $i);

                //Se obtiene el color del texto
                if(!in_array($multipleArray, $multipleTotal)) {
                    $textcolormul = 'danger';
                } else {
                    $textcolormul = 'success';
                }

                $html .= '<div class="col-4 bg-warning border"><b>Múltiplos de </b>'. $i .' (' . $minMax [0] . ', ' . $minMax [1] . ')<br><p class="text-' . $textcolormul . '">' . $multipleArray .  '</p></div>';
            }
                   
            $html .= '</div>';
            $html .= '</div>';
            $html .='</div>';
        }   

        echo $html; //Se imprimen los números insertados
        ?>
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
            
            sort($numbers); //Se ordenan los números

            //Se verifica que no se repitan los números
            if(count(array_unique($numbers, SORT_NUMERIC)) < $balls) {
                $html = '<div class="mt-3">';
                $html .= '<h4 class = "text-center text-danger">No se pueden repetir los números</h4>';
                $html .= '</div>';
                echo $html;                    
            } else {  
                //Se insertan los datos de la jugada de manera provisional
                $stored = new StdInsertQuery ($numbers, $conn);
                $stored -> insertQueryConfirm (); //Se insertan los números en la base de datos

                //Si se insertaron los números de manera provisional
                if($stored) { 
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

                    //Se prueba la diferencia de las jugadas
                    $case = new DiffClass($test, $conn, $balls, $numbers);
                    $test = $case -> diffPlaysCalculation();

                    //Se prueba la suma de las posiciones de los números
                    $case = new PartialSumClass ($test, $conn, $balls, $numbers);
                    $test = $case -> sumPlaysCalculation();

                    //Se prueba la cantidad de múltiplos presentes
                    for ($i = 2; $i <= 20; $i++) {
                        $case = new MultipleClass($test, $numbers, $i, $balls, $conn);
                        $test = $case -> multipleComparison();

                        if($test == false) {
                            break;
                        }
                    }

                    //Se muestra el resultado
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

                $html = '<div class="mt-3">';
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