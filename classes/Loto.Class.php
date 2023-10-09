<?php
require ("LP.Class.php");

class LotoClass extends RangeNumbers {
    protected int $frequency = 6;

    //Patrón de restas
    protected function subRange($balls) {
        $array = $this -> diffRange(null, 1, 2, $balls);
        $array = $this -> diffRange($array, 1, 3, $balls);
        $array = $this -> diffRange($array, 1, 4, $balls);
        $array = $this -> diffRange($array, 1, 5, $balls);
        $array = $this -> diffRange($array, 2, 3, $balls);
        $array = $this -> diffRange($array, 2, 4, $balls);
        $array = $this -> diffRange($array, 2, 5, $balls);
        $array = $this -> diffRange($array, 3, 4, $balls);
        $array = $this -> diffRange($array, 3, 5, $balls);
        $array = $this -> diffRange($array, 4, 5, $balls);
        $array = $this -> diffRange($array, 4, 6, $balls);
        $array = $this -> diffRange($array, 5, 6, $balls);

        return $array;
    }
    
    protected function sumEach($balls) {
        $array = $this -> consecutiveOutArray(null, $balls);
        $array = $this -> rangeSumEach($array, 1, 2);
        $array = $this -> rangeSumEach($array, 1, 3);
        $array = $this -> rangeSumEach($array, 1, 4);
        $array = $this -> rangeSumEach($array, 1, 5);
        $array = $this -> rangeSumEach($array, 2, 3);
        $array = $this -> rangeSumEach($array, 2, 4);
        $array = $this -> rangeSumEach($array, 2, 5);
        $array = $this -> rangeSumEach($array, 3, 4);
        $array = $this -> rangeSumEach($array, 3, 5);
        $array = $this -> rangeSumEach($array, 4, 5);
        $array = $this -> rangeSumEach($array, 4, 6);
        $array = $this -> rangeSumEach($array, 5, 6);

        return $array;
    }

    public function finalNumbers ($balls, $up) {
        $array = $this -> randOutArray(80000, $balls, $up);
        sort($array);
        return $array;
    }
}

?>