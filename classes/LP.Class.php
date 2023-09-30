<?php
class RangeNumbers {
    protected $position;
    protected $start;
    protected $totalNumbers;
    protected $amount;
    protected $time;
    protected $arrayNumbers;


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


    /*************************************   Generando  ************************************/
    /*************************************    números   ************************************/

    //2. SE GENERA EL NUMERO
    
    protected function arrayNumbers($arrayNumbers = null) {
        $arrayNumbers = [$this-> numberRange(1), $this-> numberRange(2), $this-> numberRange(3), $this-> numberRange(4), $this-> numberRange(5)]; 
        
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        sort($arrayNumbers);

        return $arrayNumbers;
    }

    //3. SE EXCLUYE EL O LOS NUMEROS QUE MENOS SALEN

    protected function rareNumbersOut($arrayNumbers = null, $amount) {
        $conn = DatabaseClass::dbConnection();  
        $arrayNumbers = $this-> arrayNumbers();   
       
        $result = $conn -> query ("SELECT number, count(*) as total FROM numbers GROUP BY number ORDER BY total asc LIMIT $amount;");
        while($row = $result -> fetch_assoc()){
            $number = intval($row["number"]);
            if(in_array($number, $arrayNumbers) && count($arrayNumbers) > $amount) {
                $arrayNumbers = array_diff($arrayNumbers, array($number));                               
            }
        }  

        sort($arrayNumbers);
        
        return $arrayNumbers;
    }  

    //4. SE INCLUYE EL O LOS NUMEROS QUE MAS SALEN

    //Incluye números de sorteos anteriores
    protected function normalNumbers($arrayNumbers = null, $amount) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = $this-> rareNumbersOut (null, 1);

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
    //Suma de los elementos de array
    private function sumArray ($array) {
        $count = count($array);

        $sum = 0;
        for($i = 0; $i < $count; $i++) {
            $sum += $array[$i];
        }

        return $sum;
    }
    //Promedio del array
    private function average($array) {
        $sum = $this -> sumArray ($array);
       
        return $media = $sum / count($array);
    }

    //Desviación estándar
    private function standardDeviation ($array) {
        $count = count($array);
        $media = $this -> average($array);

        $varianza = 0;
        for($i = 0; $i < $count; $i++) {
            $varianza += pow(($media - $array[$i]), 2);
        }

        $standardDesviation = sqrt($varianza / $count);

        return $standardDesviation;
    }

    //Desviación estandard del array
    protected function arrayStandardDeviation() {
        $sumsArrayNumbers = $this -> sumsArrayNumbers();
        return $this -> standardDeviation ($sumsArrayNumbers);
    }

    protected function minSum($margin) {
        $sumsArrayNumbers = $this -> sumsArrayNumbers();
        $arrayStandardDeviation = $this -> arrayStandardDeviation();

        $minSum = floor(($this -> average($sumsArrayNumbers) - $arrayStandardDeviation)) - $margin;

        return $minSum;
    }

    protected function maxSum($margin) {
        $sumsArrayNumbers = $this -> sumsArrayNumbers();
        $arrayStandardDeviation = $this -> arrayStandardDeviation();

        $maxSum = ceil(($this -> average($sumsArrayNumbers) + $arrayStandardDeviation)) + $margin;

        return $maxSum;
    }

    protected function rangeSumArray () {
        $minSum = $this -> minSum(8);
        $maxSum = $this -> maxSum(8);

        return [$minSum, $maxSum];
    }

    /*************************************    Arreglos de  ************************************/
    /************************************* todas las jugadas **********************************/

    //6. CALCULAR LAS POSICIONES DE LAS JUGADAS

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
    
    //Arreglo de los arreglos de todas las jugadas pasadas
    protected function totalNumbers(){
        $positionArray1 = $this-> positionCalculation(1);
        $positionArray2 = $this-> positionCalculation(2);
        $positionArray3 = $this-> positionCalculation(3);
        $positionArray4 = $this-> positionCalculation(4);
        $positionArray5 = $this-> positionCalculation(5);

        $totalPosition = [];

        for($i = 0; $i < count($positionArray1); $i++) {
            $totalPosition[$i] = [$positionArray1[$i], $positionArray2[$i], $positionArray3[$i], $positionArray4[$i], $positionArray5[$i]];
        }        

        return $totalPosition;
    }

    
    /*******************************   Diferencia de números **************************************/
    /**********************************************************************************************/

    //7. CALCULAR EL RANGO DE LAS RESTAS DE UN NUMERO Y OTRO

    protected function number_diff ($down, $up) {
        $positionArrayDown = $this-> positionCalculation($down);
        $positionArrayUp = $this-> positionCalculation($up);

        $positionDiferences = [];

        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionDiferences [] = abs($positionArrayUp[$i] - $positionArrayDown[$i]);
        }

