<?php
//Clase para el producto de las jugadas dependiendo de las posición de los números
class PartialProductClass {
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
    
    //producto de los números
    private function numbersProduct ($down, $up) {
        //Se obtienen las posiciones de los números
        $positionArrayDown = $this-> numbersPosition ($down, $up) [0];
        $positionArrayUp = $this-> numbersPosition ($down, $up) [1];

        $positionPro = [];
        //Se calcula el producto de las posiciones
        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionPro [] = $positionArrayUp[$i] * $positionArrayDown[$i];
        }
        //Se retorna el producto de las posiciones
        return $positionPro;
    }   

    //producto de los números de un array
    public function productArray($array, $down, $up) {
        $firstPosition = $array[$down - 1];
        $secondPosition = $array[$up - 1];

        return $firstPosition * $secondPosition;
    }

    //Rango de las productos
    public function minMaxProRange ($down, $up) {
        $minMaxArray = $this -> numbersProduct ($down, $up);

        return [min($minMaxArray), max($minMaxArray)];
    }

    //Comparación de los productos
    public function productPlaysCalculation() {
        //Se verifica el filtro anterior
        if($this -> test == false) {
            return false;
        }
        
        $result = true;
        //Se calcula si el producto de las jugadas es igual a alguna de los productos de las posiciones
        for ($i = 1; $i < $this -> balls; $i++) {
            for ($j = $i + 1; $j <= $this -> balls; $j++) {
                //Se obtiene el producto de las jugadas
                $productArray = $this -> productArray($this -> numbers, $i, $j);
                //Rango de los productos
                $minMaxProRange = $this -> minMaxProRange ($i, $j);
                //Si el producto de las jugadas es igual a alguna de los productos de las posiciones
                if ($productArray < $minMaxProRange [0] || $productArray > $minMaxProRange [1]) {
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

