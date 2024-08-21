<?php
// EXCLUIR LAS JUGADAS ANTERIORES
class Previous {
    private $conn;
    private $test;
    private $numbers;
    private $balls;

    public function __construct($test, $conn, $numbers, $balls) {
        $this -> test = $test;
        $this -> conn = $conn;
        $this -> numbers = $numbers;
        $this -> balls = $balls;
    }

    //Metodo para obtener la interseccion de los arreglos
    public function arrayIntersectionCount($array, $pos /*Posicion del arreglo*/) { 
        //Todas las jugadas
        $allPlays = new PreviousPlaysOut ($this -> test, $this -> conn, $this -> balls, $this -> numbers);
        $allPlays = $allPlays -> totalNumbers();   

        $previous = [];
        //Interseccion de los arreglos
        for ($i = $pos + 1; $i < count($allPlays); $i++) {
            $intersect = array_intersect($array, $allPlays[$i]);

            $previous [] = count($intersect);                
        }
        //Retorna el arreglo de las intersecciones
        return $previous; 
    }

    //Metodo para obtener los arreglos de los arreglos de las intersecciones de las jugadas pasadas
    private function previousPlaysComArray() {
        //Todas las jugadas
        $allPlays = new PreviousPlaysOut ($this -> test, $this -> conn, $this -> balls, $this -> numbers);
        $allPlays = $allPlays -> totalNumbers();   

        $previousArrays = [];
        
        //Arreglos de arraglos de las intersecciones de las jugadas pasadas
        for ($i = 0; $i < count($allPlays) - 1; $i++) {
            $previousArrays [] = $this -> arrayIntersectionCount($allPlays[$i], $i);
        }
        //Retorna los arreglos de los arreglos de las intersecciones de las jugadas pasadas
        return $previousArrays;        
    }

    //Metodo para combinar los arreglos
    private function previousArrayMerge () {
        //Arreglos de los arreglos de las intersecciones de las jugadas pasadas
        $previousArrays = $this -> previousPlaysComArray();

        $totalPreviousArray = [];   

        //Unir todos los arreglos
        for($i = 0; $i < count($previousArrays); $i++) {
            $totalPreviousArray = array_merge($totalPreviousArray, $previousArrays[$i]);
        }

        return $totalPreviousArray;
    }

    //Total de las intersecciones de las jugadas pasadas
    public function totalIntersect () {
        //Arreglo de las intersecciones de las jugadas pasadas
        $previousArrayMerge = $this -> previousArrayMerge();

        return count($previousArrayMerge);
    }

    public function previousElementCount ($element) {
        //Arreglo de las intersecciones de las jugadas pasadas
        $previousArrayMerge = $this -> previousArrayMerge();

        $count = 0;
        
        //Contar las veces que se repite un numero  
        for ($i = 0; $i < count($previousArrayMerge); $i++) {
            if ($previousArrayMerge[$i] == $element) {
                $count += 1;
            }           
        }               

        return $count;
    }

    //Arreglo de las veces que se repite un numero
    public function previousElementCountArray () {
        $elementRepArray = [];

        for ($i = 0; $i <= $this -> balls; $i++) {
            $elementRepArray [$i] = $this -> previousElementCount($i);
        }

        return $elementRepArray;
    }
}

?>