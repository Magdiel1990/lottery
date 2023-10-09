<?php
require_once ("Lottery.Class.php");

class LPClass extends LotteryClass {  
//Final
    public function finalNumbers ($balls, $up, $conn) {
        $array = $this -> randOutArray(150000, $balls, $up, $conn);
        sort($array);
        return $array;
    }
}
?>