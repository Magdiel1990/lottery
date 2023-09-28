<?php
require ("LP.Class.php");

class RangeNumbersChild extends RangeNumbers {

    /*************************************   Generando  ************************************/
    /*************************************    números   ************************************/

    protected function rareNumbersOut ($arrayNumbers = null, $amount) {
        $conn = DatabaseClass::dbConnection();  
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);   
       
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
    
    protected function repeatedNumbers($arrayNumbers = null, $time) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        $max = $time * 5; 

        //Ultimos numeros
        $result = $conn -> query ("SELECT number FROM numbers LIMIT 5 OFFSET $max;");

        $numbers = [];
        
        while($row = $result -> fetch_assoc()){
            $numbers [] = intval($row["number"]);
        }        
   
        while(count($arrayNumbers) < 5) {
            array_push($arrayNumbers, $numbers[rand(0,4)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }

    public function randomNumbers() {
        $arrayNumbers = $this-> arrayNumbers();
        $arrayNumbers = $this-> rareNumbersOut($arrayNumbers, 2);
        $arrayNumbers = $this-> repeatedNumbers($arrayNumbers, 3);

        return $arrayNumbers;
    }
}
?>