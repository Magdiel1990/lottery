<?php
//RANGO DE DESVIACION ESTANDAR    
class StandardDeviation {
    private $test;
    private $numbers;
    private $balls;
    private $conn;

    public function __construct($test, $numbers, $balls, $conn) {
        $this -> test = $test;
        $this -> numbers = $numbers;
        $this -> balls = $balls;
        $this -> conn = $conn;
    }

    //Desviación estándar
    public function standardDeviation($array) {
        $count = count($array);
        
        //Promedio
        $media = new Average($this -> test, $this -> conn, $array, $this ->balls);        
        $media = $media -> average($array);

        //Desviación estándar
        $varianza = 0;
        for($i = 0; $i < $count; $i++) {
            $varianza += pow(($media - $array[$i]), 2);
        }

        $standardDesviation = sqrt($varianza / $count);

        return $standardDesviation;
    }

    public function totalStdDev() {
        //Total de jugadas
        $totalNumbers = new PreviousPlaysOut($this -> test, $this -> conn, $this -> balls, $this -> numbers);        
        $totalNumbers = $totalNumbers -> totalNumbers(); 
        
        $standardDevArray = [];
        //Desviación estándar de cada jugada
        for($i = 0; $i < count($totalNumbers); $i++) {
            $standardDevArray [] = $this -> standardDeviation ($totalNumbers[$i]);
        }

        return $standardDevArray;
    }

    public function StdDevRange() {
        //Arreglo de las desviaciones estándares
        $totalStdDevArray = $this -> totalStdDev();        

        //Mínimo y máximo de las desviaciones estándares
        $minStdDev = min($totalStdDevArray);
        $maxStdDev = max($totalStdDevArray);

        return [$minStdDev, $maxStdDev];
    }
    
    public function StdDev() {
        //Desviación estándar
        $stdDev = $this -> standardDeviation($this -> numbers);

        //Si no se ha pasado la prueba anterior
        if($this -> test == false) {
            return false;
        } 
        
        //Si la desviación estándar de la jugada actual está fuera del rango de las desviaciones estándares de las jugadas pasadas
        if($stdDev < $this -> StdDevRange()[0] || $stdDev > $this -> StdDevRange()[1]) {
            return false;
        } else {
            return true;
        }
    }
}    
?>