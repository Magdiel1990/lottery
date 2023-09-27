<?php
class RangeNumbers {
    private $position;
    private $start;
    private $totalNumbers;
    protected $amount;
    protected $time;
    protected $arrayNumbers;


    /************************************* Cálculo del ************************************/
    /*************************************   rango     ************************************/


    //Maximo numero en cualquier posicion
    private function maxNumberRange($position) {
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT max(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Mínimo numero en cualquier posicion
    private function minNumberRange($position) {
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT min(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Rango en el que pueden estar los números
    private function numberRange($position) {
        $maxNumber = $this-> maxNumberRange($position);
        $minNumber = $this-> minNumberRange($position);

        return rand ($minNumber, $maxNumber);
    }


    /*************************************   Generando  ************************************/
    /*************************************    números   ************************************/


    //Números aleatorios
    protected function arrayNumbers($arrayNumbers = null) {
        $arrayNumbers = [$this-> numberRange(1), $this-> numberRange(2), $this-> numberRange(3), $this-> numberRange(4), $this-> numberRange(5)]; 
        
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        sort($arrayNumbers);

        return $arrayNumbers;
    }
    
    //Descarte del mes
    private function monthOut() {
        date_default_timezone_set ("America/Santo_Domingo");

        $arrayNumbers = $this-> arrayNumbers();

        $tomonth = date("n");

        for($i = 0; $i < count($arrayNumbers); $i++) {
            if($arrayNumbers[$i] == $tomonth) {
                unset($arrayNumbers[$i]);
                break;
            }
        }

        sort($arrayNumbers);

        return $arrayNumbers;
    }

    //Descarte del día
    private function dayOut() {
        date_default_timezone_set ("America/Santo_Domingo");
        $today = date("j");

        $arrayNumbers = $this-> monthOut();

        for($i = 0; $i < count($arrayNumbers); $i++) {
            if($arrayNumbers[$i] == $today) {
                unset($arrayNumbers[$i]);
                break;
            }
        }

        sort($arrayNumbers);

        return $arrayNumbers;
    }

    //Descarte de los números que menos salen
    protected function rareNumbersOut($arrayNumbers = null, $amount) {
        $conn = DatabaseClass::dbConnection();  
        $arrayNumbers = $this-> dayOut();   
       
        $result = $conn -> query ("SELECT number, count(*) as total FROM numbers GROUP BY number ORDER BY total asc LIMIT $amount;");
        while($row = $result -> fetch_assoc()){
            $number = intval($row["number"]);
            if(in_array($number, $arrayNumbers) && count($arrayNumbers) > $amount) {
                $arrayNumbers = array_diff($arrayNumbers, array($number));                               
            }
        }  

        sort($arrayNumbers);
        
        return $arrayNumbers;
    }  

    //Incluye números de sorteos anteriores
    protected function repeatedNumbers($arrayNumbers = null, $time) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = $this-> rareNumbersOut (null, 2);

        $max = $time * 5; 

        //Ultimos numeros
        $result = $conn -> query ("SELECT number FROM numbers LIMIT 5 OFFSET $max;");

        $numbers = [];
        
        while($row = $result -> fetch_assoc()){
            $numbers [] = intval($row["number"]);
        }        
   
        while(count($arrayNumbers) != 5) {
            array_push($arrayNumbers, $numbers[rand(0,4)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }


    /*************************************    Arreglos de  ************************************/
    /************************************* todas las jugadas **********************************/

    //Arreglos de todas las jugadas pasadas
    private function positionCal($position) {
        $conn = DatabaseClass::dbConnection();  
        $result = $conn -> query ("SELECT number FROM numbers WHERE position = '$position' ORDER BY date;");

        $positionArray = [];

        while($row = $result -> fetch_assoc()) {
            $positionArray[] = intval($row["number"]);
        }

        return $positionArray;
    }
    
    //Arreglo de los arreglos de todas las jugadas pasadas
    private function totalNumbers(){
        $positionArray1 = $this-> positionCal(1);
        $positionArray2 = $this-> positionCal(2);
        $positionArray3 = $this-> positionCal(3);
        $positionArray4 = $this-> positionCal(4);
        $positionArray5 = $this-> positionCal(5);

        $totalPosition = [];

        for($i = 0; $i < count($positionArray1); $i++) {
            $totalPosition[$i] = [$positionArray1[$i], $positionArray2[$i], $positionArray3[$i], $positionArray4[$i], $positionArray5[$i]];
        }        

        return $totalPosition;
    }

    //Verificar si esta jugada ya había salido
    public function finalNumbers($totalNumbers = null){
        $totalNumbers = $this-> totalNumbers();
        $arrayNumbers = $this-> repeatedNumbers(null, 3);

        sort($arrayNumbers);

        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                return $this -> arrayNumbers($arrayNumbers);
            }
        }
      
        return $arrayNumbers;
    }
}


/********************************************************* Clase hija ******************************************************/
/***************************************************************************************************************************/
/***************************************************************************************************************************/


class RangeNumbersChild extends RangeNumbers {

    /*************************************   Generando  ************************************/
    /*************************************    números   ************************************/

    public function rareNumbersOut ($arrayNumbers = null, $amount) {
        $conn = DatabaseClass::dbConnection();  
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);   
       
        $result = $conn -> query ("SELECT number, count(*) as total FROM numbers GROUP BY number ORDER BY total asc LIMIT $amount;");
        while($row = $result -> fetch_assoc()){
            $number = intval($row["number"]);
            if(in_array($number, $arrayNumbers) && count($arrayNumbers) > $amount) {
                $arrayNumbers = array_diff($arrayNumbers, array($number));                               
            }
        }  

        sort($arrayNumbers);
        
        return $arrayNumbers;
    }
    
    public function repeatedNumbers($arrayNumbers = null, $time) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        $max = $time * 5; 

        //Ultimos numeros
        $result = $conn -> query ("SELECT number FROM numbers LIMIT 5 OFFSET $max;");

        $numbers = [];
        
        while($row = $result -> fetch_assoc()){
            $numbers [] = intval($row["number"]);
        }        
   
        while(count($arrayNumbers) < 5) {
            array_push($arrayNumbers, $numbers[rand(0,4)]);

            $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }

    public function randomNumbers() {
        $arrayNumbers = $this-> arrayNumbers();
        $arrayNumbers = $this-> rareNumbersOut($arrayNumbers, 2);
        $arrayNumbers = $this-> repeatedNumbers($arrayNumbers, 3);

        return $arrayNumbers;
    }
}
?>