        return $positionDiferences;
    }

    protected function minSumDiff($down, $up) {
        $array = $this -> number_diff ($down, $up);

        sort($array);

        $minSum = $array[0];

        return $minSum;
    }

    protected function maxSumDiff($down, $up) {
        $array = $this -> number_diff ($down, $up);

        sort($array);

        $count = count($array);      

        $maxSum = $array [$count - 1];

        return $maxSum;
    }
    
    protected function rangeDiffArray ($down, $up) {
        $minSum = $this -> minSumDiff($down, $up);
        $maxSum = $this -> maxSumDiff($down, $up);

        return [$minSum, $maxSum];
    }

    
    //8. EXCLUIR LAS JUGADAS ANTERIORES

    //Verificar si esta jugada ya había salido
    protected function lastNumbersExceptions() {
        $totalNumbers = $this-> totalNumbers();
        $arrayNumbers = $this-> normalNumbers(null, 5);

        sort($arrayNumbers);
         
        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                return $arrayNumbers = [];
            }
        }      
        return $arrayNumbers;
    }

    //9. GENERADOR DE ALEATORIOS PARA SER EXCLUIDOS

    //Generador de random
    protected function randomGenerator($amount) {
        $randomArraysOfTheDay = [];

        while(count($randomArraysOfTheDay) < $amount) {
            $generatedRandomArray = [];
            while(count($generatedRandomArray)< 5) {
                $generatedRandomArray [] = rand(1,31);
                $generatedRandomArray = array_unique($generatedRandomArray, SORT_NUMERIC);
            }

            sort($generatedRandomArray);
            
            $randomArraysOfTheDay [] = $generatedRandomArray;
        }

       return $randomArraysOfTheDay;
    }

    //10. EXCLUSION DE ALEATORIOS

    //Verificar si esta jugada ya había salido
    protected function randomNumbersExceptions ($totalNumbers, $arrayNumbers) {
        sort($arrayNumbers);
         
        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                $arrayNumbers = [];
                return $arrayNumbers;
            }
        }      
        return $arrayNumbers;
    }
/*
    //Excluir los número aleatorios
    protected function randomOfTheDayException() {
       $arrayNumbers = $this-> lastNumbersExceptions();

       if(count($arrayNumbers) !== 0) {

        $randomArraysOfTheDay = $this-> randomGenerator(100);

        $arrayNumbers = $this-> randomNumbersExceptions($randomArraysOfTheDay, $arrayNumbers);
        
        return $arrayNumbers;
       } else {
            return [];
       }
    }
*/
    //11. RANGO DE SUMAS ACEPTADO

    //Incluir rango de sumas
    protected function sumRange() {
        //Array        
        $totalNumbers =  $this-> lastNumbersExceptions();
        //Suma de los elementos del array
        $sumArray = $this -> sumArray ($totalNumbers);
        //Array del máximo y mínimo
        $rangeSumArray = $this -> rangeSumArray ();

        if($sumArray >= $rangeSumArray [0] && $sumArray <= $rangeSumArray [1]) {
            return $totalNumbers;
        } else {
            return [];
        }
    }

    //12. RANGO DE LA RESTA DE UN NUMERO A OTRO

    //Incluir rango de restas
    protected function diffRange($array = [], $down, $up) {
    
        $array = $this -> sumRange();

        if(count($array) != 0) {
            //Array del máximo y mínimo
            $rangeDiffArray = $this -> rangeDiffArray ($down, $up);
            //Array difference
            $diff = abs($array[$up - 1] - $array[$down - 1]);

            if($diff >= $rangeDiffArray [0] && $diff <= $rangeDiffArray [1]) {
                return $array;
            } else {
                return [];
            }
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

    //13. RANGO DEL PROMEDIO DE TODOS LOS NUMEROS

    protected function averageArray() {
        $totalArrayNumbers = $this-> totalNumbers();

        $averageArray = [];

        for($i = 0; $i < count($totalArrayNumbers); $i++) {
            $averageArray [] = $this -> average($totalArrayNumbers[$i]);
        }

        return $averageArray;       
    }

    protected function minSumAvg() {
        $array = $this -> averageArray();

        sort($array);

        $minAvg = $array[0];

        return $minAvg;
    }

    protected function maxSumAvg() {
        $array = $this -> averageArray();

        sort($array);

        $count = count($array);      

        $maxAvg = $array [$count - 1];

        return $maxAvg;
    }
    
    protected function rangeAvg() {
        $minAvg = $this -> minSumAvg();
        $maxAvg = $this -> maxSumAvg();

        return [$minAvg, $maxAvg];
    }

    protected function rangeAvgArray () {
        $array = $this -> subRange();       

        if(count($array) != 0) {
            //Array del máximo y mínimo
            $rangeAvg = $this -> rangeAvg();
            //Array average
            $average = $this -> average($array);

            if($average >= $rangeAvg [0] && $average <= $rangeAvg [1]) {
                return $array;
            } else {
                return [];
            }
        } else {
            return $array;
        }
    }

    //14. RANGO DEL PRODUCTO DE TODOS LOS NUMEROS

    protected function productArray () {
        $totalArrayNumbers = $this-> totalNumbers();

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

    protected function minProAvg() {
        $array = $this -> productArray();

        sort($array);

        $minPro = $array[0];

        return $minPro;
    }

    protected function maxProAvg() {
        $array = $this -> productArray();

        sort($array);

        $count = count($array);      

        $maxPro = $array [$count - 1];

        return $maxPro;
    }
    
    protected function rangePro() {
        $minPro = $this -> minSumPro();
        $maxPro = $this -> maxSumPro();

        return [$minPro, $maxPro];
    }

    private function product($array) {
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

            if($product >= $rangePro [0] && $product <= $rangePro [1]) {
                return $array;
            } else {
                return [];
            }
        } else {
            return $array;
        }
    }

    public function finalNumbers () {
        $totalNumbers = $this -> rangeAvgArray();
        return $totalNumbers;
    }
}
?>