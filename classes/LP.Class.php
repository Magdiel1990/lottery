<?php
class RangeNumbers {
    protected $position;
    protected $start;
    protected $totalNumbers;
    protected $amount;
    protected $time;
    protected $arrayNumbers;
    protected $array;
    protected $down; 
    protected $up;


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

   
    protected function rangeDiffArray ($down, $up) {
        $array = $this -> number_diff ($down, $up);

        return $this -> minMaxArray($array);
    }
    

     //15. RANGO DE DESVIACION ESTANDAR
    
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
    public function rangeStandardDeviation() {
        //Desviación standard de la jugada
        $array = $this-> normalNumbers(null, 5);
        $standardDeviationOfArray =  $this -> standardDeviation ($array);

        //Desviaciones estandares de jugadas anteriores
        $totalArrayNumbers = $this-> totalNumbers();
        $arrayOfStandardDeviation =  $this -> standardDeviationArray($totalArrayNumbers);

        //Máximo y mínimo de las deviaciónes estándares
        $rangeDev = $this-> minMaxArray($arrayOfStandardDeviation);

        if($standardDeviationOfArray >= $rangeDev [0] && $standardDeviationOfArray <= $rangeDev [1]) {
            return $array;
        } else {
            return [];
        }
    }
    
    //8. EXCLUIR LAS JUGADAS ANTERIORES

    //Verificar si esta jugada ya había salido
    protected function lastNumbersExceptions() {
        $totalNumbers = $this-> totalNumbers();
        $arrayNumbers = $this-> rangeStandardDeviation();

        sort($arrayNumbers);
         
        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                return $arrayNumbers = [];
            }
        }      
        return $arrayNumbers;
    }

    //11. RANGO DE SUMAS ACEPTADO

    //Incluir rango de sumas
    protected function sumRange() {
        //Array        
        $totalNumbers =  $this-> lastNumbersExceptions();
        //Suma de los elementos del array
        $sumArray = $this -> sumArray ($totalNumbers);
        //Array del máximo y mínimo
        $rangeSumArray = $this -> minMaxArray($this -> sumsArrayNumbers());

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

    protected function rangeAvg() {
        $array = $this -> averageArray();

        return $this -> minMaxArray($array);
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

            if($product >= $rangePro [0] && $product <= $rangePro [1]) {
                return $array;
            } else {
                return [];
            }
        } else {
            return $array;
        }
    }

    //14. QUITAR NUMEROS DOBLEMENTE CONSECUTIVOS
    protected function consecutiveOutArray (){
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
        
    //Final
    public function finalNumbers () {
        $totalNumbers = $this -> consecutiveOutArray();
        sort($totalNumbers);
        return $totalNumbers;
    }
}


?>