<?php
require ("LP.Class.php");

class LotoClass extends RangeNumbers {

/************************************* Cálculo del ************************************/
/*************************************   rango     ************************************/

    //Maximo numero en cualquier posicion
    protected function maxNumberRange($position) {
        $conn = DatabaseClassLoto::dbConnection();
        
        $result = $conn -> query("SELECT max(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Mínimo numero en cualquier posicion
    protected function minNumberRange($position) {
        $conn = DatabaseClassLoto::dbConnection();
        
        $result = $conn -> query("SELECT min(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }


/*************************************   Generando  ************************************/
/*************************************    números   ************************************/

     //Números aleatorios
    protected function arrayNumbers($arrayNumbers = null) {
        $arrayNumbers = [$this-> numberRange(1), $this-> numberRange(2), $this-> numberRange(3), $this-> numberRange(4), $this-> numberRange(5), $this-> numberRange(6)]; 
        
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        sort($arrayNumbers);

        return $arrayNumbers;
    }
 

    //Descarte de los números que menos salen
    protected function rareNumbersOut($arrayNumbers = null, $amount) {
        $conn = DatabaseClassLoto::dbConnection();  
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

    //Incluye números de sorteos anteriores
    protected function repeatedNumbers($arrayNumbers = null, $time) {        
        $conn = DatabaseClassLoto::dbConnection();     
        $arrayNumbers = $this-> rareNumbersOut (null, 4);

        $max = $time * 6; 

        //Ultimos numeros
        $result = $conn -> query ("SELECT number FROM numbers LIMIT 6 OFFSET $max;");

        $numbers = [];
        
        while($row = $result -> fetch_assoc()){
            $numbers [] = intval($row["number"]);
        }        
   
        while(count($arrayNumbers) != 6) {
            array_push($arrayNumbers, $numbers[rand(0,5)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }


/*************************************    Arreglos de  ************************************/
/************************************* todas las jugadas **********************************/


    //Generador de random
    protected function randomGenerator($amount) {
        $randomArraysOfTheDay = [];

        while(count($randomArraysOfTheDay) < $amount) {
            $generatedRandomArray = [];
            while(count($generatedRandomArray)< 6) {
                $generatedRandomArray [] = rand(1,38);
                $generatedRandomArray = array_unique($generatedRandomArray, SORT_NUMERIC);
            }

            sort($generatedRandomArray);
            
            $randomArraysOfTheDay [] = $generatedRandomArray;
        }

       return $randomArraysOfTheDay;
    }

    
    public function finalNumbers () {
        $totalNumbers = $this -> randomOfTheDayException();
        return $totalNumbers;
    }
}

?>