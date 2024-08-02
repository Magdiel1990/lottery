<?php
//Clase para la diferencia de las jugadas dependiendo de las posición de los números
class PartialSumClass {
    private $conn;
    private $balls;
    private $numbers;
    private $test;

    public function __construct($test, $conn, $balls, $numbers) {
        $this -> conn = $conn;
        $this -> balls = $balls;
        $this -> numbers = $numbers;
        $this -> test = $test;
    }   

    //Posición de los números
    private function numbersPosition ($down, $up) {
        $positionCalculation = new PreviousPlaysOut(true, $this -> conn, $this -> balls, $this -> numbers);
        
        //Se calculan las posiciones de los números
        $positionArrayDown = $positionCalculation -> positionCalculation($down);
        $positionArrayUp = $positionCalculation -> positionCalculation($up);

        //Se retornan las posiciones de los números
        return [$positionArrayDown, $positionArrayUp];
    }
    
    //Diferencia de los números
    private function numbersSum ($down, $up) {
        //Se obtienen las posiciones de los números
        $positionArrayDown = $this-> numbersPosition ($down, $up) [0];
        $positionArrayUp = $this-> numbersPosition ($down, $up) [1];

        $positionSum = [];
        //Se calcula la diferencia de las posiciones
        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionSum [] = $positionArrayUp[$i] + $positionArrayDown[$i];
        }
        //Se retorna la diferencia de las posiciones
        return $positionSum;
    }   

    //Diferencia de los números de un array
    public function sumArray($array, $down, $up) {
        $firstPosition = $array[$down - 1];
        $secondPosition = $array[$up - 1];

        return $firstPosition + $secondPosition;
    }

    //Rango de las diferencias
    public function minMaxSumRange ($down, $up) {
        $minMaxArray = $this -> numbersSum ($down, $up);

        return [min($minMaxArray), max($minMaxArray)];
    }

    //Comparación de las diferencias
    public function sumPlaysCalculation() {
        //Se verifica el filtro anterior
        if($this -> test == false) {
            return false;
        }
        
        $result = true;
        //Se calcula si la diferencia de las jugadas es igual a alguna de las diferencias de las posiciones
        for ($i = 1; $i < $this -> balls; $i++) {
            for ($j = $i + 1; $j <= $this -> balls; $j++) {
                //Si la diferencia de las jugadas es igual a alguna de las diferencias de las posiciones
                if (in_array ($this -> sumArray($this -> numbers, $i, $j), $this -> numbersSum ($i, $j))) {
                    $result = false;
                    return $result;
                }
            }            
        }
        //Se retorna el resultado
        return $result;
    }
}
?>

