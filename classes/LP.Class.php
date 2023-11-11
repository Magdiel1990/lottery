<?php
require_once ("Lottery.Class.php");

class LPClass extends LotteryClass { 
// Calculo de pares e impares   
    protected function oddEvenArray ($days, $balls, $conn) {
        $array = $this -> pastDaysAccount ($days, $balls, $conn);
        $even = $this -> oddEvenCal($days, $array, $balls, $conn);

        if($even == false) {
            return [];
        }

        if($even == 1 || $even == 2 || $even == 3) {
            return $array;
        } else {
            return [];
        }
    }

//Final
    public function finalNumbers ($days, $balls, $conn) {
        $array = $this -> oddEvenArray ($days, $balls, $conn);;
        sort($array);
        return $array;
    }
}
?>