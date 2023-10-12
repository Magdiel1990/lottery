<?php
require_once ("Lottery.Class.php");

class KTVClass extends LotteryClass {
    //Filter 6
    protected function subRange($balls, $conn) {            
        $array = $this -> sumRange($balls, $conn);

     //   $array = $this -> diffRangeLoop ($array, $balls, $conn);

        return $array;
    }
    
    protected function consecutiveOutArray ($balls, $conn){
        return $this -> rangeProArray ($balls, $conn);    
    }

    //Filter 10
    protected function sumEach($balls, $conn) {
        $array = $this -> consecutiveOutArray($balls, $conn);
        
       // $array = $this -> sumEachLoop($array, $balls, $conn);
   
        return $array;
    }

    protected function insersectArrayOut ($amount, $up, $balls, $conn, $frequency) {
        $array = $this -> randOutArray($amount, $balls, $up, $conn);
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
        $array = $this -> intersectCompare($array, 9, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 8, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        
        return $array;
    }  

    protected function lastRange($amount, $balls, $up, $conn) {
        $array = $this -> number_period_filter ($amount, $up, $balls, $conn, 0.02);
        
        for($i = 1; $i<=6; $i++) {
            $array = $this -> range_filter($array, $i, 5*$i);
        }
        
        $array = $this -> range_filter($array, 7, 30);
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

    private function tenNumbersCreator($balls, $up, $conn) {
        $array = $this -> lastRange(200000, $balls, $up, $conn);
        //Alearoriamente organizamos array
        shuffle($array);
        //Lo divido en pedazos de 10
        $array = array_chunk ($array, 10);
        //Retorno el primer elemento de ese arreglo
        return $array[0];
    }

//Final
    public function finalNumbers ($balls, $up, $conn) {
        $array = $this -> tenNumbersCreator($balls, $up, $conn);
        sort($array);
        return $array;
    }
}
?>