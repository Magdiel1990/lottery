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

    //Suma de los números de un array
    public function sumArray($array, $down, $up) {
        $arrayPos = [$array[$down - 1], $array[$up - 1]];

        return array_sum($arrayPos);
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
                //Suma de los números
                $sumArray = $this -> sumArray($this -> numbers, $i, $j);
                //Rango de las sumas
                $minMaxSumRange= $this -> minMaxSumRange ($i, $j);
                //Si la diferencia de las jugadas es igual a alguna de las diferencias de las posiciones
                if ($sumArray < $minMaxSumRange[0] || $sumArray > $minMaxSumRange[1]) {
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

