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


    //Números aleatorios
    protected function arrayNumbers($arrayNumbers = null) {
        $arrayNumbers = [$this-> numberRange(1), $this-> numberRange(2), $this-> numberRange(3), $this-> numberRange(4), $this-> numberRange(5)]; 
        
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        sort($arrayNumbers);

        return $arrayNumbers;
    }
    
    //Descarte del día
    protected function dayOut() {
        date_default_timezone_set ("America/Santo_Domingo");
        $today = date("j");

        $arrayNumbers = $this-> arrayNumbers();

        for($i = 0; $i < count($arrayNumbers); $i++) {
            if($arrayNumbers[$i] == $today) {
                unset($arrayNumbers[$i]);
                break;
            }
        }

        sort($arrayNumbers);

        return $arrayNumbers;
    }

    //Descarte de los números que menos salen
    protected function rareNumbersOut($arrayNumbers = null, $amount) {
        $conn = DatabaseClass::dbConnection();  
        $arrayNumbers = $this-> dayOut();   
       
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

    //Incluye números de sorteos anteriores
    protected function repeatedNumbers($arrayNumbers = null, $time) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = $this-> rareNumbersOut (null, 2);

        $max = $time * 5; 

        //Ultimos numeros
        $result = $conn -> query ("SELECT number FROM numbers LIMIT 5 OFFSET $max;");

        $numbers = [];
        
        while($row = $result -> fetch_assoc()){
            $numbers [] = intval($row["number"]);
        }        
   
        while(count($arrayNumbers) != 5) {
            array_push($arrayNumbers, $numbers[rand(0,4)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }


    /*************************************    Arreglos de  ************************************/
    /************************************* todas las jugadas **********************************/

    //Arreglos de todas las jugadas pasadas
    protected function positionCal($position) {
        $conn = DatabaseClass::dbConnection();  
        $result = $conn -> query ("SELECT number FROM numbers WHERE position = '$position' ORDER BY date;");

        $positionArray = [];

        while($row = $result -> fetch_assoc()) {
            $positionArray[] = intval($row["number"]);
        }

        return $positionArray;
    }
    
    //Arreglo de los arreglos de todas las jugadas pasadas
    protected function totalNumbers(){
        $positionArray1 = $this-> positionCal(1);
        $positionArray2 = $this-> positionCal(2);
        $positionArray3 = $this-> positionCal(3);
        $positionArray4 = $this-> positionCal(4);
        $positionArray5 = $this-> positionCal(5);

        $totalPosition = [];

        for($i = 0; $i < count($positionArray1); $i++) {
            $totalPosition[$i] = [$positionArray1[$i], $positionArray2[$i], $positionArray3[$i], $positionArray4[$i], $positionArray5[$i]];
        }        

        return $totalPosition;
    }

    //Verificar si esta jugada ya había salido
    protected function lastNumbersExceptions() {
        $totalNumbers = $this-> totalNumbers();
        $arrayNumbers = $this-> repeatedNumbers(null, 3);

        sort($arrayNumbers);
         
        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                return $arrayNumbers = [];
            }
        }      
        return $arrayNumbers;
    }

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

    //Excluir los número aleatorios
    protected function randomOfTheDayException() {
       $arrayNumbers = $this-> lastNumbersExceptions();

       $randomArraysOfTheDay = $this-> randomGenerator(100);

       $arrayNumbers = $this-> randomNumbersExceptions($randomArraysOfTheDay, $arrayNumbers);
       
       return $arrayNumbers;
    }

    public function finalNumbers () {
        $totalNumbers = $this -> randomOfTheDayException();
        return $totalNumbers;
    }
}
?>