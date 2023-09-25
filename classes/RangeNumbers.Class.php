<?php
class RangeNumbers {
    private $position;
    private $start;
    
    protected function maxNumberRange($position) {
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT max(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    protected function minNumberRange($position) {
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT min(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    private function numberRange($position) {
        $maxNumber = $this-> maxNumberRange($position);
        $minNumber = $this-> minNumberRange($position);

        return rand ($minNumber, $maxNumber);
    }

    protected function arrayNumbers() {
        $firstNumber = $this-> numberRange(1);
        $secondNumber = $this-> numberRange(2);
        $thirdNumber = $this-> numberRange(3);
        $fourthNumber = $this-> numberRange(4);
        $fifthNumber = $this-> numberRange(5);

        $arrayNumbers = [$firstNumber, $secondNumber, $thirdNumber, $fourthNumber, $fifthNumber]; 
        return $arrayNumbers;
    }
    
    private function yearOut() {
        date_default_timezone_set ("America/Santo_Domingo");

        $arrayNumbers = $this-> arrayNumbers();

        $toyear = date("y");

        for($i = 0; $i < count($arrayNumbers); $i++) {
            if($arrayNumbers[$i] == $toyear) {
                unset($arrayNumbers[$i]);
                break;
            }
        }

        sort($arrayNumbers);

        return $arrayNumbers;
    }

    private function monthOut() {
        date_default_timezone_set ("America/Santo_Domingo");

        $arrayNumbers = $this->yearOut();

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

    private function dateOut() {
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
        
    protected function unfrequentNumbersOut ($arrayNumbers = null) {
        $conn = DatabaseClass::dbConnection();  
        $arrayNumbers = $this-> dateOut();   
       
        $result = $conn -> query ("SELECT number, count(*) as total FROM numbers GROUP BY number ORDER BY total asc LIMIT 2;");
        while($row = $result -> fetch_assoc()){
            if(in_array($row["number"], $arrayNumbers)) {
                $arrayNumbers = array_diff($arrayNumbers, array($row["number"]));         
                sort($arrayNumbers);       
            }
        }  

        return $arrayNumbers;
    }   
   
    protected function repeatedNumbers($arrayNumbers = null) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = $this-> unfrequentNumbersOut ();

        $arrayUniqueNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        $currentDate = date("Y-m-d H:i:s");
        //Ultimos numeros
        $lastDates = date("Y-m-d 00:00:00", strtotime ($currentDate."- 1 days"));          

        while(count($arrayUniqueNumbers) < 5) {
            $result = $conn -> query ("SELECT number FROM numbers WHERE date = '$lastDates' ORDER BY rand() LIMIT 1;");
            $row = $result -> fetch_assoc();

            array_push($arrayUniqueNumbers, $row["number"]);

            $arrayUniqueNumbers = array_unique($arrayUniqueNumbers, SORT_NUMERIC);
        }
       
        return $arrayUniqueNumbers;
    }


     private function positionCal($position) {
        $conn = DatabaseClass::dbConnection();  
        $result = $conn -> query ("SELECT number FROM numbers WHERE position = '$position' ORDER BY date;");

        $positionArray = [];

        while($row = $result -> fetch_assoc()) {
            $positionArray[] = $row["number"];
        }

        return $positionArray;
    }
    
    private function numbersArrayWinners(){
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

    public function finalNumbers($totalNumbers = null){
        $totalNumbers = $this-> numbersArrayWinners();
        $arrayNumbers = $this-> repeatedNumbers();

        sort($arrayNumbers);

        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                $this -> arrayNumbers();
                break;
                exit;
            }
        }
        return $arrayNumbers;
    }
}

/********************************************************* */

class RangeNumbersChild extends RangeNumbers {
    public function unfrequentNumbersOut ($arrayNumbers = null) {
        $conn = DatabaseClass::dbConnection();  
        $arrayNumbers = array_unique($arrayNumbers, SORT_NUMERIC);
       
        $result = $conn -> query ("SELECT number, count(*) as total FROM numbers GROUP BY number ORDER BY total asc LIMIT 2;");
        while($row = $result -> fetch_assoc()){
            if(in_array($row["number"], $arrayNumbers)) {
                $arrayNumbers = array_diff($arrayNumbers, array($row["number"]));         
                sort($arrayNumbers);       
            }
        }  

        return $arrayNumbers;
    }
    
    public function repeatedNumbers($arrayNumbers = null) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayUniqueNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        $currentDate = date("Y-m-d H:i:s");
        //Ultimos numeros
        $lastDates = date("Y-m-d 00:00:00", strtotime ($currentDate."- 1 days"));          

        while(count($arrayUniqueNumbers) < 5) {
            $result = $conn -> query ("SELECT number FROM numbers WHERE date = '$lastDates' ORDER BY rand() LIMIT 1;");
            $row = $result -> fetch_assoc();

            array_push($arrayUniqueNumbers, $row["number"]);

            $arrayUniqueNumbers = array_unique($arrayUniqueNumbers, SORT_NUMERIC);
        }
       
        return $arrayUniqueNumbers;
    }

/****Check this */
    public function randomNumbers() {
        $arrayNumbers = $this-> arrayNumbers();
        $arrayNumbers = $this-> unfrequentNumbersOut($arrayNumbers);
        $arrayNumbers = $this-> repeatedNumbers($arrayNumbers);

        return $arrayNumbers;
    }
}
?>