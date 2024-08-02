<?php
// EXCLUIR LAS JUGADAS ANTERIORES
class PreviousPlaysOut {
    private $numbers;
    private $test;
    private $conn;
    private $balls;

    public function __construct($test, $conn, $balls, $numbers) {
        $this -> test = $test;
        $this -> conn = $conn;
        $this -> balls = $balls;
        $this -> numbers = $numbers;
    }

    //Total de jugadas
    public function positionCalculation($position) {
        $result = $this -> conn -> query ("SELECT number FROM numbers WHERE position = '$position' ORDER BY date desc;");

        $positionArray = [];

        while($row = $result -> fetch_assoc()) {
            $positionArray[] = intval($row["number"]);
        }

        return $positionArray;
    }
    
    //Arreglo de los arreglos de todas las jugadas pasadas
    private function totalNumbersArrays(){
        $positionArray = [];

        for ($i = 1; $i <= $this -> balls; $i++) {
            $positionArray [] = $this-> positionCalculation($i);
        }
        return $positionArray;
    }

    //Arreglo de todas las jugadas pasadas
    public function totalNumbers(){
        $positionArray = $this -> totalNumbersArrays();

        $totalPosition = [];

        for($i = 0; $i < count($positionArray[1]); $i++) {
            $partialPosition = []; 

            for($j=0; $j<count($positionArray); $j++) {
                $partialPosition [] = $positionArray[$j][$i];
            }

            $totalPosition [] = $partialPosition;
        }        

        return $totalPosition;
    }

    public function lastNumbersExceptions() {
        //Jugada actual
        $arrayNumbers = $this-> numbers;
        //Todas las jugadas
        $totalNumbers = $this-> totalNumbers(); 
        
        //Si falla el filtro de rango
        if($this -> test == false) {
            return false;
        } 

        foreach ($totalNumbers as $subArray) {
            //Si la jugada actual es igual a alguna de las jugadas anteriores
            if($subArray == $arrayNumbers) {
                $return = false;   
                break;     
            } else {
                $return = true;       
            }
        } 

        return $return;           
    }
}
?>