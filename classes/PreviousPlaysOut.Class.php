<?php
// EXCLUIR LAS JUGADAS ANTERIORES
class PreviousPlaysOut {
    public $numbers;
    public $test;
    public $conn;
    public $balls;

    public function __construct($test, $conn, $balls, $numbers) {
        $this -> test = $test;
        $this -> conn = $conn;
        $this -> balls = $balls;
        $this -> numbers = $numbers;
    }

    //Total de jugadas
    protected function positionCalculation($position) {
        $result = $this -> conn -> query ("SELECT number FROM numbers WHERE position = '$position' ORDER BY date desc;");

        $positionArray = [];

        while($row = $result -> fetch_assoc()) {
            $positionArray[] = intval($row["number"]);
        }

        return $positionArray;
    }
    
    //Arreglo de los arreglos de todas las jugadas pasadas
    protected function totalNumbersArrays(){
        $positionArray = [];

        for ($i = 1; $i <= $this -> balls; $i++) {
            $positionArray [] = $this-> positionCalculation($i);
        }
        return $positionArray;
    }

    //Arreglo de todas las jugadas pasadas
    protected function totalNumbers(){
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
        $arrayNumbers = $this-> numbers;
        $totalNumbers = $this-> totalNumbers(); 
        
        if($this -> test == false) {
            return false;
        } else {       
            for($i = 0; $i < count($totalNumbers); $i++) {
                if($totalNumbers[$i] == $arrayNumbers) {
                    return false;
                    break;
                } else {
                    return true;
                }
            }   
        }      
    }
}
?>