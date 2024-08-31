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
        //Instancia de la clase StringArray
        $getNumbers = new StringArray();
        $numbers = $getNumbers -> getNumbers();

        //Array de las sumas
        $sums = [];

        //Se suman los números de cada jugada
        for($i = 0; $i < count($numbers); $i++) {
            $sums [] = array_sum($numbers[$i]);
        }
        //Se ordena el array
        sort($sums);

        //Se retorna el array de mínimos y máximos de las sumas
        return [min($sums), max($sums)];        
    }

    public function testTotalSum() {
        //Rango de las sumas
        $sumsRange = $this -> sumsArrayNumbers();
        //Suma de la jugada actual
        $sum = $this -> array_sum($this -> numbers);
        
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