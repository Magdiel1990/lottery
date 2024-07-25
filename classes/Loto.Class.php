<?php
require_once ("Random.Generators.Class.php");

abstract class LotteryClass {
    protected $totalNumbers;
    protected $arrayNumbers;
    protected $array; //Números a jugar
    protected $down; //Posición inferior
    protected $up; //Posición superior
    protected $data;
    protected $range;
    protected $position;
    protected $ball;
    protected $balls; //Cantidad máxima de bolos a sacar
    protected $count;
    protected $conn;
    protected $days; //Días anteriores a la jugada a tomar en cuenta
    protected $pastGames; //Cantidad de días anteriores que deben tener al menos una de las jugadas de hoy
 

    /************************************* Cálculo del ************************************/
    /*************************************   rango     ************************************/

    //1.SE ESTABLECE EL RANGO

    //Maximo numero en cualquier posicion
    protected function maxNumberRange($position, $conn) {       
        $result = $conn -> query("SELECT max(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Mínimo numero en cualquier posicion
    protected function minNumberRange($position, $conn) {       
        $result = $conn -> query("SELECT min(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Rango en el que pueden estar los números
    protected function numberRange($position, $conn) {
        $maxNumber = $this-> maxNumberRange($position, $conn);
        $minNumber = $this-> minNumberRange($position, $conn);

        return rand($minNumber, $maxNumber);
    }
    
    //2. CALCULAR LAS POSICIONES DE LAS JUGADAS

    //Arreglos de todas las jugadas pasadas
    protected function positionCalculation($position, $conn) {
        $result = $conn -> query ("SELECT number FROM numbers WHERE position = '$position' ORDER BY date desc;");

        $positionArray = [];

        while($row = $result -> fetch_assoc()) {
            $positionArray[] = intval($row["number"]);
        }

        return $positionArray;
    }
    //Total de jugadas
    protected function totalPlays($conn) {
        $result = $conn -> query ("SELECT count(*) as total FROM numbers WHERE position = 1 ORDER BY date desc;");

        $row = $result -> fetch_assoc();

        return $row["total"];
    }
    
    //Arreglo de los arreglos de todas las jugadas pasadas
    protected function totalNumbersArrays($balls, $conn){
        $positionArray = [];

        for ($i = 1; $i <= $balls; $i++) {
            $positionArray [] = $this-> positionCalculation($i, $conn);
        }
        return $positionArray;
    }

    protected function totalNumbers($balls, $conn){
        $positionArray = $this -> totalNumbersArrays($balls, $conn);

        $totalPosition = [];

        for($i = 0; $i < count($positionArray[1]); $i++) {
            $partialPosition = []; 

            for($j=0; $j<count($positionArray); $j++) {
                $partialPosition [] = $positionArray[$j][$i];
            }

            $totalPosition [] = $partialPosition;
        }        

        return $totalPosition;
    }

    /*************************************   Generando  ************************************/
    /*************************************    números   ************************************/

    //3. SE GENERA EL NUMERO
    //Filter 1
    protected function arrayNumbers($balls, $conn) {
        $arrayNumbers = [];

        for($i = 1; $i <= $balls; $i++) {
            $arrayNumbers [] = $this-> numberRange($i, $conn);
        }     
       
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        sort($arrayNumbers);

        return $arrayNumbers;
    }

    //4. SE INCLUYE EL O LOS NUMEROS QUE MAS SALEN

    //Incluye números que más se  repiten de sorteos anteriores
    //Filter 2
    protected function lastNumbersPlayed($days, $balls, $conn) { 
        $lastNumbers = [];

        for ($i = 1; $i <= $balls; $i++) {
            for($j = 0; $j < $days; $j++) {
                $lastNumbers [] = $this-> positionCalculation($i, $conn)[$j];
            }
        }

        return $lastNumbers;
    }

    protected function lastNumbersMoreOftenPlayed ($days, $balls, $conn) {  
        $lastNumbers = $this-> lastNumbersPlayed($days, $balls, $conn);

        $valuesArray = array_count_values ($lastNumbers);

        arsort($valuesArray);

        $keyArray = array_keys($valuesArray);
        
        return $keyArray;
    }

    protected function normalNumbers($days, $balls, $conn) {
        $arrayNumbers = $this-> arrayNumbers($balls, $conn); 
        //Si ya está completo retornarlo
        if(count($arrayNumbers) == $balls) {
            return $arrayNumbers;
        }

        $keys = $this-> lastNumbersMoreOftenPlayed ($days, $balls, $conn);
        $keys = array_chunk ($keys, $balls);
        $keys = $keys[0];

        while(count($arrayNumbers) != $balls) {
            array_push($arrayNumbers, $keys [rand(0, $balls - 1)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }

    /*************************************   Suma de números  *************************************/
    /**********************************************************************************************/

    //5. CALCULAR EL RANGO DE LAS SUMAS DE LAS JUGADAS

    //Array de la suma
    protected function sumsArrayNumbers($conn) {
        $result = $conn -> query ("SELECT sum(number) AS suma FROM numbers GROUP BY date ORDER BY suma;");

        $sums = [];

        while($row = $result -> fetch_assoc()) {
             $sums []  = intval($row ["suma"]);
        }

        return $sums;
    }
    //Suma de los elementos de un array
    protected function sumArray ($array) {
        $count = count($array);

        if($count == 0) {
            return 0;
        }

        $sum = 0;
        for($i = 0; $i < $count; $i++) {
            $sum += $array[$i];
        }

        return $sum;
    }
  
    //Promedio del array
    protected function average($array) {
        $count = count($array);
        
        $sum = $this -> sumArray ($array);
    
        return $media = $sum / $count;

    }

    //Cantidad de veces que se repite un número en un arreglo
    protected function element_rep ($element, $array) {
        $count = 0;
        for ($i = 0; $i < count($array); $i++) {
            if($array[$i] == $element) {
                $count += 1;
            }
        }
        return $count;
    }
    
    //Rango máximo y mínimo
    protected function minMaxArray($array) {  
        $min =  min($array);
        $max =  max($array);

        return [$min, $max];
    }
    //Condition range
    protected function rangeCondition($data, $range, $array) {
        if($data >= $range [0] && $data <= $range [1]) {
            return $array;
        } else {
            return [];
        }
    }

    //Suma de cada elemento
    protected function number_sum ($down, $up, $conn) {
        //Posiciones a sumar
        $positionArrayDown = $this-> positionCalculation($down, $conn);
        $positionArrayUp = $this-> positionCalculation($up, $conn);

        $positionSums = [];
        //Suma de posiciones
        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionSums [] = $positionArrayUp[$i] + $positionArrayDown[$i];
        }

        return $positionSums;
    }


    /*******************************   Diferencia de números **************************************/
    /**********************************************************************************************/

    //6. CALCULAR EL RANGO DE LAS RESTAS DE UN NUMERO Y OTRO

    protected function number_diff ($down, $up, $conn) {
        $positionArrayDown = $this-> positionCalculation($down, $conn);
        $positionArrayUp = $this-> positionCalculation($up, $conn);

        $positionDiferences = [];

        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionDiferences [] = abs($positionArrayUp[$i] - $positionArrayDown[$i]);
        }

        return $positionDiferences;
    }   
   
    protected function rangeDiffArray ($down, $up, $conn) {
        $array = $this -> number_diff ($down, $up, $conn);

        return $this -> minMaxArray($array);
    }


    //7. RANGO DE DESVIACION ESTANDAR
    
    //Desviación estándar
    protected function standardDeviation ($array) {
        $count = count($array);
        
        $media = $this -> average($array);

        $varianza = 0;
        for($i = 0; $i < $count; $i++) {
            $varianza += pow(($media - $array[$i]), 2);
        }

        $standardDesviation = sqrt($varianza / $count);

        return $standardDesviation;
    }

    protected function standardDeviationArray($multiArray) {

        $standardDevArray = [];

        for($i = 0; $i < count($multiArray); $i++) {
            $standardDevArray [] = $this -> standardDeviation ($multiArray[$i]);
        }

        return $standardDevArray;
    }

    //Desviación estandard del array
    //Filter 3
    protected function rangeStandardDeviation($days, $balls, $conn) {
        //Desviación standard de la jugada
        $array = $this-> normalNumbers($days, $balls, $conn);
        $standardDeviationOfArray =  $this -> standardDeviation ($array);

        //Desviaciones estandares de jugadas anteriores
        $totalArrayNumbers = $this-> totalNumbers($balls, $conn);
        $arrayOfStandardDeviation =  $this -> standardDeviationArray($totalArrayNumbers);

        //Máximo y mínimo de las deviaciónes estándares
        $rangeDev = $this-> minMaxArray($arrayOfStandardDeviation);

        return $this -> rangeCondition ($standardDeviationOfArray, $rangeDev, $array);
    }
    
    //8. EXCLUIR LAS JUGADAS ANTERIORES

    //Verificar si esta jugada ya había salido
    //Filter 4
    protected function lastNumbersExceptions($days, $balls, $conn) {
        $totalNumbers = $this-> totalNumbers($balls, $conn);
        $arrayNumbers = $this-> rangeStandardDeviation($days, $balls, $conn);

        sort($arrayNumbers);
         
        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                return $arrayNumbers = [];
            }
        }      
        return $arrayNumbers;
    }
    
    //9. RANGO DE SUMAS ACEPTADO

    //Incluir rango de sumas
    //Filter 5
    protected function sumRange($days, $balls, $conn) {
        //Array     
        $array = $this -> lastNumbersExceptions($days, $balls, $conn);

        if(count($array) == 0) {
            return [];
        }

        //Suma de los elementos del array
        $sumArray = $this -> sumArray ($array);
        //Array del máximo y mínimo
        $rangeSumArray = $this -> minMaxArray($this -> sumsArrayNumbers($conn));

        return $this -> rangeCondition ($sumArray, $rangeSumArray, $array);
    }

    //11. RANGO DEL PROMEDIO DE TODOS LOS NUMEROS

    protected function averageArray($balls, $conn) {
        $totalArrayNumbers = $this-> totalNumbers($balls, $conn);

        $averageArray = [];

        for($i = 0; $i < count($totalArrayNumbers); $i++) {
            $averageArray [] = $this -> average($totalArrayNumbers[$i]);
        }

        return $averageArray;       
    }

    protected function rangeAvg($balls, $conn) {
        $array = $this -> averageArray($balls, $conn);

        return $this -> minMaxArray($array);
    }

    //Rango de los promedios
    //Filter 7
    protected function rangeAvgArray ($days, $balls, $conn) {
        $array = $this -> sumRange($days, $balls, $conn);

        if(count($array) == 0) {
            return $array;
        }
        //Array del máximo y mínimo
        $rangeAvg = $this -> rangeAvg($balls, $conn);
        //Array average
        $average = $this -> average($array);

        return $this -> rangeCondition ($average, $rangeAvg, $array);
    }

    //12. RANGO DEL PRODUCTO DE TODOS LOS NUMEROS

    protected function productArray ($days, $balls, $conn) {
        $totalArrayNumbers = $this-> totalNumbers($balls, $conn);

        $productArray = [];

        for($i = 0; $i < count($totalArrayNumbers); $i++) {
            $product = 1;
            for($j = 0; $j < count($totalArrayNumbers[$i]); $j++) { 
                $product *= $totalArrayNumbers[$i][$j];
            }
            $productArray[] = $product;
        }       
       
        return $productArray;          
    }

    protected function rangePro($balls, $conn) {        
       $array = $this -> productArray ($days = true, $balls, $conn);

       return $this -> minMaxArray($array);
    }

    protected function product($array) {
        $product = 1;

        for($i = 0; $i < count($array); $i++) {
            $product *= $array[$i];
        }

        return $product;
    }

    //Filter 8
    protected function rangeProArray ($days, $balls, $conn) {
        $array = $this -> rangeAvgArray ($days, $balls, $conn);    
        
        if(count($array) == 0) {
            return $array;
        }

        //Array del máximo y mínimo
        $rangePro = $this -> rangePro($balls, $conn);
        //Array average
        $product = $this -> product($array);

        return $this -> rangeCondition ($product, $rangePro, $array);
    }

    //13. QUITAR NUMEROS DOBLEMENTE CONSECUTIVOS
    //Filter 9
    protected function consecutiveOutArray ($days, $balls, $conn) {
        $array = $this -> rangeProArray ($days, $balls, $conn);

        if(count($array) == 0) {
            return $array;
        }

        $count = 0;

        for($i = 1; $i < count($array); $i++) {
            if($array[$i] - $array[$i - 1] == 1) {
                $count += 1;
            }            
        }

        if($count <= 1) {
            return $array;
        } else {
            return [];
        }        
    }

    /*
    protected function decenas_calculation ($array, $days, $balls, $conn) {

        if(count($array) == 0) {
            return [];
        }

        $decena = [];

        for($i = 0; $i < count($array); $i++) {
            if($array[$i] > 0 && $array[$i] < 10) {
                $decena [] = "first";
            } else if ($array[$i] >= 10 && $array[$i] < 20) {
                $decena [] = "second";
            } else if($array[$i] >= 20 && $array[$i] < 30) {
                $decena [] = "third";
            } else {
                $decena [] = "fourth";
            }
        }       

        return $decena;
    }

    protected function decenas ($days, $balls, $conn) {
        $array = $this -> consecutiveOutArray ($days, $balls, $conn);
        $decena = $this -> decenas_calculation ($array, $days, $balls, $conn);

        $decena = array_unique ($decena);

        if(count($decena) < 3) {
            return [];
        } else {
            return $array;
        }
    }  
    */
   /* protected function pastDaysAccount ($days, $balls, $conn, $pastGames) {
        $array = $this -> consecutiveOutArray ($days, $balls, $conn);
        $totalNumbers = $this -> totalNumbers($balls, $conn);

        $lastPlayArray = $totalNumbers [$pastGames - 1];

        for($i = 0; $i < count($array); $i++) {
            if(in_array($array [$i], $lastPlayArray)) {
                return $array;
            } 
        }  
        
        return [];
    } */
    /*
    protected function oddEvenCal($days, $array, $balls, $conn) {

        if(count($array) == 0) {
            return false;
        }

        $even = 0;

        for($i = 0; $i < count($array); $i++) {
            if ($array [$i] % 2 == 0) {
                $even += 1;
            }
        }  
        
        return $even;
    }
    */
    public function multipleCalculation ($times, $number, $balls, $conn) {
        $totalNumbers = $this -> totalNumbers($balls, $conn);
        //Total de jugadas
        $totalPlays = $this -> totalPlays($conn);

        $count = [];

        for ($i = 0; $i < count($totalNumbers); $i++) {
            $repeat = 0;
            for($j = 0; $j < count($totalNumbers[$i]); $j++) {
                if($totalNumbers[$i][$j] % $number == 0) {
                    $repeat += 1;
                }
            }
            $count [] = $repeat;
        }

        $rep = $this -> element_rep ($times, $count);

        return intval(round ($rep * 100/$totalPlays));
    }

    protected function multipleCounter($number, $array) {
        if(count($array) == 0) {
            return -1;
        }

        $count = 0;
        
        for($i=0; $i < count($array); $i++) {
            if($array [$i] % $number == 0) {
                $count += 1;
            }
        }
        return $count;
    }

    //Final
    //Filter 15
    abstract protected function finalNumbers ($days, $balls, $conn);
}

class LotoClass extends LotteryClass {
    //4. SE INCLUYE EL O LOS NUMEROS QUE MAS SALEN

    //Incluye números que más se  repiten de sorteos anteriores

    protected function normalNumbers($days, $balls, $conn) {

        $keys = [];

        for ($i = 1; $i <= $balls; $i++) {            
            $keys [] = $this-> positionCalculation($i, $conn)[1]/*Dos días atrás*/;
        }

        $arrayNumbers = $this-> arrayNumbers($balls, $conn); 

        while(count($arrayNumbers) != $balls) {
            array_push($arrayNumbers, $keys [rand(0, $balls - 1)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }


// Calculo de pares e impares   
 /*   protected function oddEvenArray ($days, $balls, $conn, $pastGames) {
        $array = $this -> pastDaysAccount ($days, $balls, $conn, $pastGames);
        $even = $this -> oddEvenCal($days, $array, $balls, $conn);

        if($even == false) {
            return [];
        }

        if($even == 2 || $even == 3 || $even == 4) {
            return $array;
        } else {
            return [];
        }
    }

    protected function multiple_test ($array, $days, $times, $number, $balls, $conn){
        $percentage = $this -> multipleCalculation ($times, $number, $balls, $conn);           
        $repeat = $this -> multipleCounter($number, $array);

        if ($repeat == $times) {
            if($percentage > 10) {
                return $array;
            } else {
                return [];
            }  
        } else {
            return $array;
        }         
    }
   
    protected function multiple($days, $balls, $conn, $pastGames) {   
        $array = $this -> pastDaysAccount ($days, $balls, $conn, $pastGames); 
        $array = $this -> multiple_test ($array, $days, 0, 2, $balls, $conn);   
        $array = $this -> multiple_test ($array, $days, 1, 2, $balls, $conn); 
        $array = $this -> multiple_test ($array, $days, 2, 2, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 2, $balls, $conn);   
        $array = $this -> multiple_test ($array, $days, 4, 2, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 10, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 10, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 10, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 10, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 10, $balls, $conn);  

        return $array;
    }
*/
 //Final
    public function finalNumbers ($days, $balls, $conn) {
        $array = $this -> consecutiveOutArray ($days, $balls, $conn); 
        sort($array);
        return $array;
    }
}

?>