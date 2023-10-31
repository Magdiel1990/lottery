<?php
require_once ("Lottery.Class.php");

class LPClass extends LotteryClass { 
   /* protected function numberRange($position, $conn) {
        switch ($position) {
            case 1:
                return rand(1,9);
                break;
            case 2: 
                return rand(6,16);
                break;
            case 3:
                return rand(10,23);
                break;
            case 4:
                return rand(17,27);
                break;
            default:
                return rand(24,31);                
        }
    } */
    
    //Patrón de restas
    protected function diffRangeLoop($array, $conn) {
        $array = $this -> diffRange($array, 1, 2, $conn);
        $array = $this -> diffRange($array, 1, 3, $conn);
        $array = $this -> diffRange($array, 1, 4, $conn);
        $array = $this -> diffRange($array, 1, 5, $conn);
        $array = $this -> diffRange($array, 2, 3, $conn);
        $array = $this -> diffRange($array, 2, 4, $conn);
        $array = $this -> diffRange($array, 2, 5, $conn);
        $array = $this -> diffRange($array, 3, 4, $conn);
        $array = $this -> diffRange($array, 3, 5, $conn);
        $array = $this -> diffRange($array, 4, 5, $conn);
  
        return $array;
    }

   
    //Patrón de restas
    protected function sumEachLoop($array, $conn) {        
        $array = $this -> rangeSumEach($array, 1, 2, $conn);
        $array = $this -> rangeSumEach($array, 1, 3, $conn);
        $array = $this -> rangeSumEach($array, 1, 4, $conn);
        $array = $this -> rangeSumEach($array, 1, 5, $conn);
        $array = $this -> rangeSumEach($array, 2, 3, $conn);
        $array = $this -> rangeSumEach($array, 2, 4, $conn);
        $array = $this -> rangeSumEach($array, 2, 5, $conn);
        $array = $this -> rangeSumEach($array, 3, 4, $conn);
        $array = $this -> rangeSumEach($array, 3, 5, $conn);
        $array = $this -> rangeSumEach($array, 4, 5, $conn);

        return $array;
    }

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