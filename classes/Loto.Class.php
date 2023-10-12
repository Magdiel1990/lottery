<?php
require_once ("Lottery.Class.php");

class LotoClass extends LotteryClass {
    protected function insersectArrayOut ($amount, $up, $balls, $conn, $frequency) {
        $array = $this -> randOutArray($amount, $balls, $up, $conn);
        $totalPlays = $this -> totalPlays($conn);

        sort($array);

        $array = $this -> intersectCompare($array, 5, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 4, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 3, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        
        return $array;
    }  
    
    //Filter 13
    protected function number_period_filter ($amount, $up, $balls, $conn, $frequency) {        
        $array = $this -> insersectArrayOut ($amount, $up, $balls, $conn, $frequency);  

        return $array;
    }
    protected function lastRange($amount, $balls, $up, $conn) {
        $array = $this -> number_period_filter ($amount, $up, $balls, $conn, 0.02);

        for($i = 1; $i <= $balls - 3; $i++) {
            $array = $this -> range_filter($array, $i, $i*10);
        }

        $array = $this -> range_filter($array, 4, 30);
        $array = $this -> range_filter($array, 5, 36);

        return $array;
    }
//Final
    public function finalNumbers ($balls, $up, $conn) {
        $array = $this -> lastRange(500000, $balls, $up, $conn);
        sort($array);
        return $array;
    }
}

?>