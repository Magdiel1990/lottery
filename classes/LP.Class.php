<?php
require_once ("Lottery.Class.php");

class LPClass extends LotteryClass { 
    protected function numberRange($position, $conn) {
        switch ($position) {
            case 1:
                return rand(1,9);
                break;
            case 2: 
                return rand(6,16);
                break;
            case 3:
                return rand(10,23);
                break;
            case 4:
                return rand(17,27);
                break;
            default:
                return rand(24,31);                
        }
    } 
    
    protected function rangeAvg($balls, $conn) {
        return [12,21];
    }

    protected function sumRange($days, $balls, $conn) {
        //Array     
        $array = $this -> lastNumbersExceptions($days, $balls, $conn);

        if(count($array) == 0) {
            return [];
        }

        //Suma de los elementos del array
        $sumArray = $this -> sumArray ($array);
        //Array del máximo y mínimo
        $rangeSumArray = [64, 101];

        return $this -> rangeCondition ($sumArray, $rangeSumArray, $array);
    }

    //Patrón de restas
    protected function diffRangeLoop($array, $conn) {
        $array = $this -> diffRange($array, 1, 2, $conn);
        $array = $this -> diffRange($array, 1, 3, $conn);
        $array = $this -> diffRange($array, 1, 4, $conn);
        $array = $this -> diffRange($array, 1, 5, $conn);
        $array = $this -> diffRange($array, 2, 3, $conn);
        $array = $this -> diffRange($array, 2, 4, $conn);
        $array = $this -> diffRange($array, 2, 5, $conn);
        $array = $this -> diffRange($array, 3, 4, $conn);
        $array = $this -> diffRange($array, 3, 5, $conn);
        $array = $this -> diffRange($array, 4, 5, $conn);
  
        return $array;
    }

   
    //Patrón de restas
    protected function sumEachLoop($array, $conn) {        
        $array = $this -> rangeSumEach($array, 1, 2, $conn);
        $array = $this -> rangeSumEach($array, 1, 3, $conn);
        $array = $this -> rangeSumEach($array, 1, 4, $conn);
        $array = $this -> rangeSumEach($array, 1, 5, $conn);
        $array = $this -> rangeSumEach($array, 2, 3, $conn);
        $array = $this -> rangeSumEach($array, 2, 4, $conn);
        $array = $this -> rangeSumEach($array, 2, 5, $conn);
        $array = $this -> rangeSumEach($array, 3, 4, $conn);
        $array = $this -> rangeSumEach($array, 3, 5, $conn);
        $array = $this -> rangeSumEach($array, 4, 5, $conn);

        return $array;
    }

//Final
    public function finalNumbers ($days, $balls, $conn) {
        $array = $this -> pastDaysAccount ($days, $balls, $conn);
        sort($array);
        return $array;
    }
}
?>