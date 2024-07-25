<?php
require_once ("Lottery.Class.php");

class LotoClass extends LotteryClass {
    //4. SE INCLUYE EL O LOS NUMEROS QUE MAS SALEN

    //Incluye números que más se  repiten de sorteos anteriores

    protected function normalNumbers($days, $balls, $conn) {

        $keys = [];

        for ($i = 1; $i <= $balls; $i++) {            
            $keys [] = $this-> positionCalculation($i, $conn)[1]/*Dos días atrás*/;
        }

        $arrayNumbers = $this-> arrayNumbers($balls, $conn); 

        while(count($arrayNumbers) != $balls) {
            array_push($arrayNumbers, $keys [rand(0, $balls - 1)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }


// Calculo de pares e impares   
 /*   protected function oddEvenArray ($days, $balls, $conn, $pastGames) {
        $array = $this -> pastDaysAccount ($days, $balls, $conn, $pastGames);
        $even = $this -> oddEvenCal($days, $array, $balls, $conn);

        if($even == false) {
            return [];
        }

        if($even == 2 || $even == 3 || $even == 4) {
            return $array;
        } else {
            return [];
        }
    }

    protected function multiple_test ($array, $days, $times, $number, $balls, $conn){
        $percentage = $this -> multipleCalculation ($times, $number, $balls, $conn);           
        $repeat = $this -> multipleCounter($number, $array);

        if ($repeat == $times) {
            if($percentage > 10) {
                return $array;
            } else {
                return [];
            }  
        } else {
            return $array;
        }         
    }
   
    protected function multiple($days, $balls, $conn, $pastGames) {   
        $array = $this -> pastDaysAccount ($days, $balls, $conn, $pastGames); 
        $array = $this -> multiple_test ($array, $days, 0, 2, $balls, $conn);   
        $array = $this -> multiple_test ($array, $days, 1, 2, $balls, $conn); 
        $array = $this -> multiple_test ($array, $days, 2, 2, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 2, $balls, $conn);   
        $array = $this -> multiple_test ($array, $days, 4, 2, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 3, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 4, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 5, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 6, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 7, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 8, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 9, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 0, 10, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 1, 10, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 2, 10, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 3, 10, $balls, $conn);  
        $array = $this -> multiple_test ($array, $days, 4, 10, $balls, $conn);  

        return $array;
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