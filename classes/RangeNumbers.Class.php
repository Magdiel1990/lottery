<?php
class RangeNumbers {
    private $position;
    private $start;
    private $totalNumbers;
    protected $amount;
    protected string $time;
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
        return $arrayNumbers;
    }
    
    //Descarte del año
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

    //Descarte del mes
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

    //Descarte del día
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

    //Descarte de los números que menos salen
    protected function rareNumbersOut($arrayNumbers = null, $amount) {
        $conn = DatabaseClass::dbConnection();  
        $arrayNumbers = $this-> dateOut();   
       
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
    protected function repeatedNumbers($arrayNumbers = null, string $time) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayNumbers = $this-> rareNumbersOut (null, 2);

        $arrayUniqueNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        $currentDate = date("Y-m-d H:i:s");
        //Ultimos numeros
        $lastDates = date("Y-m-d 00:00:00", strtotime ($currentDate." - " . $time . " days"));          

        while(count($arrayUniqueNumbers) < 5) {
            $result = $conn -> query ("SELECT number FROM numbers WHERE date = '$lastDates' ORDER BY rand() LIMIT 1;");
            $row = $result -> fetch_assoc();

            array_push($arrayUniqueNumbers, intval($row["number"]));

            $arrayUniqueNumbers = array_unique($arrayUniqueNumbers, SORT_NUMERIC);
        }
               
        return $arrayUniqueNumbers;
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

    /*************************************   Filtrando  ************************************/
    /*************************************    números   ************************************/

    //Array de combinación par o impar que más sale
    private function oddEvenArray() {
        $totalNumbers = $this-> totalNumbers();

        $type = [];
        $even = 0;

        for($i = 0; $i < count($totalNumbers); $i++) { 
            for($j = 0; $j < count($totalNumbers[$i]); $j++) {
                if($totalNumbers[$i][$j] % 2 == 0){
                    $even += 1;
                } 
            }    

            $type [] = $even;    
            $even = 0;
        }   

        return $type;
    }

    //Usar solo el tipo de combinación par o impar de la tendencia

    private function evenOfAnArray() {      
        //Verificar cantidad de jugadas pares en el array random
        $arrayNumbers = $this-> repeatedNumbers(null, "3");
        $evenRandom = 0;

        for($i = 0; $i < count($arrayNumbers); $i++) { 
            if($arrayNumbers[$i] % 2 == 0) {
                $evenRandom += 1;                
            }
        }   
        
        return $evenRandom;
    }

    //Determinar cuántas jugadas pares hay

    private function evenOfANumberedArray() {        
        $type = $this-> oddEvenArray();  
        $count = count($type);
        $evenGames = 0;

        if($count > 0) {
            for($i = 0; $i < $count; $i++) { 
                if($type[$i] >= 3) {
                    //Cantidad de jugadas pares de las tendencias
                    $evenGames += 1;
                }
            }   
        } 
        return $evenGames;
    } 

    private function oddEvenCalculus() {    
        $arrayNumbers = $this-> repeatedNumbers(null, "3");  

        $type = $this-> oddEvenArray();  
        $count = count($type);

        //Jugadas pares del random
        $evenRandom = $this -> evenOfAnArray(); 

        //Jugadas pares
        $evenGames = $this -> evenOfANumberedArray();
        //Jugadas impares
        $oddGammes = $count - $evenGames;

        //Si hay más pares que impares en el random y en las tendencias
        if($evenGames > $oddGammes && $evenRandom >= 3) {           
            return $arrayNumbers;
        //Si hay más impares que pares en el random y en las tendencias
        } else if ($evenGames < $oddGammes && $evenRandom < 3) {
            return $arrayNumbers;
        //Cuando el random no va de la mano con las tendencias
        } else {
            return $this -> arrayNumbers($arrayNumbers);
        }
    }

    //Verificar si esta jugada ya había salido
    public function finalNumbers($totalNumbers = null){
        $totalNumbers = $this-> totalNumbers();
        $arrayNumbers = $this-> oddEvenCalculus();

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
       
        $result = $conn -> query ("SELECT number, count(*) as total FROM numbers GROUP BY number ORDER BY total asc LIMIT 2;");
        while($row = $result -> fetch_assoc()){
            if(in_array(intval($row["number"]), $arrayNumbers)) {
                $arrayNumbers = array_diff($arrayNumbers, array(intval($row["number"])));         
                sort($arrayNumbers);       
            }
        }  

        return $arrayNumbers;
    }
    
    public function repeatedNumbers($arrayNumbers = null, string $time) {        
        $conn = DatabaseClass::dbConnection();     
        $arrayUniqueNumbers = array_unique($arrayNumbers, SORT_NUMERIC);

        $currentDate = date("Y-m-d H:i:s");
        //Ultimos numeros
        $lastDates = date("Y-m-d 00:00:00", strtotime ($currentDate."- " . $time . " days"));          

        while(count($arrayUniqueNumbers) < 5) {
            $result = $conn -> query ("SELECT number FROM numbers WHERE date = '$lastDates' ORDER BY rand() LIMIT 1;");
            $row = $result -> fetch_assoc();

            array_push($arrayUniqueNumbers, intval($row["number"]));

            $arrayUniqueNumbers = array_unique($arrayUniqueNumbers, SORT_NUMERIC);
        }
       
        return $arrayUniqueNumbers;
    }

    public function randomNumbers() {
        $arrayNumbers = $this-> arrayNumbers();
        $arrayNumbers = $this-> rareNumbersOut($arrayNumbers, 2);
        $arrayNumbers = $this-> repeatedNumbers($arrayNumbers, "3");

        return $arrayNumbers;
    }
}
?>