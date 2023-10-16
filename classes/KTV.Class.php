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

    private function tenNumbersCreator($days, $balls, $conn) {
        $array = $this -> sumEach($days, $balls, $conn);
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

    protected function decenas_calculation ($array, $days, $balls, $conn) {

        if(count($array) != 0) {
      
            $decena = [];

            for($i = 0; $i < count($array); $i++) {
                if($array[$i] > 0 && $array[$i] < 10) {
                    $decena [] = "first";
                } else if ($array[$i] >= 10 && $array[$i] < 20) {
                    $decena [] = "second";
                } else if($array[$i] >= 20 && $array[$i] < 30) {
                    $decena [] = "third";
                } else if($array[$i] >= 30 && $array[$i] < 40) {
                    $decena [] = "fourth";
                } else if($array[$i] >= 40 && $array[$i] < 50) {
                    $decena [] = "fifth";
                } else if($array[$i] >= 50 && $array[$i] < 60) {
                    $decena [] = "sixth";
                } else if($array[$i] >= 60 && $array[$i] < 70) {
                    $decena [] = "seventh";
                } else {
                    $decena [] = "eighth";
                } 
            }       
        } else {
            $decena = [];
        }

        return $decena;
    }

    protected function decenas ($days, $balls, $conn) {
        $array = $this -> tenNumbersCreator ($days, $balls, $conn);
        $decena = $this -> decenas_calculation ($array, $days, $balls, $conn);

        $decena = array_unique ($decena);

        if(count($decena) < 8) {
            return [];
        } else {
            return $array;
        }
    }

//Final
    public function finalNumbers ($days, $balls, $conn) {
        $array = $this -> tenNumbersCreator($days, $balls, $conn);
        sort($array);
        return $array;
    }
}
?>