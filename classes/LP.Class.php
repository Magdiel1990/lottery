<?php
require_once ("Lottery.Class.php");

class LPClass extends LotteryClass {  
    protected function lastRange($amount, $balls, $up, $conn) {
        $array = $this -> insersectArrayOut ($amount, $up, $balls, $conn, 0.02);
        for($i = 1; $i <= $balls - 3; $i++) {
            $array = $this -> range_filter($array, $i, $i*10);
        }
        $array = $this -> range_filter($array, 3, 25);
        $array = $this -> range_filter($array, 4, 30);
        return $array;
    }
//Final
    public function finalNumbers ($balls, $up, $conn) {
        $array = $this -> lastRange(150000, $balls, $up, $conn);
        sort($array);
        return $array;
    }
}
?>