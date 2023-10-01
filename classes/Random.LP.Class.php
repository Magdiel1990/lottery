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

    
    //Verificar si esta jugada ya había salido
    protected function lastNumbersExceptions($arrayNumbers = null) {
        $totalNumbers = $this-> totalNumbers();

        sort($arrayNumbers);
         
        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                return $arrayNumbers = [];
            }
        }      
        return $arrayNumbers;
    }

    //Incluir rango de sumas
    protected function sumRange($arrayNumbers = null) {
        //Suma de los elementos del array
        $sumArray = $this -> sumArray ($arrayNumbers);
        //Array del máximo y mínimo
        $rangeSumArray = $this -> minMaxArray($this -> sumsArrayNumbers());

        return $this -> rangeCondition ($sumArray, $rangeSumArray, $arrayNumbers);
    }
    //Rango de los promedios
    protected function rangeAvgArray ($arrayNumbers = null) {
        if(count($arrayNumbers) != 0) {
            //Array del máximo y mínimo
            $rangeAvg = $this -> rangeAvg();
            //Array average
            $average = $this -> average($arrayNumbers);

            return $this -> rangeCondition ($average, $rangeAvg, $arrayNumbers);
        } else {
            return $arrayNumbers;
        }
    }
    //Excluir doble consecutivos
    protected function consecutiveOutArray ($arrayNumbers = null){
        $count = 0;

        for($i = 1; $i < count($arrayNumbers); $i++) {
            if($arrayNumbers[$i] - $arrayNumbers[$i - 1] == 1) {
                $count += 1;
            }            
        }

        if($count <= 1) {
            return $arrayNumbers;
        } else {
            return [];
        }        
    }


    public function randomNumbers() {
        $arrayNumbers = $this-> arrayNumbers();
        $arrayNumbers = $this-> normalNumbers($arrayNumbers, 5);
        $arrayNumbers = $this-> lastNumbersExceptions($arrayNumbers);
        $arrayNumbers = $this-> sumRange($arrayNumbers);
        $arrayNumbers = $this-> consecutiveOutArray ($arrayNumbers);
        
        return $arrayNumbers;
    }
}


/***************************Methods out *********************/
//rangeStandardDeviation, subRange, rangeAvgArray, rangeProArray
?>