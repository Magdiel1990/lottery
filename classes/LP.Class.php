<?php
require_once ("Lottery.Class.php");

class LPClass extends LotteryClass {
  
    //Patrón de restas
    protected function subRange($balls, $conn) {
        $array = $this -> diffRange(null, 1, 2, $balls, $conn);        
        $array = $this -> diffRange($array, 1, 3, $balls, $conn);
        $array = $this -> diffRange($array, 1, 4, $balls, $conn);
        $array = $this -> diffRange($array, 1, 5, $balls, $conn);
        $array = $this -> diffRange($array, 2, 3, $balls, $conn);
        $array = $this -> diffRange($array, 2, 4, $balls, $conn);
        $array = $this -> diffRange($array, 2, 5, $balls, $conn);
        $array = $this -> diffRange($array, 3, 4, $balls, $conn);
        $array = $this -> diffRange($array, 3, 5, $balls, $conn);
        $array = $this -> diffRange($array, 4, 5, $balls, $conn);

        return $array;
    }

    protected function sumEach($balls, $conn) {
        $array = $this -> consecutiveOutArray(null, $balls, $conn);
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
    public function finalNumbers ($balls, $up, $conn) {
        $array = $this -> randOutArray(80000, $balls, $up, $conn);
        sort($array);
        return $array;
    }
}
?>