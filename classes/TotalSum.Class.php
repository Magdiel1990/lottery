<?php
Class TotalSum {
    public $conn;
    public $test;
    public $numbers;

    public function __construct($test, $conn, $numbers) {
        $this -> test = $test;
        $this -> conn = $conn;
        $this -> numbers = $numbers;
    }

    //Array de la suma de todas las jugadas hechas
    private function sumsArrayNumbers() {
        $result = $this -> conn -> query ("SELECT sum(number) AS suma FROM numbers GROUP BY date ORDER BY suma;");

        $sums = [];

        while($row = $result -> fetch_assoc()) {
             $sums []  = intval($row ["suma"]);
        }

        return [min($sums), max($sums)];        
    }
    //Suma de los elementos de una jugada
    public function sumArray() {
        $count = count($this -> numbers);

        if($count == 0) {
            return 0;
        }

        $sum = 0;
        for($i = 0; $i < $count; $i++) {
            $sum += $this -> numbers [$i];
        }

        return $sum;
    }

    public function testTotalSum() {
        $sumsRange = $this -> sumsArrayNumbers();
        $sum = $this -> sumArray();

        if($this -> test == false) {
            return false;
        } 

        if($sum < $sumsRange [0] || $sum > $sumsRange [1]) {
            return false;
        } else {
            return true;
        }
    }
}
?>