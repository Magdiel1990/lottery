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

    
    */

    /********************************************Descartar combinaciones anteriores **********************************/
    /*****************************************************************************************************************/

    //9. EXCLUIR COMBINACIONES DE 3 Y 4 ANTERIORES
    /*
    private function intersectArrays ($allArrays, $time) {
        $intersectionsArrays = [];
        for($i = 0; $i < count($allArrays) - $time; $i++) {
            $intersectionsArrays [] = array_intersect($allArrays[$i], $allArrays[$i + $time]);
        } 
        
        return $intersectionsArrays;
    }

    protected function intersectArraysBets ($time, $balls, $conn) {
        $allArrays = $this -> totalNumbers($balls, $conn);

        $intersectionsArrays = $this -> intersectArrays ($allArrays, $time);

        return $intersectionsArrays;
    }

    protected function frequencyCalculation ($positions, $time, $balls, $conn){
        $intersectArrays = $this -> intersectArraysBets ($time, $balls, $conn);

        $repeat = 0;

        for($i = 0; $i < count($intersectArrays); $i++) {
            if(count($intersectArrays[$i]) == $positions) {
                $repeat += 1;
            }
        } 

        return $repeat;
    }

    private function intersection ($array, $time, $allArrays) {
        $intersection = array_intersect($allArrays [$time - 1], $array);

        return $intersection;
    }

    protected function intersectCondition ($array, $positions, $time, $frequency, $balls, $conn) {
        //frequency: cantidad máxima de apariciones aceptadas de la repetición de esa secuencia.
        //array: Jugada a ser examinada.
        //position: cantidad de secuencias a tomar en cuenta. Si son 5 bolos puede ser 1,2,3,4 o 5.
        //time: cantidad de días anteriores a la jugada para ser comparados.
        $allArrays = $this -> totalNumbers($balls, $conn);

        if(count($array) == 0) {
            return $array;
        }

        $frequencyCalculation = $this -> frequencyCalculation ($positions, $time, $balls, $conn);

        $intersection = $this -> intersection ($array, $time, $allArrays);
   
        if($frequencyCalculation <= $frequency && count($intersection) == $positions) {
            return $array;
        } else if (count($intersection) < $positions) {
            return $array;
        } else {
            return [];
        }      
    }

    private function intersectCompare($array, $positions, $balls, $frequency, $time, $conn) {

        for($i = 1; $i <= $time; $i++) {
            $array = $this -> intersectCondition ($array, $positions, $time, $frequency, $balls, $conn);
            if(count($array) == 0) {
                break;
            }
        }        
        return $array;
    }

    protected function insersectArrayOut ($balls, $conn) {
        $array = $this -> lastNumbersExceptions(null, $balls, $conn);
        $totalPlays = $this -> totalPlays($conn);

        sort($array);

        $array = $this -> intersectCompare($array, 4, $balls, 5, $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 3, $balls, 5, $totalPlays - 1, $conn);
        $array = $this -> intersectCompare($array, 2, $balls, 5, $totalPlays - 1, $conn);

        return $array;
    }   
    */

    ?>