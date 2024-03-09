<?php
require_once ("Lottery.Class.php");

class LPClass extends LotteryClass { 
// Calculo de pares e impares   
  /*  protected function oddEvenArray ($days, $balls, $conn, $pastGames) {
        $array = $this -> pastDaysAccount ($days, $balls, $conn, $pastGames);
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
*/
//Final
    public function finalNumbers ($days, $balls, $conn) {
        $array = $this -> consecutiveOutArray ($days, $balls, $conn);
        sort($array);
        return $array;
    }
}
?>