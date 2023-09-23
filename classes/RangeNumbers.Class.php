<?php
class RangeNumbers {
    private $position;
    private $start;
    
    private function numberRange($position, $start) {
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT max(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return rand ($start, $number[0]);
    }

    private function minNumberRange($position) {
        $conn = DatabaseClass::dbConnection();
        
        $result = $conn -> query("SELECT min(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    private function arrayNumbers() {
        $firstNumber = $this-> numberRange(1, $this-> minNumberRange(1));
        $secondNumber = $this-> numberRange(2, $this-> minNumberRange(2));
        $thirdNumber = $this-> numberRange(3, $this-> minNumberRange(3));
        $fourthNumber = $this-> numberRange(4, $this-> minNumberRange(4));
        $fifthNumber = $this-> numberRange(5, $this-> minNumberRange(5));

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

    public function missingNumbers(){
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
}
?>