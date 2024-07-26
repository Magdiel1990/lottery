<?php
class Average {
    public $conn;
    public $test;
    public $numbers;
    public $balls;

    public function __construct($test, $conn, $numbers, $balls) {
        $this -> test = $test;
        $this -> conn = $conn;
        $this -> numbers = $numbers;
        $this -> balls = $balls;
    }

    public function average($array) {
        $count = count($array);

        $sum = new TotalSum($this -> test, $this -> conn, $array);        
        $sum = $sum -> sumArray ();
    
        return $media = $sum / $count;    
    }

    public function totalAverage() {
        $totalArrayNumbers = new PreviousPlaysOut($this -> test, $this -> conn, $this -> balls, $this -> numbers);
        $totalArrayNumbers = $totalArrayNumbers -> totalNumbers();

        $averageTotalArray = [];

        for($i = 0; $i < count($totalArrayNumbers); $i++) {
            $averageTotalArray [] = $this -> average($totalArrayNumbers[$i]);
        }

        return $averageTotalArray;         
    }    

    public function averagePastGames() {
        //Se ordenan los promedios
        $averageTotalArray = $this -> totalAverage();
        $average = $this -> average($this -> numbers);

        //Se obtienen los valores mínimo y máximo
        $minAvg = min($averageTotalArray);
        $maxAvg = max($averageTotalArray);

        //Si no se ha pasado la prueba anterior
     //   if($this -> test == false) {
    //        return false;
    //    } 

        //Si el promedio es menor al mínimo o mayor al máximo
        if($average < $minAvg || $average > $maxAvg) {
            return false;
        } else {
            return true;
        }
    }
}
?>