<?php
require_once ("Lottery.Class.php");

class LPClass extends LotteryClass {  
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

    protected function decenas_calculation ($array, $days, $balls, $conn) {

        if(count($array) != 0) {
      
            $decena = [];

            for($i = 0; $i < count($array); $i++) {
                if($array[$i] > 0 && $array[$i] < 10) {
                    $decena [] = "first";
                } else if ($array[$i] >= 10 && $array[$i] < 20) {
                    $decena [] = "second";
                } else if($array[$i] >= 20 && $array[$i] < 30) {
                    $decena [] = "third";
                } else {
                    $decena [] = "fourth";
                }
            }       
        } else {
            $decena = [];
        }

        return $decena;
    }

    protected function decenas ($days, $balls, $conn) {
        $array = $this -> sumEach($days, $balls, $conn);
        $decena = $this -> decenas_calculation ($array, $days, $balls, $conn);

        $decena = array_unique ($decena);

        if(count($decena) < 3) {
            return [];
        } else {
            return $array;
        }
    }

//Final
    public function finalNumbers ($days, $balls, $conn) {
        $array = $this -> decenas($days, $balls, $conn);
        sort($array);
        return $array;
    }
}
?>