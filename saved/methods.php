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

    
    */

    ?>