<?php
require_once ("Lottery.Class.php");

class LotoClass extends LotteryClass {
//Final
    private function range_filter($array, $position, $up) {   
        if(count($array) == 0) {
            return [];
        }

        if($array [$position - 1] < $up) {
            return $array;
        } else {
            return [];
        }
    }

    protected function lastRange($amount, $balls, $up, $conn) {
        $array = $this -> randOutArray($amount, $balls, $up, $conn);
        
        for($i = 1; $i <= $balls - 3; $i++) {
            $array = $this -> range_filter($array, $i, $i*10);
        }

        $array = $this -> range_filter($array, 4, 30);
        $array = $this -> range_filter($array, 5, 36);

        return $array;
    }

    public function finalNumbers ($balls, $up, $conn) {
        $array = $this -> lastRange(1000000, $balls, $up, $conn);
        sort($array);
        return $array;
    }
}

?>