<?php
//RANGO DE DESVIACION ESTANDAR    
class StandardDeviation {
    public $test;
    public $numbers;
    public $balls;
    public $conn;

    public function __construct($test, $numbers, $balls, $conn) {
        $this -> test = $test;
        $this -> numbers = $numbers;
        $this -> balls = $balls;
        $this -> conn = $conn;
    }

    //Desviación estándar
    private function standardDeviation() {
        $count = count($this ->numbers);
        
        //Promedio
        $media = new Average($this -> test, $this -> conn, $this ->numbers, $this ->balls);        
        $media = $media -> average($this ->numbers);

        //Desviación estándar
        $varianza = 0;
        for($i = 0; $i < $count; $i++) {
            $varianza += pow(($media - $this ->numbers[$i]), 2);
        }

        $standardDesviation = sqrt($varianza / $count);

        return $standardDesviation;
    }

    private function standardDeviationArray() {
        //Total de jugadas
        $totalNumbers = new PreviousPlaysOut($this -> test, $this -> conn, $this -> balls, $this -> numbers);        
        $totalNumbers = $totalNumbers -> totalNumbers(); 
        
        $standardDevArray = [];
        //Desviación estándar de cada jugada
        for($i = 0; $i < count($totalNumbers); $i++) {
            $standardDevArray [] = $this -> standardDeviation ($totalNumbers[$i]);
        }

        return $totalStdDevArray;
    }
    
    public function StdDev() {
        //Arreglo de las desviaciones estándares
        $totalStdDevArray = $this -> standardDeviationArray();
        //Desviación estándar
        $stdDev = $this -> standardDeviation();

        //Mínimo y máximo de las desviaciones estándares
        $minStdDev = min($totalStdDevArray);
        $maxStdDev = max($totalStdDevArray);

        if($this -> test == false) {
            return false;
        } 
        
        //Si la desviación estándar de la jugada actual está fuera del rango de las desviaciones estándares de las jugadas pasadas
        if($stdDev < $minStdDev || $stdDev > $maxStdDev) {
            return false;
        } else {
            return true;
        }
    }
}    
?>