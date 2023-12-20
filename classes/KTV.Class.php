<?php
require_once ("Lottery.Class.php");

class KTVClass extends LotteryClass {
//Final
    public function finalNumbers ($days, $balls, $conn, $pastGames) {
        $array = $this -> pastDaysAccount ($days, $balls, $conn, $pastGames); 
        sort($array);
        return $array;
    }
}
?>