<?php
require_once ("Lottery.Class.php");

class LotoClass extends LotteryClass {
//Final
    public function finalNumbers ($balls, $up, $conn) {
        $array = $this -> randOutArray(1000000, $balls, $up, $conn);
        sort($array);
        return $array;
    }
}

?>