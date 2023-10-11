<?php
require_once ("Lottery.Class.php");

class LotoClass extends LotteryClass {
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