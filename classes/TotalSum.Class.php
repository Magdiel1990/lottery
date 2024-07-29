<?php
Class TotalSum {
    private $conn;
    private $test;
    private $numbers;

    public function __construct($test, $conn, $numbers) {
        $this -> test = $test;
        $this -> conn = $conn;
        $this -> numbers = $numbers;
    }

    //Array de la suma de todas las jugadas hechas

    public function sumsArrayNumbers() {
        $result = $this -> conn -> query ("SELECT sum(number) AS suma FROM numbers GROUP BY date ORDER BY suma;");

        $sums = [];

        while($row = $result -> fetch_assoc()) {
             $sums []  = intval($row ["suma"]);
        }

        return [min($sums), max($sums)];        
    }
    //Suma de los elementos de una jugada
    public function sumArray($array) {
        $count = count($array);

        if($count == 0) {
            return 0;
        }

        $sum = 0;
        for($i = 0; $i < $count; $i++) {
            $sum += $array[$i];
        }

        return $sum;
    }

    public function testTotalSum() {
        //Rango de las sumas
        $sumsRange = $this -> sumsArrayNumbers();
        //Suma de la jugada actual
        $sum = $this -> sumArray($this -> numbers);
        
        //Si no está en el rango anterior
        if($this -> test == false) {
            return false;
        } 

        //Si la suma no está en el rango
        if($sum < $sumsRange [0] || $sum > $sumsRange [1]) {
            return false;
        } else {
            return true;
        }
    }
}
?>