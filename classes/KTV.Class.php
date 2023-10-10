<?php
require_once ("Lottery.Class.php");

class KTVClass extends LotteryClass {
    //Filter 6
    protected function subRange($balls, $conn) {            
        $array = $this -> sumRange($balls, $conn);

     //   $array = $this -> diffRangeLoop ($array, $balls, $conn);

        return $array;
    }
    //Filter 9
    protected function consecutiveOutArray ($balls, $conn){
        return $this -> rangeProArray ($balls, $conn);    
    }

    //Filter 10
    protected function sumEach($balls, $conn) {
        $array = $this -> consecutiveOutArray($balls, $conn);
        
       // $array = $this -> sumEachLoop($array, $balls, $conn);
   
        return $array;
    }

    protected function lastRange($amount, $balls, $up, $conn) {
        $array = $this -> insersectArrayOut ($amount, $up, $balls, $conn, 0.02);
        /*for($i = 1; $i <= $balls - 3; $i++) {
            $array = $this -> range_filter($array, $i, $i*10);
        }
        $array = $this -> range_filter($array, 3, 25);
        $array = $this -> range_filter($array, 4, 30);*/
        return $array;
    }

    private function tenNumbersCreator($balls, $up, $conn) {
        $array = $this -> lastRange(200000, $balls, $up, $conn);
        //Alearoriamente organizamos array
        shuffle($array);
        //Lo divido en pedazos de 10
        $array = array_chunk ($array, 10);
        //Retorno el primer elemento de ese arreglo
        return $array[0];
    }

//Final
    public function finalNumbers ($balls, $up, $conn) {
        $array = $this -> tenNumbersCreator($balls, $up, $conn);
        sort($array);
        return $array;
    }
}
?>