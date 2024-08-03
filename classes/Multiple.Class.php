<?php
class MultipleClass {
    private $conn;
    private $numbers;
    private $times;
    private $balls;
    private $test;


    public function __construct($test, $numbers, $times, $balls, $conn) {
        $this -> test = $test;
        $this -> numbers = $numbers;
        $this -> times = $times;
        $this -> balls = $balls;
        $this -> conn = $conn;
    }

    
    public function multipleArrayCal($array, $times) {
        //Ordenar el array
        sort($array);

        $count = 0;
        
        for($i = 0; $i < count($array) - 1; $i++) {
            for($j = $i + 1; $j < count($array); $j++) {
                if($array[$j] / $array[$i] == $times) {
                    $count += 1;
                }
            }
        }
        return $count;
    }

    public function multipleTotalCal() {
        //Total de jugadas
        $totalNumbers = new PreviousPlaysOut($this -> test, $this -> conn, $this -> balls, $this -> numbers);        
        $totalNumbers = $totalNumbers -> totalNumbers(); 

        //Array de multiplos
        $multipleArray = [];

        //Calcular multiplos de todas las jugadas
        for($i = 0; $i < count($totalNumbers); $i++) {
            $multipleArray[] = $this -> multipleArrayCal($totalNumbers[$i], $this -> times);
        }

        return $multipleArray;        
    }

    //Minimo y maximo de multiplos
    public function minMaxMultiple() {
        //Array de multiplos
        $multipleArray = $this -> multipleTotalCal();

        //Minimo y maximo de multiplos
        $min = min($multipleArray);
        $max = max($multipleArray);

        return [$min, $max];
    }

    //Comparacion de multiplos
    public function multipleComparison() {
        //Array de multiplos
        $multipleArray = $this -> multipleTotalCal();
        $count = $this -> multipleArrayCal($this -> numbers, $this -> times);

        //Filtro anterior
        if($this -> test == false) {
            return false;
        } 

        //Comparacion de multiplos
        if (!in_array ($count, $multipleArray)) {
            return false;
        } else {
            return true;
        }
    }
}
?>