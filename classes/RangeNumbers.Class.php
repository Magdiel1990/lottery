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

    private function oddEvenLastNumbers() {
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT number FROM numbers ORDER BY date desc LIMIT 5;");
        
        $odd = 0;
        $even = 0;

        while($row = $result -> fetch_assoc()) {
            if($row["number"] % 2 == 0) {
                $even += 1;
            } else {
                $odd += 1;
            }
        }

        return [$odd, $even];
    }

    private function oddEvenCurrentNumber() {
        $arrayNumbers = $this-> dateOut(); 

        $odd = 0;
        $even = 0;

        for($i = 0; $i < count($arrayNumbers); $i++) {
            if($arrayNumbers[$i] % 2 == 0) {
                $even += 1;
            } else {
                $odd += 1;
            }
        }

        return [$odd, $even];
    }

    private function oddEvenCalculation () {
        //Números random
        $arrayNumbers = $this-> dateOut(); 

        $oddEvenCurrentNumber = $this -> oddEvenCurrentNumber();
        $oddEvenLastNumbers = $this -> oddEvenLastNumbers();
        //Si hay más números impares que pares en ambos arrays
        if($oddEvenLastNumbers[0] > $oddEvenLastNumbers[1] && $oddEvenCurrentNumber[0] > $oddEvenCurrentNumber[1]) {
            for($i = 0; $i < count($arrayNumbers); $i++) {
                if($arrayNumbers[$i] % 2 !== 0) {
                    $arrayNumbers[$i] = $arrayNumbers[$i] + 1;
                    break;
                }
            }
        } else if($oddEvenLastNumbers[0] < $oddEvenLastNumbers[1] && $oddEvenCurrentNumber[0] < $oddEvenCurrentNumber[1]) {
            for($i = 0; $i < count($arrayNumbers); $i++) {
                if($arrayNumbers[$i] % 2 === 0) {
                    $arrayNumbers[$i] = $arrayNumbers[$i] + 1;
                    break;
                }
            }
        } 

        sort($arrayNumbers);

        return $arrayNumbers;
    }

    private function nthposition($arrayNumbers, $position) {
        
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT number FROM numbers WHERE position = " . $position . " ORDER BY date desc LIMIT 1;");
        $row = $result -> fetch_assoc();
        $number = $row["number"];

        if(in_array($number, $arrayNumbers)) {
            for($i = 0; $i < count($arrayNumbers); $i++) {
                if($arrayNumbers[$i] == $number) {
                    unset($arrayNumbers[$i]);
                }
            }                        
        }
        
        sort($arrayNumbers);

        return $arrayNumbers;
    }

    private function missingNumbers(){
        //Números random
        $arrayNumbers = $this-> oddEvenCalculation ();
        $arrayNumbers =  $this-> nthposition($arrayNumbers, 2);
        $arrayNumbers =  $this->nthposition($arrayNumbers, 3);
        $arrayNumbers = $this-> nthposition($arrayNumbers, 4);
        $arrayNumbers = $this-> nthposition($arrayNumbers, 5);

        $arrayCount = count($arrayNumbers);
        $arrayCountDiff = 5 - $arrayCount;

        $currentDate = date("Y-m-d H:i:s");
        $thirdLastDates = date("Y-m-d 00:00:00", strtotime ($currentDate."- 3 days"));       

        $conn = DatabaseClass::dbConnection();        
        $result = $conn -> query("SELECT number FROM numbers WHERE date = '" . $thirdLastDates . "' ORDER BY rand() LIMIT $arrayCountDiff;");
        
        $randomNumbersArray = [];

        while($row = $result -> fetch_assoc()) {
            $randomNumbersArray [] = $row ["number"];
        }
        
        return array_merge($arrayNumbers, $randomNumbersArray);
    }    

    public function repeatedNumbers($arrayNumbers = null) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = $this-> missingNumbers();

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
}

class RangeNumbersChild extends RangeNumbers {
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

    protected function modeNumber($position) {
        $conn = DatabaseClass::dbConnection();  
        $result = $conn -> query ("SELECT number, COUNT(*) as mode FROM numbers WHERE position = $position GROUP BY number ORDER BY mode DESC LIMIT 1;");

        $row = $result -> fetch_assoc();

        $mode = $row ["mode"];

        return $mode;
    }

    protected function mostfrequentNumbers ($arrayNumbers = null) {
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = $this-> repeatedNumbers($arrayNumbers);

        sort($arrayNumbers);
       
        $f1 = $this-> modeNumber(1);
        $f2 = $this-> modeNumber(2);
        $f3 = $this-> modeNumber(3);
        $f4 = $this-> modeNumber(4);
        $f5 = $this-> modeNumber(5);

        $Range1 = [$this-> minNumberRange(1), $this-> maxNumberRange(1)];
        $Range2 = [$this-> minNumberRange(2), $this-> maxNumberRange(2)];
        $Range3 = [$this-> minNumberRange(3), $this-> maxNumberRange(3)];
        $Range4 = [$this-> minNumberRange(4), $this-> maxNumberRange(4)];
        $Range5 = [$this-> minNumberRange(5), $this-> maxNumberRange(5)];

        if($arrayNumbers[0] > $Range1[1]) {
            $arrayNumbers[0] = $f1;
        }

        if($arrayNumbers[1] > $Range2[1] || $arrayNumbers[1] < $Range2[0]){
            $arrayNumbers[1] = $f2;
        }

        if($arrayNumbers[2] > $Range3[1] || $arrayNumbers[2] < $Range3[0]){
            $arrayNumbers[2] = $f3;
        }

        if($arrayNumbers[3] > $Range4[1] || $arrayNumbers[3] < $Range4[0]) {
            $arrayNumbers[3] = $f4;
        }

        if($arrayNumbers[4] < $Range5[0]){
            $arrayNumbers[4] = $f5;
        }

        return $arrayNumbers;
    }

    public function randomNumbers() {
        $arrayNumbers = $this-> arrayNumbers();
        $arrayNumbers = $this-> mostfrequentNumbers($arrayNumbers); 

        return $arrayNumbers;
    }
}
?>