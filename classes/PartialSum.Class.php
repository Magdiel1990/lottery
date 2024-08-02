<?php


protected function number_sum ($down, $up, $conn) {
    //Posiciones a sumar
    $positionArrayDown = $this-> positionCalculation($down, $conn);
    $positionArrayUp = $this-> positionCalculation($up, $conn);

    $positionSums = [];
    //Suma de posiciones
    for($i = 0; $i < count($positionArrayDown); $i++) {
        $positionSums [] = $positionArrayUp[$i] + $positionArrayDown[$i];
    }

    return $positionSums;
}



?>