<?php
require_once ("Lottery.Class.php");

class LotoClass extends LotteryClass {
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

    protected function insersectArrayOut ($days, $balls, $conn, $frequency) {
        $array = $this -> sumEach($days, $balls, $conn);
        $totalPlays = $this -> totalPlays($conn);

        sort($array);

        $array = $this -> intersectCompare($array, 5, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 4, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
               
        return $array;
    }  
    
    //Filter 13
    protected function number_period_filter ($days, $balls, $conn, $frequency) {        
        $array = $this -> insersectArrayOut ($days, $balls, $conn, $frequency);  

        return $array;
    }

    protected function combination_calculation ($days, $balls, $conn, $frequency) {
        $array = $this -> number_period_filter ($days, $balls, $conn, $frequency);

        for($i = 0; $i < count($array) - 1; $i++) {
            for($j = $i + 1; $j < $balls; $j++) {
                if($this -> combination_percentage ($array[$i], $array[$j], 10, $conn) < 2) {
                    return ["ok"];
                }
            }
        }

        return $array;
    }

    protected function lastRange ($days, $balls, $conn, $frequency) {
        $array = $this -> combination_calculation ($days, $balls, $conn, $frequency);
        return $array;
    }
//Final
    public function finalNumbers ($days, $balls, $conn, $frequency) {
        $array = $this -> lastRange($days, $balls, $conn, $frequency);
        sort($array);
        return $array;
    }
}

?>