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
    protected $time;
    protected $allArray;
    protected $position;
    protected $frequency;
    protected $ball;
    protected $balls; //Cantidad máxima de bolos a sacar
    protected $count;
    protected $conn;
    protected $days; //Días anteriores a la jugada a tomar en cuenta
 

    /************************************* Cálculo del ************************************/
    /*************************************   rango     ************************************/

    //1.SE ESTABLECE EL RANGO

    //Maximo numero en cualquier posicion
    private function maxNumberRange($position, $conn) {       
        $result = $conn -> query("SELECT max(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Mínimo numero en cualquier posicion
    private function minNumberRange($position, $conn) {       
        $result = $conn -> query("SELECT min(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Rango en el que pueden estar los números
    private function numberRange($position, $conn) {
        $maxNumber = $this-> maxNumberRange($position, $conn);
        $minNumber = $this-> minNumberRange($position, $conn);

        return rand ($minNumber, $maxNumber);
    }

    //2. CALCULAR LAS POSICIONES DE LAS JUGADAS

    //Arreglos de todas las jugadas pasadas
    private function positionCalculation($position, $conn) {
        $result = $conn -> query ("SELECT number FROM numbers WHERE position = '$position' ORDER BY date desc;");

        $positionArray = [];

        while($row = $result -> fetch_assoc()) {
            $positionArray[] = intval($row["number"]);
        }

        return $positionArray;
    }

    protected function totalPlays($conn) {
        $result = $conn -> query ("SELECT count(*) as total FROM numbers WHERE position = 1 ORDER BY date desc;");

        $row = $result -> fetch_assoc();

        return $row["total"];
    }
    
    //Arreglo de los arreglos de todas las jugadas pasadas
    private function totalNumbersArrays($balls, $conn){
        $positionArray = [];

        for ($i = 1; $i <= $balls; $i++) {
            $positionArray [] = $this-> positionCalculation($i, $conn);
        }
        return $positionArray;
    }

    private function totalNumbers($balls, $conn){
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
    private function arrayNumbers($balls, $conn) {
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
    private function lastNumbersPlayed($days, $balls, $conn) { 
        $lastNumbers = [];

        for ($i = 1; $i <= $balls; $i++) {
            for($j = 0; $j < $days; $j++) {
                $lastNumbers [] = $this-> positionCalculation($i, $conn)[$j];
            }
        }

        return $lastNumbers;
    }

    private function lastNumbersMoreOftenPlayed ($days, $balls, $conn) {  
        $lastNumbers = $this-> lastNumbersPlayed($days, $balls, $conn);

        $valuesArray = array_count_values ($lastNumbers);

        arsort($valuesArray);

        $keyArray = array_keys($valuesArray);
        
        return $keyArray;
    }

    protected function normalNumbers($days, $balls, $conn) {
        $arrayNumbers = $this-> arrayNumbers($balls, $conn); 

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
    private function sumsArrayNumbers($conn) {
        $result = $conn -> query ("SELECT sum(number) AS suma FROM numbers GROUP BY date ORDER BY suma;");

        $sums = [];

        while($row = $result -> fetch_assoc()) {
             $sums []  = $row ["suma"];
        }

        return $sums;
    }
    //Suma de los elementos de un array
    private function sumArray ($array) {
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
    private function minMaxArray($array) {  
        $min =  min($array);
        $max =  max($array);

        return [$min, $max];
    }
    //Condition range
    private function rangeCondition($data, $range, $array) {
        if($data >= $range [0] && $data <= $range [1]) {
            return $array;
        } else {
            return [];
        }
    }

    //Suma de cada elemento
    private function number_sum ($down, $up, $conn) {
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

    private function number_diff ($down, $up, $conn) {
        $positionArrayDown = $this-> positionCalculation($down, $conn);
        $positionArrayUp = $this-> positionCalculation($up, $conn);

        $positionDiferences = [];

        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionDiferences [] = abs($positionArrayUp[$i] - $positionArrayDown[$i]);
        }

        return $positionDiferences;
    }   
   
    private function rangeDiffArray ($down, $up, $conn) {
        $array = $this -> number_diff ($down, $up, $conn);

        return $this -> minMaxArray($array);
    }


    //7. RANGO DE DESVIACION ESTANDAR
    
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

    private function standardDeviationArray($multiArray) {

        $standardDevArray = [];

        for($i = 0; $i < count($multiArray); $i++) {
            $standardDevArray [] = $this -> standardDeviation ($multiArray[$i]);
        }

        return $standardDevArray;
    }

    //Desviación estandard del array
    //Filter 3
    private function rangeStandardDeviation($days, $balls, $conn) {
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
    private function lastNumbersExceptions($days, $balls, $conn) {
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

        //Suma de los elementos del array
        $sumArray = $this -> sumArray ($array);
        //Array del máximo y mínimo
        $rangeSumArray = $this -> minMaxArray($this -> sumsArrayNumbers($conn));

        return $this -> rangeCondition ($sumArray, $rangeSumArray, $array);
    }

    //10. RANGO DE LA RESTA DE UN NUMERO A OTRO

    //Incluir rango de restas
    protected function diffRange($array, $down, $up, $conn) {
        if(count($array) != 0) {
            //Array del máximo y mínimo
            $rangeDiffArray = $this -> rangeDiffArray ($down, $up, $conn);
            //Array difference
            $diff = abs($array[$up - 1] - $array[$down - 1]);
            
            return $this -> rangeCondition ($diff, $rangeDiffArray, $array);

        } else {
            return $array;
        }
    }
    //Patrón de restas
    abstract protected function diffRangeLoop($array, $conn);

    //Patrón de restas
    //Filter 6
    protected function subRange($days, $balls, $conn) {            
        $array = $this -> sumRange($days, $balls, $conn);

        $array = $this -> diffRangeLoop ($array, $conn);

        return $array;
    }

    //11. RANGO DEL PROMEDIO DE TODOS LOS NUMEROS

    private function averageArray($balls, $conn) {
        $totalArrayNumbers = $this-> totalNumbers($balls, $conn);

        $averageArray = [];

        for($i = 0; $i < count($totalArrayNumbers); $i++) {
            $averageArray [] = $this -> average($totalArrayNumbers[$i]);
        }

        return $averageArray;       
    }

    private function rangeAvg($balls, $conn) {
        $array = $this -> averageArray($balls, $conn);

        return $this -> minMaxArray($array);
    }

    //Rango de los promedios
    //Filter 7
    private function rangeAvgArray ($days, $balls, $conn) {
        $array = $this -> subRange($days, $balls, $conn);       

        if(count($array) != 0) {
            //Array del máximo y mínimo
            $rangeAvg = $this -> rangeAvg($balls, $conn);
            //Array average
            $average = $this -> average($array);

            return $this -> rangeCondition ($average, $rangeAvg, $array);
        } else {
            return $array;
        }
    }

    //12. RANGO DEL PRODUCTO DE TODOS LOS NUMEROS

    private function productArray ($balls, $conn) {
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

    private function rangePro($balls, $conn) {
       $array = $this -> productArray($balls, $conn);

       return $this -> minMaxArray($array);
    }

    private function product($array) {
        $product = 1;

        for($i = 0; $i < count($array); $i++) {
            $product *= $array[$i];
        }

        return $product;
    }

    //Filter 8
    protected function rangeProArray ($days, $balls, $conn) {
        $array = $this -> rangeAvgArray ($days, $balls, $conn);       

        if(count($array) != 0) {
            //Array del máximo y mínimo
            $rangePro = $this -> rangePro($balls, $conn);
            //Array average
            $product = $this -> product($array);

            return $this -> rangeCondition ($product, $rangePro, $array);
        } else {
            return $array;
        }
    }

    //13. QUITAR NUMEROS DOBLEMENTE CONSECUTIVOS
    //Filter 9
    protected function consecutiveOutArray ($days, $balls, $conn) {
        $array = $this -> rangeProArray ($days, $balls, $conn);

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

    //14. RANGO PARA LA SUMA DE ELEMENTOS CONSECUTIVOS
    private function elementArraySum ($array, $down, $up) {        
        $sum = $array[$down - 1] + $array[$up - 1];
        return $sum;        
    }

    protected function rangeSumEach($array, $down, $up, $conn) {
        if(count($array) != 0) {
            //Arreglo de la suma de elementos consecutivos de los números jugados anteriormente
            $arrayOfTheSumArray = $this -> number_sum ($down, $up, $conn);
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

    //Patrón de restas
    abstract protected function sumEachLoop($array, $conn);

    //Filter 10
    protected function sumEach($days, $balls, $conn) {
        $array = $this -> consecutiveOutArray ($days, $balls, $conn);
        
        $array = $this -> sumEachLoop($array, $conn);
   
        return $array;
    }

    
    /********************************************Descartar combinaciones anteriores **********************************/
    /*****************************************************************************************************************/

    //16. EXCLUIR COMBINACIONES DE 3 Y 4 ANTERIORES
    private function intersectArrays ($allArrays, $time) {
        $intersectionsArrays = [];
        for($i = 0; $i < count($allArrays) - $time; $i++) {
            $intersectionsArrays [] = array_intersect($allArrays[$i], $allArrays[$i + $time]);
        } 
        
        return $intersectionsArrays;
    }

    private function intersectArraysBets ($time, $balls, $conn) {
        $allArrays = $this -> totalNumbers($balls, $conn);

        $intersectionsArrays = $this -> intersectArrays ($allArrays, $time);

        return $intersectionsArrays;
    }

    private function frequencyCalculation ($position, $time, $balls, $conn){
        $intersectArrays = $this -> intersectArraysBets ($time, $balls, $conn);

        $repeat = 0;

        for($i = 0; $i < count($intersectArrays); $i++) {
            if(count($intersectArrays[$i]) == $position) {
                $repeat += 1;
            }
        } 

        return $repeat;
    }

    private function intersection ($array, $time, $allArrays) {
        $intersection = array_intersect($allArrays [$time - 1], $array);

        return $intersection;
    }

    private function intersectCondition ($array, $position, $time, $frequency, $balls, $conn) {
        //frequency: cantidad máxima de apariciones aceptadas de la repetición de esa secuencia.
        //array: Jugada a ser examinada.
        //position: cantidad de secuencias a tomar en cuenta. Si son 5 bolos puede ser 1,2,3,4 o 5.
        //time: cantidad de días anteriores a la jugada para ser comparados.
        $allArrays = $this -> totalNumbers($balls, $conn);

        if(count($array) == 0) {
            return $array;
        }

        $frequencyCalculation = $this -> frequencyCalculation ($position, $time, $balls, $conn);

        $intersection = $this -> intersection ($array, $time, $allArrays);

        $totalPlays = $this -> totalPlays($conn);
        $frequency = ceil($frequency * ($totalPlays - 1));
   
        if($frequencyCalculation <= $frequency && count($intersection) == $position) {
            return $array;
        } else if (count($intersection) < $position) {
            return $array;
        } else {
            return [];
        }      
    }

    protected function intersectCompare($array, $position, $balls, $frequency, $time, $conn) {

        for($i = 1; $i <= $time; $i++) {
            $array = $this -> intersectCondition ($array, $position, $time, $frequency, $balls, $conn);
            if(count($array) == 0) {
                break;
            }
        }        
        return $array;
    }

    //Filter 12
    abstract protected function insersectArrayOut ($days, $balls, $conn, $frequency);

    /*********************************Descartar los numeros por la frecuencia en que salen ***************************/
    /*****************************************************************************************************************/

    //17. EXCLUIR NUMEROS QUE SALEN CADA CUANTOS DIAS
    private function datesArray ($ball, $conn) {
        $result = $conn -> query ("SELECT date FROM numbers WHERE number = $ball ORDER by date asc;");
        
        $array = [];

        while($row = $result -> fetch_array()) {
            $array [] = $row[0];
        }

        return $array;
    }

    private function diffDatesArray ($ball, $conn) {
        $dateArray = $this -> datesArray ($ball, $conn);

        $diffDateArray = [];

        for ($i = 0; $i < count($dateArray) - 1; $i++) {
            $diffDateArray [] = intval(date("j", strtotime($dateArray[$i+1]) - strtotime($dateArray[$i])));
        }

        return $diffDateArray;
    }

    private function last_appearance ($ball, $conn) {
        date_default_timezone_set("America/Santo_Domingo");       

        $datesArray = $this -> datesArray ($ball, $conn);
        rsort($datesArray);
        
        $lastappearance = $datesArray[0];

        $difference = strtotime(date("Y-m-d h:i:s")) - strtotime($lastappearance);
        
        $difference = intval(date("j", $difference));

        return $difference;
    }

    private function numberPeriodValue ($ball, $conn) {
        $array = $this -> diffDatesArray ($ball, $conn);

        if(count($array) == 0) {
            return [];
        }
        
        $min = min($array);

        $difference = $this -> last_appearance ($ball, $conn);

        if($difference <= $min) {
            return false;
        } else {
            return true;
        }
    }
    //Filter 13
    protected function number_period_filter ($days, $balls, $conn, $frequency) {        
        $array = $this -> insersectArrayOut ($days, $balls, $conn, $frequency);

        $value = true;

        for($i = 0; $i < count($array); $i++) {
            $numberPeriodValue = $this -> numberPeriodValue ($array[$i], $conn);
            if($numberPeriodValue == false) {
                $value = false;
                break;
            }
        }

        if($value == true) {
            return $array;
        } else {
            return [];
        }
    }

    //Ultimo rango
    protected function range_filter($array, $position, $up) {   
        if(count($array) == 0) {
            return [];
        }

        if($array [$position - 1] <= $up) {
            return $array;
        } else {
            return [];
        }
    }   
    /*****************************************Calcular las combinaciones que mas salen ************************/ 
    private function datesNumbers ($down, $period, $conn) {
        $result = $conn -> query ("SELECT date from numbers WHERE number = $down ORDER BY date DESC LIMIT $period;");
        $dates = [];

        while($row = $result -> fetch_assoc()){
          $dates [] = $row ["date"];
        } 
        
        return $dates;
    }

    private function combinations ($down, $up, $period, $conn) {
        $dates = $this -> datesNumbers ($down, $period, $conn);

        $count = 0;

        if(count($dates) != 0) {
            
            for($i = 0; $i < count($dates); $i++) {
                $result = $conn -> query ("SELECT count(id) as `count` FROM numbers WHERE number = '$up' AND date = '" . $dates[$i] . "';");
                $row = $result -> fetch_assoc();
                $count += $row["count"];
            } 
        }

        return $count;
    }

    protected function combination_percentage ($down, $up, $period, $conn) {
        $count = $this -> combinations ($down, $up, $period, $conn);

        return ($count/$period)*100;
    }

    abstract protected function combination_calculation ($days, $balls, $conn, $frequency);

    //Filter 14
    abstract protected function lastRange ($days, $balls, $conn, $frequency);
    
    //Final
    //Filter 15
    abstract protected function finalNumbers ($days, $balls, $conn, $frequency);
}
?>