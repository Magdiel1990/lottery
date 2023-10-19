<?php
require_once ("../classes/Lottery.Class.php");

class Statistic extends LotteryClass {
    protected function numberRange($position, $conn) {
        $maxNumber = $this-> maxNumberRange($position, $conn);
        $minNumber = $this-> minNumberRange($position, $conn);

        return [$minNumber, $maxNumber];
    }
    
    //Rango real de las jugadas
    public function statNumbersRanges ($balls, $conn) {
        $ranges = [];
        for ($i = 1; $i <= $balls; $i++) {
            $ranges [] = $this -> numberRange($i, $conn);
        }
        return $ranges;
    }
    //Repetición un día de otro
    public function statDailyRep ($balls, $conn, $days) {
        $totalNumbers = $this -> totalNumbers($balls, $conn);

        $count = 0;

        for($i = 0; $i < count($totalNumbers) - $days; $i++) {
            for ($j = 0; $j < count($totalNumbers[$i]); $j++) {
                if(in_array($totalNumbers[$i][$j], $totalNumbers[$i + $days])) {
                    $count += 1;
                    break;
                }
            }
        }
        $percentage = round (($count * 100 / count($totalNumbers)));

        return $percentage; 
    }

    public function normalNumbers($days, $balls, $conn) {
        $keys = $this-> lastNumbersMoreOftenPlayed ($days, $balls, $conn);
        $keys = array_chunk ($keys, $balls);
        $keys = $keys[0];

        return $keys;
    }

    /***************Abstract methods *****************/
    protected function diffRangeLoop($array, $conn) {
        return null;
    }
    protected function sumEachLoop($array, $conn) {
        return null;
    }
    protected function finalNumbers ($days, $balls, $conn) {
        return null;
    }
}
?>