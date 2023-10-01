<?php
require ("LP.Class.php");

class RangeNumbersChild extends RangeNumbers {

    /*************************************   Generando  ************************************/
    /*************************************    números   ************************************/

    protected function normalNumbers($arrayNumbers = null, $amount) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

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

    public function randomNumbers() {
        $arrayNumbers = $this-> arrayNumbers();
        $arrayNumbers = $this-> normalNumbers($arrayNumbers, 5);

        return $arrayNumbers;
    }
}
?>