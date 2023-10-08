<?php
require_once ("Random.Generators.Class.php");

class RangeNumbers {
    protected $totalNumbers;
    protected int $amount;
    protected $arrayNumbers;
    protected $array;
    protected int $down; 
    protected int $up;
    protected $data;
    protected $range;
    protected int $time = 1;
    protected $allArray;
    protected int $positions = 3;
    protected int $frequency = 5;
    protected int $balls;
    protected int $count;


    /************************************* Cálculo del ************************************/
    /*************************************   rango     ************************************/

    //1.SE ESTABLECE EL RANGO

    //Maximo numero en cualquier posicion
    protected function maxNumberRange($position) {
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT max(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Mínimo numero en cualquier posicion
    protected function minNumberRange($position) {
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT min(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Rango en el que pueden estar los números
    protected function numberRange($position) {
        $maxNumber = $this-> maxNumberRange($position);
        $minNumber = $this-> minNumberRange($position);

        return rand ($minNumber, $maxNumber);
    }

    //2. CALCULAR LAS POSICIONES DE LAS JUGADAS

    //Arreglos de todas las jugadas pasadas
    protected function positionCalculation($position) {
        $conn = DatabaseClass::dbConnection();  
        $result = $conn -> query ("SELECT number FROM numbers WHERE position = '$position' ORDER BY date desc;");

        $positionArray = [];

        while($row = $result -> fetch_assoc()) {
            $positionArray[] = intval($row["number"]);
        }

        return $positionArray;
    }

    protected function totalPlays() {
        $conn = DatabaseClass::dbConnection(); 
        $result = $conn -> query ("SELECT count(*) as total FROM numbers WHERE position = 1 ORDER BY date desc;");

        $row = $result -> fetch_assoc();

        return $row["total"];
    }
    
    //Arreglo de los arreglos de todas las jugadas pasadas
    protected function totalNumbers($balls){
        $positionArray = [];

        for ($i = 1; $i <= $balls; $i++) {
            $positionArray [] = $this-> positionCalculation($i);
        }

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
    
    protected function arrayNumbers($arrayNumbers = null) {
        $arrayNumbers = [$this-> numberRange(1), $this-> numberRange(2), $this-> numberRange(3), $this-> numberRange(4), $this-> numberRange(5)]; 
        
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        sort($arrayNumbers);

        return $arrayNumbers;
    }

    //4. SE INCLUYE EL O LOS NUMEROS QUE MAS SALEN

    //Incluye números de sorteos anteriores
    protected function normalNumbers($arrayNumbers = null, $amount) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = $this-> arrayNumbers(); 

        $result = $conn -> query ("SELECT number, count(*) as total FROM numbers GROUP BY number ORDER BY total desc LIMIT $amount;");

        $numbers = [];
        
        while($row = $result -> fetch_assoc()){
            $numbers [] = intval($row["number"]);
        }        
   
        while(count($arrayNumbers) != $amount) {
            array_push($arrayNumbers, $numbers[rand(0, $amount - 1)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }


    /*************************************   Suma de números  *************************************/
    /**********************************************************************************************/

    //5. CALCULAR EL RANGO DE LAS SUMAS DE LAS JUGADAS

    //Array de la suma
    protected function sumsArrayNumbers() {
        $conn = DatabaseClass::dbConnection();  
        $result = $conn -> query ("SELECT sum(number) AS suma FROM numbers GROUP BY date ORDER BY suma;");

        $sums = [];

        while($row = $result -> fetch_assoc()) {
             $sums []  = $row ["suma"];
        }

        return $sums;
    }
    //Suma de los elementos de un array
    protected function sumArray ($array) {
        $count = count($array);

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
    protected function number_sum ($down, $up) {
        $positionArrayDown = $this-> positionCalculation($down);
        $positionArrayUp = $this-> positionCalculation($up);

        $positionSums = [];

        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionSums [] = $positionArrayUp[$i] + $positionArrayDown[$i];
        }

        return $positionSums;
    }


    /*******************************   Diferencia de números **************************************/
    /**********************************************************************************************/

    //6. CALCULAR EL RANGO DE LAS RESTAS DE UN NUMERO Y OTRO

    protected function number_diff ($down, $up) {
        $positionArrayDown = $this-> positionCalculation($down);
        $positionArrayUp = $this-> positionCalculation($up);

        $positionDiferences = [];

        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionDiferences [] = abs($positionArrayUp[$i] - $positionArrayDown[$i]);
        }

        return $positionDiferences;
    }   
   
    protected function rangeDiffArray ($down, $up) {
        $array = $this -> number_diff ($down, $up);

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
    protected function rangeStandardDeviation() {
        //Desviación standard de la jugada
        $array = $this-> normalNumbers(null, 5);
        $standardDeviationOfArray =  $this -> standardDeviation ($array);

        //Desviaciones estandares de jugadas anteriores
        $totalArrayNumbers = $this-> totalNumbers(5);
        $arrayOfStandardDeviation =  $this -> standardDeviationArray($totalArrayNumbers);

        //Máximo y mínimo de las deviaciónes estándares
        $rangeDev = $this-> minMaxArray($arrayOfStandardDeviation);

        return $this -> rangeCondition ($standardDeviationOfArray, $rangeDev, $array);
    }
    
    //8. EXCLUIR LAS JUGADAS ANTERIORES

    //Verificar si esta jugada ya había salido
    protected function lastNumbersExceptions($arrayNumbers = null) {
        $totalNumbers = $this-> totalNumbers(5);
        $arrayNumbers = $this-> rangeStandardDeviation();

        sort($arrayNumbers);
         
        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                return $arrayNumbers = [];
            }
        }      
        return $arrayNumbers;
    }

    /********************************************Descartar combinaciones anteriores **********************************/
    /*****************************************************************************************************************/
    /*
    //9. EXCLUIR COMBINACIONES DE 3 Y 4 ANTERIORES
    
    protected function intersectArrays ($allArrays, $time) {
        $intersectionsArrays = [];
        for($i = 0; $i < count($allArrays) - $time; $i++) {
            $intersectionsArrays [] = array_intersect($allArrays[$i], $allArrays[$i + $time]);
        } 
        
        return $intersectionsArrays;
    }

    protected function intersectArraysBets ($time) {
        $allArrays = $this -> totalNumbers(5);

        $intersectionsArrays = $this -> intersectArrays ($allArrays, $time);

        return $intersectionsArrays;
    }

    protected function frequencyCalculation ($positions, $time){
        $intersectArrays = $this -> intersectArraysBets ($time);

        $repeat = 0;

        for($i = 0; $i < count($intersectArrays); $i++) {
            if(count($intersectArrays[$i]) == $positions) {
                $repeat += 1;
            }
        } 

        return $repeat;
    }

    protected function intersection ($array, $time, $allArrays) {
        $intersection = array_intersect($allArrays [$time - 1], $array);

        return $intersection;
    }

    protected function intersectCondition ($array, $positions, $time, $frequency) {
        //frequency: cantidad máxima de apariciones aceptadas de la repetición de esa secuencia.
        //array: Jugada a ser examinada.
        //position: cantidad de secuencias a tomar en cuenta. Si son 5 bolos puede ser 1,2,3,4 o 5.
        //time: cantidad de días anteriores a la jugada para ser comparados.
        $allArrays = $this -> totalNumbers(5);

        if(count($array) == 0) {
            return $array;
        }

        $frequencyCalculation = $this -> frequencyCalculation ($positions, $time);

        $intersection = $this -> intersection ($array, $time, $allArrays);
   
        if($frequencyCalculation <= $frequency && count($intersection) == $positions) {
            return $array;
        } else if (count($intersection) < $positions) {
            return $array;
        } else {
            return [];
        }      
    }

    protected function intersectCompare($array, $positions, $balls, $frequency, $count) {

        for($i = 1; $i <= $count; $i++) {
            $array = $this -> intersectCondition ($array, $positions, $i, $frequency);
            if(count($array) == 0) {
                break;
            }
        }        
        return $array;
    }

    protected function insersectArrayOut ($balls) {
        $array = $this -> lastNumbersExceptions();

        sort($array);

        $array = $this -> intersectCompare($array, 4, $balls, 5, 50);
        $array = $this -> intersectCompare($array, 3, $balls, 5, 50);

        return $array;
    }   
   */
    //10. RANGO DE SUMAS ACEPTADO

    //Incluir rango de sumas
    protected function sumRange($arrayNumbers = null) {
        //Array     
        $array =  $this-> lastNumbersExceptions();

        //Suma de los elementos del array
        $sumArray = $this -> sumArray ($array);
        //Array del máximo y mínimo
        $rangeSumArray = $this -> minMaxArray($this -> sumsArrayNumbers());

        return $this -> rangeCondition ($sumArray, $rangeSumArray, $array);
    }

    //11. RANGO DE LA RESTA DE UN NUMERO A OTRO

    //Incluir rango de restas
    protected function diffRange($array = [], $down, $up) {
    
        $array = $this -> sumRange();

        if(count($array) != 0) {
            //Array del máximo y mínimo
            $rangeDiffArray = $this -> rangeDiffArray ($down, $up);
            //Array difference
            $diff = abs($array[$up - 1] - $array[$down - 1]);
            
            return $this -> rangeCondition ($diff, $rangeDiffArray, $array);

        } else {
            return $array;
        }
    }
    //Patrón de restas
    protected function subRange() {
        $array = $this -> diffRange(null, 1, 2);
        $array = $this -> diffRange($array, 1, 3);
        $array = $this -> diffRange($array, 1, 4);
        $array = $this -> diffRange($array, 1, 5);
        $array = $this -> diffRange($array, 2, 3);
        $array = $this -> diffRange($array, 2, 4);
        $array = $this -> diffRange($array, 2, 5);
        $array = $this -> diffRange($array, 3, 4);
        $array = $this -> diffRange($array, 3, 5);
        $array = $this -> diffRange($array, 4, 5);

        return $array;
    }

    //12. RANGO DEL PROMEDIO DE TODOS LOS NUMEROS

    protected function averageArray() {
        $totalArrayNumbers = $this-> totalNumbers(5);

        $averageArray = [];

        for($i = 0; $i < count($totalArrayNumbers); $i++) {
            $averageArray [] = $this -> average($totalArrayNumbers[$i]);
        }

        return $averageArray;       
    }

    protected function rangeAvg() {
        $array = $this -> averageArray();

        return $this -> minMaxArray($array);
    }
    //Rango de los promedios
    protected function rangeAvgArray () {
        $array = $this -> subRange();       

        if(count($array) != 0) {
            //Array del máximo y mínimo
            $rangeAvg = $this -> rangeAvg();
            //Array average
            $average = $this -> average($array);

            return $this -> rangeCondition ($average, $rangeAvg, $array);
        } else {
            return $array;
        }
    }

    //13. RANGO DEL PRODUCTO DE TODOS LOS NUMEROS

    protected function productArray () {
        $totalArrayNumbers = $this-> totalNumbers(5);

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

    protected function rangePro() {
       $array = $this -> productArray();

       return $this -> minMaxArray($array);
    }

    protected function product($array) {
        $product = 1;

        for($i = 0; $i < count($array); $i++) {
            $product *= $array[$i];
        }

        return $product;
    }

    protected function rangeProArray () {
        $array = $this -> rangeAvgArray ();       

        if(count($array) != 0) {
            //Array del máximo y mínimo
            $rangePro = $this -> rangePro();
            //Array average
            $product = $this -> product($array);

            return $this -> rangeCondition ($product, $rangePro, $array);
        } else {
            return $array;
        }
    }

    //14. QUITAR NUMEROS DOBLEMENTE CONSECUTIVOS
    protected function consecutiveOutArray ($arrayNumbers = null){
        $array = $this -> rangeAvgArray();

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

    //15. RANGO PARA LA SUMA DE ELEMENTOS CONSECUTIVOS
    protected function elementArraySum ($array, $down, $up) {        
        $sum = $array[$down - 1] + $array[$up - 1];
        return $sum;        
    }

    protected function rangeSumEach($array, $down, $up) {
        if(count($array) != 0) {
            //Arreglo de la suma de elementos consecutivos de los números jugados anteriormente
            $arrayOfTheSumArray = $this -> number_sum ($down,$up);
            //Arreglo del máximo y el mínimo
            $maxMinArray = $this -> minMaxArray($arrayOfTheSumArray);
            //Suma de elemento con elemento de los números candidatos
            $data = $this -> elementArraySum ($array, $down, $up);
            //Comparación de esa suma con el rango
            $array = $this -> rangeCondition($data, $maxMinArray, $array);
            
            return $array;
        } else {
            return $array;
        }
    }
    
    protected function sumEach() {
        $array = $this -> consecutiveOutArray();
        $array = $this -> rangeSumEach($array, 1, 2);
        $array = $this -> rangeSumEach($array, 1, 3);
        $array = $this -> rangeSumEach($array, 1, 4);
        $array = $this -> rangeSumEach($array, 1, 5);
        $array = $this -> rangeSumEach($array, 2, 3);
        $array = $this -> rangeSumEach($array, 2, 4);
        $array = $this -> rangeSumEach($array, 2, 5);
        $array = $this -> rangeSumEach($array, 3, 4);
        $array = $this -> rangeSumEach($array, 3, 5);
        $array = $this -> rangeSumEach($array, 4, 5);

        return $array;
    }
    
    //16. QUITAR LOS ALEATORIOS DE HOY    
    protected function randOutArray ($amount){
        $array = $this -> sumEach();

        if(count($array) != 0) {
            //Números aleatorios
            $randomNumbers = new RandomGenerator(1, 31, 5, $amount);
            $randomNumbers = $randomNumbers -> randGen(); 

            for($i = 0; $i < count($randomNumbers); $i++) {
                if($randomNumbers[$i] != $array) {
                    return $array;
                } else {
                    return [];
                }
            }
        } else {
            return $array;
        }
    }
    
    //Final
    public function finalNumbers () {
        $array = $this -> randOutArray(80000);
        sort($array);
        return $array;
    }
}
?>