<?php
require_once ("Lottery.Class.php");

class LotoClass extends LotteryClass {
    protected function numberRange($position, $conn) {
        switch ($position) {
            case 1:
                return rand(1,5);
                break;
            case 2: 
                return rand(4,12);
                break;
            case 3:
                return rand(7,17);
                break;
            case 4:
                return rand(19,31);
                break;
            case 5:
                return rand(22,35);
                break;
            default:
                return rand(28,38);                
        }
    } 

    protected function rangeAvg($balls, $conn) {
        return [16,25];
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
        $rangeSumArray = [86, 149];

        return $this -> rangeCondition ($sumArray, $rangeSumArray, $array);
    }

    protected function rangeStandardDeviation($days, $balls, $conn) {
        //Desviación standard de la jugada
        $array = $this-> normalNumbers($days, $balls, $conn);
        $standardDeviationOfArray =  $this -> standardDeviation ($array);

        //Máximo y mínimo de las deviaciónes estándares
        $rangeDev = [5, 13];

        return $this -> rangeCondition ($standardDeviationOfArray, $rangeDev, $array);
    }

    protected function rangeProArray ($days, $balls, $conn) {
        $array = $this -> rangeAvgArray ($days, $balls, $conn);    
        
        if(count($array) == 0) {
            return $array;
        }

        //Array del máximo y mínimo
        $rangePro = [1000000, 200000000];
        //Array average
        $product = $this -> product($array);

        return $this -> rangeCondition ($product, $rangePro, $array);
    }

    //Patrón de restas
    protected function diffRangeLoop($array, $conn) {
        $array = $this -> diffRange($array, 1, 2, $conn);
        $array = $this -> diffRange($array, 1, 3, $conn);
        $array = $this -> diffRange($array, 1, 4, $conn);
        $array = $this -> diffRange($array, 1, 5, $conn);
        $array = $this -> diffRange($array, 1, 6, $conn);
        $array = $this -> diffRange($array, 2, 3, $conn);
        $array = $this -> diffRange($array, 2, 4, $conn);
        $array = $this -> diffRange($array, 2, 5, $conn);
        $array = $this -> diffRange($array, 2, 6, $conn);
        $array = $this -> diffRange($array, 3, 4, $conn);
        $array = $this -> diffRange($array, 3, 5, $conn);
        $array = $this -> diffRange($array, 3, 6, $conn);
        $array = $this -> diffRange($array, 4, 5, $conn);
        $array = $this -> diffRange($array, 4, 6, $conn);
        $array = $this -> diffRange($array, 5, 6, $conn);
  
        return $array;
    }

    //Patrón de restas
    protected function sumEachLoop($array, $conn) {        
        $array = $this -> rangeSumEach($array, 1, 2, $conn);
        $array = $this -> rangeSumEach($array, 1, 3, $conn);
        $array = $this -> rangeSumEach($array, 1, 4, $conn);
        $array = $this -> rangeSumEach($array, 1, 5, $conn);
        $array = $this -> rangeSumEach($array, 1, 6, $conn);
        $array = $this -> rangeSumEach($array, 2, 3, $conn);
        $array = $this -> rangeSumEach($array, 2, 4, $conn);
        $array = $this -> rangeSumEach($array, 2, 5, $conn);
        $array = $this -> rangeSumEach($array, 2, 6, $conn);
        $array = $this -> rangeSumEach($array, 3, 4, $conn);
        $array = $this -> rangeSumEach($array, 3, 5, $conn);
        $array = $this -> rangeSumEach($array, 3, 6, $conn);
        $array = $this -> rangeSumEach($array, 4, 5, $conn);
        $array = $this -> rangeSumEach($array, 4, 6, $conn);
        $array = $this -> rangeSumEach($array, 5, 6, $conn);

        return $array;
    }

// Calculo de pares e impares   
    protected function oddEvenArray ($days, $balls, $conn) {
        $array = $this -> pastDaysAccount ($days, $balls, $conn);
        $even = $this -> oddEvenCal($days, $array, $balls, $conn);

        if($even == false) {
            return [];
        }

        if($even == 2 || $even == 3 || $even == 4) {
            return $array;
        } else {
            return [];
        }
    }

 //Final
    public function finalNumbers ($days, $balls, $conn) {
        $array = $this -> oddEvenArray ($days, $balls, $conn);
        sort($array);
        return $array;
    }
}

?>