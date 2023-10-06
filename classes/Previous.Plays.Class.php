<?php
require_once ("LP.Class.php");

class PreviousPlaysClass {
    private int $time = 1;
    private $allArray;
    private int $positions = 3;
    private $array;
    private int $frequency = 5;
    private int $balls;
    private int $count;

    private function intersectArrays ($allArrays, $time) {
        $intersectionsArrays = [];
        for($i = 0; $i < count($allArrays) - $time; $i++) {
            $intersectionsArrays [] = array_intersect($allArrays[$i], $allArrays[$i + $time]);
        } 
        
        return $intersectionsArrays;
    }

    private function intersectArraysBets ($time) {
        $allArrays = new RangeNumbers ();
        $allArrays = $allArrays ->  totalNumbers(5);

        $intersectionsArrays = $this -> intersectArrays ($allArrays, $time);

        return $intersectionsArrays;
    }

    private function frequencyCalculation ($positions, $time){
        $intersectArrays = $this -> intersectArraysBets ($time);

        $repeat = 0;

        for($i = 0; $i < count($intersectArrays); $i++) {
            if(count($intersectArrays[$i]) == $positions) {
                $repeat += 1;
            }
        } 

        return $repeat;
    }

    private function intersection ($array, $time, $allArrays) {
        $intersection = array_intersect($allArrays [$time - 1], $array);

        return $intersection;
    }

    private function intersectCondition ($array, $positions, $time, $frequency) {
        //frequency: cantidad máxima de apariciones aceptadas de la repetición de esa secuencia.
        //array: Jugada a ser examinada.
        //position: cantidad de secuencias a tomar en cuenta. Si son 5 bolos puede ser 1,2,3,4 o 5.
        //time: cantidad de días anteriores a la jugada para ser comparados.
        $allArrays = new RangeNumbers ();
        $allArrays = $allArrays ->  totalNumbers(5);

        if(count($array) == 0) {
            return $array;
        }

        $frequencyCalculation = $this -> frequencyCalculation ($positions, $time);

        $intersection = $this -> intersection ($array, $time, $allArrays);
   
        if($frequencyCalculation <= $frequency && count($intersection) == $positions) {
            return $array;
        } else if (count($intersection) < $positions) {
            return $array;
        } else {
            return [];
        }      
    }

    private function intersectCompare($array, $positions, $balls, $frequency, $count) {

        for($i = 1; $i <= $count; $i++) {
            $array = $this -> intersectCondition ($array, $positions, $i, $frequency);
            if(count($array) == 0) {
                break;
            }
        }        
        return $array;
    }

    public function insersectArrayOut ($balls) {
        $array = new RangeNumbers();
        $array = $array -> lastNumbersExceptions();

        sort($array);

        $array = $this -> intersectCompare($array, 4, $balls, 5, 50);
        $array = $this -> intersectCompare($array, 3, $balls, 5, 50);

        return $array;
    }
}
?>