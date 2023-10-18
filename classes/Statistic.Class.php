<?php
require_once ("../classes/Lottery.Class.php");

class Statistic extends LotteryClass {
    protected function numberRange($position, $conn) {
        $maxNumber = $this-> maxNumberRange($position, $conn);
        $minNumber = $this-> minNumberRange($position, $conn);

        return [$minNumber, $maxNumber];
    }

    public function statNumbersRanges ($balls, $conn) {
        $ranges = [];
        for ($i = 1; $i <= $balls; $i++) {
            $ranges [] = $this -> numberRange($i, $conn);
        }
        return $ranges;
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