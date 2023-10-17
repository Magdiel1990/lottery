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

 //Final
    public function finalNumbers ($days, $balls, $conn) {
        $array = $this -> decenas ($days, $balls, $conn);
        sort($array);
        return $array;
    }
}

?>