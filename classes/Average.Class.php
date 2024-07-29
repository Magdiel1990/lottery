<?php
class Average {
    private $conn;
    private $test;
    private $numbers;
    private $balls;

    public function __construct($test, $conn, $numbers, $balls) {
        $this -> test = $test;
        $this -> conn = $conn;
        $this -> numbers = $numbers;
        $this -> balls = $balls;
    }

    public function average($array) {
        $count = count($array);

        $sum = new TotalSum($this -> test, $this -> conn, $array);        
        $sum = $sum -> sumArray ($array);
    
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

    public function maxMinRange () {
        //Se ordenan los promedios
        $averageTotalArray = $this -> totalAverage();       

        //Se obtienen los valores mínimo y máximo
        $minAvg = min($averageTotalArray);
        $maxAvg = max($averageTotalArray);

        return [$minAvg, $maxAvg];
    }

    public function averagePastGames() {
        //Se obtiene el promedio
        $average = $this -> average($this -> numbers);

        //Se obtienen los valores mínimo y máximo
        $minAvg = $this -> maxMinRange()[0];
        $maxAvg = $this -> maxMinRange()[1];

        //Si no se ha pasado la prueba anterior
        if($this -> test == false) {
            return false;
        } 

        //Si el promedio es menor al mínimo o mayor al máximo
        if($average < $minAvg || $average > $maxAvg) {
            return false;
        } else {
            return true;
        }
    }
}
?>