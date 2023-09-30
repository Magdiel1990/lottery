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
    
    protected function normalNumbers($arrayNumbers = null, $amount) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        $result = $conn -> query ("SELECT number, count(*) as total FROM numbers GROUP BY number ORDER BY total desc LIMIT $amount;");

        $numbers = [];
        
        while($row = $result -> fetch_assoc()){
            $numbers [] = intval($row["number"]);
        }        
   
        while(count($arrayNumbers) != $numbers) {
            array_push($arrayNumbers, $numbers[rand(0, $amount - 1)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }

    public function randomNumbers() {
        $arrayNumbers = $this-> arrayNumbers();
        $arrayNumbers = $this-> rareNumbersOut($arrayNumbers, 1);
        $arrayNumbers = $this-> normalNumbers($arrayNumbers, 6);

        return $arrayNumbers;
    }
}
?>