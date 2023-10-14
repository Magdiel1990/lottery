<?php
require_once ("Lottery.Class.php");

class KTVClass extends LotteryClass {
    //Patrón de restas
    protected function diffRangeLoop($array, $conn) {
        return $array;
    }

    //Patrón de restas
    protected function sumEachLoop($array, $conn) {        
        return $array;
    }

    //Filter 6
    protected function subRange($days, $balls, $conn) {            
        $array = $this -> sumRange($days, $balls, $conn);

        return $array;
    }
    
    protected function consecutiveOutArray ($days, $balls, $conn){
        return $this -> rangeProArray ($days, $balls, $conn);    
    }

    //Filter 10
    protected function sumEach($days, $balls, $conn){
        $array = $this -> consecutiveOutArray($days, $balls, $conn);
   
        return $array;
    }

    protected function insersectArrayOut ($days, $balls, $conn, $frequency) {
        $array = $this -> sumEach($days, $balls, $conn);
        $totalPlays = $this -> totalPlays($conn);

        sort($array);

        $array = $this -> intersectCompare($array, 19, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 18, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 17, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 16, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 15, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 14, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 13, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 12, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 11, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 10, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        
        return $array;
    }  

    protected function lastRange($days, $balls, $conn, $frequency) {
        $array = $this -> number_period_filter ($days, $balls, $conn, $frequency);

        $array = $this -> range_filter($array, 1, 5);
        $array = $this -> range_filter($array, 2, 10);
        $array = $this -> range_filter($array, 3, 15);
        $array = $this -> range_filter($array, 4, 20);
        $array = $this -> range_filter($array, 5, 25);
        $array = $this -> range_filter($array, 6, 30);
        $array = $this -> range_filter($array, 7, 35);
        $array = $this -> range_filter($array, 8, 40);
        $array = $this -> range_filter($array, 9, 45);
        $array = $this -> range_filter($array, 10, 50);
        $array = $this -> range_filter($array, 11, 55);
        $array = $this -> range_filter($array, 12, 60);
        $array = $this -> range_filter($array, 13, 60);
        $array = $this -> range_filter($array, 14, 60);
        $array = $this -> range_filter($array, 15, 65);
        $array = $this -> range_filter($array, 16, 70);
        $array = $this -> range_filter($array, 17, 75);
        $array = $this -> range_filter($array, 18, 75);
        $array = $this -> range_filter($array, 19, 80);
        $array = $this -> range_filter($array, 20, 80);
        return $array;
    }

    private function tenNumbersCreator($days, $balls, $conn, $frequency) {
        $array = $this -> lastRange($days, $balls, $conn, $frequency);
        //Alearoriamente organizamos array
        shuffle($array);
        //Lo divido en pedazos de 10
        $array = array_chunk ($array, 10);
        //Retorno el primer elemento de ese arreglo
        if(count($array) == 0) {
            return [];
        }
        return $array[0];
    }

//Final
    public function finalNumbers ($days, $balls, $conn, $frequency) {
        $array = $this -> tenNumbersCreator($days, $balls, $conn, $frequency);
        sort($array);
        return $array;
    }
}
?>