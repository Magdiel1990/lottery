<?php
//RESTA TOTAL DE UNA JUGADA
class TotalDifference {
    private $test;
    private $numbers;
    private $balls;
    private $conn;

    public function __construct($test, $conn, $numbers, $balls) {
        $this -> test = $test;
        $this -> conn = $conn;
        $this -> numbers = $numbers;
        $this -> balls = $balls;
    }

   //Resta total de una jugada
    public function arrayDifference ($array) {
        //Diferencia inicial
        $difference = $array [$this -> balls - 1];

        //Resta de los números
        for($i = count($array) - 2; $i >= 0; $i--) {
            $difference -= $array[$i];
        }

        return $difference;
    }

    //Resta total de todas las jugadas
    private function totalPlayDifference () {
        //Total de jugadas
        $totalNumbers = new PreviousPlaysOut($this -> test, $this -> conn, $this -> balls, $this -> numbers);        
        $totalNumbers = $totalNumbers -> totalNumbers(); 

        $totalDifference = [];
        //Diferencia total de cada jugada
        for($i = 0; $i < count($totalNumbers); $i++) {
            $totalDifference [] = $this -> arrayDifference ($totalNumbers[$i]);
        }

        return $totalDifference;
    } 

    //Rango de la resta total
    public function maxMinTotalDifference () {
        //Arreglo de las diferencias totales
        $totalDifferenceArray = $this -> totalPlayDifference();        

        //Mínimo y máximo de las diferencias totales
        $minDifference = min($totalDifferenceArray);
        $maxDifference = max($totalDifferenceArray);

        return [$minDifference, $maxDifference];
    }

    
    public function totalDiff() {
        //Rango de la resta total
        $maxMinTotalDifference = $this -> maxMinTotalDifference();

        //Direfencia total de la jugada
        $arrayDifference = $this -> arrayDifference($this -> numbers);

        //Si no se ha pasado la prueba anterior
        if($this -> test == false) {
            return false;
        } 

        //Si la diferencia total de la jugada está dentro del rango
        if($arrayDifference < $maxMinTotalDifference[0] || $arrayDifference > $maxMinTotalDifference[1]) {
            return false;
        } else {
            return true;
        }
    }
}
?>