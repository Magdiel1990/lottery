<?php

/*
 //9. GENERADOR DE ALEATORIOS PARA SER EXCLUIDOS

    //Generador de random
    protected function randomGenerator($amount) {
        $randomArraysOfTheDay = [];

        while(count($randomArraysOfTheDay) < $amount) {
            $generatedRandomArray = [];
            while(count($generatedRandomArray)< 5) {
                $generatedRandomArray [] = rand(1,31);
                $generatedRandomArray = array_unique($generatedRandomArray, SORT_NUMERIC);
            }

            sort($generatedRandomArray);
            
            $randomArraysOfTheDay [] = $generatedRandomArray;
        }

       return $randomArraysOfTheDay;
    }

    //10. EXCLUSION DE ALEATORIOS

    //Verificar si esta jugada ya había salido
    protected function randomNumbersExceptions ($totalNumbers, $arrayNumbers) {
        sort($arrayNumbers);
         
        for($i = 0; $i < count($totalNumbers); $i++) {
            if($totalNumbers[$i] == $arrayNumbers) {
                $arrayNumbers = [];
                return $arrayNumbers;
            }
        }      
        return $arrayNumbers;
    }

    //3. SE EXCLUYE EL O LOS NUMEROS QUE MENOS SALEN

    protected function rareNumbersOut($arrayNumbers = null, $amount) {
        $conn = DatabaseClass::dbConnection();  
        $arrayNumbers = $this-> arrayNumbers();   
       
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


     //Promedio del array
    private function average($array) {
        $sum = $this -> sumArray ($array);
       
        return $media = $sum / count($array);
    }

    //Desviación estándar
    private function standardDeviation ($array) {
        $count = count($array);
        $media = $this -> average($array);

        $varianza = 0;
        for($i = 0; $i < $count; $i++) {
            $varianza += pow(($media - $array[$i]), 2);
        }

        $standardDesviation = sqrt($varianza / $count);

        return $standardDesviation;
    }

    //Desviación estandard del array
    protected function arrayStandardDeviation() {
        $sumsArray = $this -> sumsArray();
        return $this -> standardDeviation ($sumsArray);
    }

    CALCULO DE DECENAS
    protected function decena_filtering($array, $decena) {
        $count = 0;
        for($i = 0; $i < count($array); $i++) {
            if(floor($array[$i] / 10) == $decena) {
                $count += 1;
            }
        }
        return $count;
    }

    

    private function diffLastPlays($position, $balls, $conn) {
        $positionCalculation = $this -> positionCalculation($position, $conn);

        $diffArray = [];

        for($i = 1; $i < count($positionCalculation); $i++) {
            $diffArray [] = abs($positionCalculation[$i - 1] - $positionCalculation[$i]);
        } 
        
        return $diffArray;
    }

    protected function diffLastPlaysComparison ($amount, $up, $balls, $conn, $frequency) {
        $array = $this -> number_period_filter ($amount, $up, $balls, $conn, $frequency);

        if(count($array) == 0) {
            return [];
        }
        
        for($i = 0; $i < $balls; $i++) {
            //Arreglo de diferencias
            $nPosition = $this -> diffLastPlays($i + 1, $balls, $conn);
            //Diferencia actual
            $currentDiff = abs($nPosition [0] - $array[$i]);
            //Rango
            $minMaxArray = $this -> minMaxArray($nPosition);
            //Condicion
            $rangeCondition = $this -> rangeCondition($currentDiff, $minMaxArray, $array);

            if(count($rangeCondition) == 0) {
                break;
            }
        }

        return $rangeCondition;
    }

    //15. QUITAR LOS ALEATORIOS DE HOY    
    //Filter 11
 protected function randOutArray ($amount, $balls, $up, $conn){
        $array = $this -> sumEach($balls, $conn);

        if(count($array) != 0) {
            //Números aleatorios
            $randomNumbers = new RandomGenerator(1, $up, $balls, $amount);
            $randomNumbers = $randomNumbers -> randGen(); 

            for($i = 0; $i < count($randomNumbers); $i++) {
                if($randomNumbers[$i] != $array) {
                    return $array;
                } else {
                    return [];
                }
            }
        } else {
            return $array;
        }
    }

    



        /********************************************Descartar combinaciones anteriores **********************************/
    /*****************************************************************************************************************/
