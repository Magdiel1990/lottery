<?php
require_once ("Lottery.Class.php");

class KTVClass extends LotteryClass {
    //Patrón de restas
    protected function diffRangeLoop($array, $conn) {
        return $array;
    }

    //Patrón de restas
    protected function sumEachLoop($array, $conn) {        
        return $array;
    }

    //Filter 6
    protected function subRange($days, $balls, $conn) {            
        $array = $this -> sumRange($days, $balls, $conn);

        return $array;
    }
    
    protected function consecutiveOutArray ($days, $balls, $conn){
        return $this -> rangeProArray ($days, $balls, $conn);    
    }

    //Filter 10
    protected function sumEach($days, $balls, $conn){
        $array = $this -> consecutiveOutArray($days, $balls, $conn);
   
        return $array;
    }

    protected function insersectArrayOut ($days, $balls, $conn, $frequency) {
        $array = $this -> sumEach($days, $balls, $conn);
        return $array;
    }  

    protected function combination_calculation ($days, $balls, $conn, $frequency) {
        $array = $this -> number_period_filter ($days, $balls, $conn, $frequency);

        for($i = 0; $i < count($array) - 1; $i++) {
            for($j = $i + 1; $j < $balls; $j++) {
                if($this -> combination_percentage ($array[$i], $array[$j], 10, $conn) < 5) {
                    return [];
                }
            }
        }

        return $array;
    }
    
    protected function lastRange($days, $balls, $conn, $frequency) {
        $array = $this -> combination_calculation ($days, $balls, $conn, $frequency);
        return $array;
    }

    private function tenNumbersCreator($days, $balls, $conn, $frequency) {
        $array = $this -> lastRange($days, $balls, $conn, $frequency);
        //Alearoriamente organizamos array
        shuffle($array);
        //Lo divido en pedazos de 10
        $array = array_chunk ($array, 10);
        //Retorno el primer elemento de ese arreglo
        if(count($array) == 0) {
            return [];
        }
        return $array[0];
    }

//Final
    public function finalNumbers ($days, $balls, $conn, $frequency) {
        $array = $this -> tenNumbersCreator($days, $balls, $conn, $frequency);
        sort($array);
        return $array;
    }
}
?>