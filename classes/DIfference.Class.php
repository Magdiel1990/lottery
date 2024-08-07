<?php
//Clase para la diferencia de las jugadas dependiendo de las posición de los números
class DiffClass {
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
    private function numbersDiff ($down, $up) {
        //Se obtienen las posiciones de los números
        $positionArrayDown = $this-> numbersPosition ($down, $up) [0];
        $positionArrayUp = $this-> numbersPosition ($down, $up) [1];

        $positionDiferences = [];
        //Se calcula la diferencia de las posiciones
        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionDiferences [] = abs($positionArrayUp[$i] - $positionArrayDown[$i]);
        }
        //Se retorna la diferencia de las posiciones
        return $positionDiferences;
    }   

    //Diferencia de los números de un array
    public function diffArray($array, $down, $up) {
        $firstPosition = $array[$down - 1];
        $secondPosition = $array[$up - 1];

        return abs($firstPosition - $secondPosition);
    }

    //Rango de las diferencias
    public function minMaxDiffRange ($down, $up) {
        $minMaxArray = $this -> numbersDiff ($down, $up);

        return [min($minMaxArray), max($minMaxArray)];
    }

    //Comparación de las diferencias
    public function diffPlaysCalculation () {
        //Se verifica el filtro anterior
        if($this -> test == false) {
            return false;
        }
        
        $result = true;
        //Se calcula si la diferencia de las jugadas es igual a alguna de las diferencias de las posiciones
        for ($i = 1; $i < $this -> balls; $i++) {
            for ($j = $i + 1; $j <= $this -> balls; $j++) {
                $diffArray = $this -> diffArray($this -> numbers, $i, $j);
                $minMaxDiffRange =  $this -> minMaxDiffRange ($i, $j);
                //Si la diferencia de las jugadas es igual a alguna de las diferencias de las posiciones
                if ($diffArray < $minMaxDiffRange[0] || $diffArray > $minMaxDiffRange[1]) {
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