/*
    //16. EXCLUIR COMBINACIONES DE 3 Y 4 ANTERIORES
    private function intersectArrays ($allArrays, $time) {
        $intersectionsArrays = [];
        for($i = 0; $i < count($allArrays) - $time; $i++) {
            $intersectionsArrays [] = array_intersect($allArrays[$i], $allArrays[$i + $time]);
        } 
        
        return $intersectionsArrays;
    }

    private function intersectArraysBets ($time, $balls, $conn) {
        $allArrays = $this -> totalNumbers($balls, $conn);

        $intersectionsArrays = $this -> intersectArrays ($allArrays, $time);

        return $intersectionsArrays;
    }

    private function frequencyCalculation ($position, $time, $balls, $conn){
        $intersectArrays = $this -> intersectArraysBets ($time, $balls, $conn);

        $repeat = 0;

        for($i = 0; $i < count($intersectArrays); $i++) {
            if(count($intersectArrays[$i]) == $position) {
                $repeat += 1;
            }
        } 

        return $repeat;
    }

    private function intersection ($array, $time, $allArrays) {
        $intersection = array_intersect($allArrays [$time - 1], $array);

        return $intersection;
    }

    private function intersectCondition ($array, $position, $time, $frequency, $balls, $conn) {
        //frequency: cantidad máxima de apariciones aceptadas de la repetición de esa secuencia.
        //array: Jugada a ser examinada.
        //position: cantidad de secuencias a tomar en cuenta. Si son 5 bolos puede ser 1,2,3,4 o 5.
        //time: cantidad de días anteriores a la jugada para ser comparados.
        $allArrays = $this -> totalNumbers($balls, $conn);

        if(count($array) == 0) {
            return $array;
        }

        $frequencyCalculation = $this -> frequencyCalculation ($position, $time, $balls, $conn);

        $intersection = $this -> intersection ($array, $time, $allArrays);

        $totalPlays = $this -> totalPlays($conn);
        $frequency = ceil($frequency * ($totalPlays - 1));
   
        if($frequencyCalculation <= $frequency && count($intersection) == $position) {
            return $array;
        } else if (count($intersection) < $position) {
            return $array;
        } else {
            return [];
        }      
    }

    protected function intersectCompare($array, $position, $balls, $frequency, $time, $conn) {

        for($i = 1; $i <= $time; $i++) {
            $array = $this -> intersectCondition ($array, $position, $time, $frequency, $balls, $conn);
            if(count($array) == 0) {
                break;
            }
        }        
        return $array;
    }

    //Filter 12
    abstract protected function insersectArrayOut ($days, $balls, $conn, $frequency);

    /********************************Descartar los numeros por la frecuencia en que salen ***************************/
    /****************************************************************************************************************/
/*
    //17. EXCLUIR NUMEROS QUE SALEN CADA CUANTOS DIAS
    private function datesArray ($ball, $conn) {
        $result = $conn -> query ("SELECT date FROM numbers WHERE number = $ball ORDER by date asc;");
        
        $array = [];

        while($row = $result -> fetch_array()) {
            $array [] = $row[0];
        }

        return $array;
    }

    private function diffDatesArray ($ball, $conn) {
        $dateArray = $this -> datesArray ($ball, $conn);

        $diffDateArray = [];

        for ($i = 0; $i < count($dateArray) - 1; $i++) {
            $diffDateArray [] = intval(date("j", strtotime($dateArray[$i+1]) - strtotime($dateArray[$i])));
        }

        return $diffDateArray;
    }

    private function last_appearance ($ball, $conn) {
        date_default_timezone_set("America/Santo_Domingo");       

        $datesArray = $this -> datesArray ($ball, $conn);
        rsort($datesArray);
        
        $lastappearance = $datesArray[0];

        $difference = strtotime(date("Y-m-d h:i:s")) - strtotime($lastappearance);
        
        $difference = intval(date("j", $difference));

        return $difference;
    }

    private function numberPeriodValue ($ball, $conn) {
        $array = $this -> diffDatesArray ($ball, $conn);

        if(count($array) == 0) {
            return [];
        }
        
        $min = min($array);

        $difference = $this -> last_appearance ($ball, $conn);

        if($difference <= $min) {
            return false;
        } else {
            return true;
        }
    }
    //Filter 13
    protected function number_period_filter ($days, $balls, $conn, $frequency) {        
        $array = $this -> insersectArrayOut ($days, $balls, $conn, $frequency);

        $value = true;

        for($i = 0; $i < count($array); $i++) {
            $numberPeriodValue = $this -> numberPeriodValue ($array[$i], $conn);
            if($numberPeriodValue == false) {
                $value = false;
                break;
            }
        }

        if($value == true) {
            return $array;
        } else {
            return [];
        }
    }
*/
    /****************************************Calcular las combinaciones que mas salen ************************/ 
  /*  private function datesNumbers ($down, $period, $conn) {
        $result = $conn -> query ("SELECT date from numbers WHERE number = $down ORDER BY date DESC LIMIT $period;");
        $dates = [];

        while($row = $result -> fetch_assoc()){
          $dates [] = $row ["date"];
        } 
        
        return $dates;
    }

    private function combinations ($down, $up, $period, $conn) {
        $dates = $this -> datesNumbers ($down, $period, $conn);

        $count = 0;

        if(count($dates) != 0) {
            
            for($i = 0; $i < count($dates); $i++) {
                $result = $conn -> query ("SELECT count(id) as `count` FROM numbers WHERE number = '$up' AND date = '" . $dates[$i] . "';");
                $row = $result -> fetch_assoc();
                $count += $row["count"];
            } 
        }

        return $count;
    }

    protected function combination_percentage ($down, $up, $period, $conn) {
        $count = $this -> combinations ($down, $up, $period, $conn);

        return ($count/$period)*100;
    }

    abstract protected function combination_calculation ($days, $balls, $conn, $frequency);

    */
    /*
        protected function insersectArrayOut ($days, $balls, $conn, $frequency) {
        $array = $this -> sumEach($days, $balls, $conn);

        $totalPlays = $this -> totalPlays($conn);

        sort($array);

        $array = $this -> intersectCompare($array, 4, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 3, $balls, ceil(($totalPlays - 1) * $frequency), $totalPlays - 1, $conn);

        return $array;
    }  

    protected function combination_calculation ($days, $balls, $conn, $frequency) {
        $array = $this -> number_period_filter ($days, $balls, $conn, $frequency);

        for($i = 0; $i < count($array) - 1; $i++) {
            for($j = $i + 1; $j < $balls; $j++) {
                if($this -> combination_percentage ($array[$i], $array[$j], 30, $conn) < 10) {
                    return [];
                }
            }
        }

        return $array;
    }
*/
    ?>