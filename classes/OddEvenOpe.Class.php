<?php
//Clase para las operaciones de los números pares e impares
class OddEvenOpeClass {
    private $conn;
    private $balls;
    private $numbers;
    private $test;

    public function __construct($test, $conn, $balls, $numbers) {
        $this -> conn = $conn;
        $this -> balls = $balls;
        $this -> numbers = $numbers;
        $this -> test = $test;
    }
    //Arreglos de números pares e impares
    private function oddEvenArrays ($array){
        //Arreglos de números pares e impares
        $oddArray = [];
        $evenArray = [];

        //Se separan los números pares e impares
        for($i = 0; $i < count($array); $i++) {
            if($array [$i] % 2 == 0) {
                $evenArray [] = $array [$i];
            } else {
                $oddArray [] = $array [$i];
            }
        }
        //Retorno de los arreglos de números pares e impares
        return [$oddArray, $evenArray];
    }

    //Suma de los números pares e impares   
    public function oddEvenSums ($array) {
        //Arreglos de números pares e impares
        $oddEvenArray = $this -> oddEvenArrays ($array);

        //Suma de los números pares e impares
        $oddSum = array_sum($oddEvenArray[0]);
        $evenSum = array_sum($oddEvenArray[1]);

        //Retorno de la suma de los números pares e impares
        return [$oddSum, $evenSum];
    }

    //Producto de los números pares e impares
    public function oddEvenProducts($array) {
        //Arreglos de números pares e impares
        $oddEvenArray = $this -> oddEvenArrays ($array);

        //Producto de los números pares e impares
        $oddProduct = array_product($oddEvenArray[0]);
        $evenProduct = array_product($oddEvenArray[1]);

        //Retorno de los productos
        return [$oddProduct, $evenProduct];
    }

    //Suma de los números pares e impares
    private function oddEvenTotalSums () {
        //Total de jugadas
        $totalNumbers = new PreviousPlaysOut($this -> test, $this -> conn, $this -> balls, $this -> numbers);        
        $totalNumbers = $totalNumbers -> totalNumbers(); 
        
        $oddEvenSumsTotalArray = [];

        //Se calcula la suma de los números pares e impares
        for($i = 0; $i < count($totalNumbers); $i++) {
            $oddEvenSumsTotalArray [] = $this -> oddEvenSums ($totalNumbers[$i]);
        }

        //Retorno de la suma de los números pares e impares
        return $oddEvenSumsTotalArray;
    }

    //Máximo y mínimo de la suma de los números impares
    public function maxMinOddSums () {
        //Suma de los números pares e impares
        $oddEvenSumsTotalArray = $this -> oddEvenTotalSums();

        $oddSums = [];
 
        //Máximo y mínimo de la suma de los números impares
        for($i = 0; $i < count($oddEvenSumsTotalArray); $i++) {
            $oddSums [] = $oddEvenSumsTotalArray[$i][0];
        }

        //Máximo y mínimo de la suma de los números impares
        $maxOddSum = max($oddSums);
        $minOddSum = min($oddSums);       

        //Retorno de los valores
        return [$minOddSum, $maxOddSum];
    }

    //Máximo y mínimo de la suma de los números pares
    public function maxMinEvenSums () {
        //Suma de los números pares e impares
        $oddEvenSumsTotalArray = $this -> oddEvenTotalSums();

        $evenSums = [];

        //Máximo y mínimo de la suma de los números pares
        for($i = 0; $i < count($oddEvenSumsTotalArray); $i++) {
            $evenSums [] = $oddEvenSumsTotalArray[$i][1];
        }

        //Máximo y mínimo de la suma de los números pares
        $maxEvenSum = max($evenSums);
        $minEvenSum = min($evenSums);

        //Retorno de los valores
        return [$minEvenSum, $maxEvenSum];
    }

    //Producto de los números pares e impares
    private function oddEvenTotalProduct () {
        //Total de jugadas
        $totalNumbers = new PreviousPlaysOut($this -> test, $this -> conn, $this -> balls, $this -> numbers);        
        $totalNumbers = $totalNumbers -> totalNumbers(); 
        
        $oddEvenProductTotalArray = [];

        //Se calcula el producto de los números pares e impares
        for($i = 0; $i < count($totalNumbers); $i++) {
            $oddEvenProductTotalArray [] = $this -> oddEvenProducts($totalNumbers[$i]);
        }

        return $oddEvenProductTotalArray;
    }

    //Máximo y mínimo del producto de los números impares
    public function maxMinOddProduct () {
        //multiplicacion de los números pares e impares
        $oddEvenProductTotalArray = $this -> oddEvenTotalProduct();

        //Arreglo de los números impares
        $oddPro = [];

        //Máximo y mínimo de la multiplicacion de los números impares
        for($i = 0; $i < count($oddEvenProductTotalArray); $i++) {
            $oddPro [] = $oddEvenProductTotalArray[$i][0];
        }

        //Máximo y mínimo de la multiplicacion de los números impares
        $maxOddPro = max($oddPro);
        $minOddPro = min($oddPro);       

        //Retorno de los valores
        return [$minOddPro, $maxOddPro];
    }
    
    //Máximo y mínimo del producto de los números pares
    public function maxMinEvenProduct () {
        //Suma de los números pares e impares
        $oddEvenProductTotalArray = $this -> oddEvenTotalProduct();

        //Arreglo de los números pares
        $evenPro = [];

        //Máximo y mínimo de la suma de los números pares
        for($i = 0; $i < count($oddEvenProductTotalArray); $i++) {
            $evenPro [] = $oddEvenProductTotalArray[$i][1];
        }

        //Máximo y mínimo de la suma de los números pares
        $maxEvenPro = max($evenPro);
        $minEvenPro = min($evenPro);

        //Retorno de los valores
        return [$minEvenPro, $maxEvenPro];
    }

    //Comprobación de los productos de números pares e impares
    private function oddEvenProductsCalculation() {
        //Producto de los números pares e impares
        $oddEvenTotalProduct = $this -> oddEvenTotalProduct (); //[$oddProduct, $evenProduct]

        //Máximo y mínimo del producto de los números impares y pares
        $maxMinOddProduct = $this -> maxMinOddProduct (); //[$minOddPro, $maxOddPro]
        $maxMinEvenProduct = $this -> maxMinEvenProduct (); //[$minEvenPro, $maxEvenPro]

        //Se verifica el filtro anterior
        if($this -> test == false) {
            return false;
        }
        //Se verifica si el producto de los números pares e impares está dentro del rango
        if($oddEvenTotalProduct[0] < $maxMinOddProduct[0] || $oddEvenTotalProduct[0] > $maxMinOddProduct[1] 
        || $oddEvenTotalProduct[1] < $maxMinEvenProduct[0] || $oddEvenTotalProduct[1] > $maxMinEvenProduct[1]) {
            return false;
        } else {
            return true;
        }
    }

    //Comprobación de las sumas de los números pares e impares
    private function oddEvenSumsCalculation() {
        //Suma de los números pares e impares
        $oddEvenTotalSums = $this -> oddEvenTotalSums (); //[$oddSum, $evenSum]

        //Máximo y mínimo de la suma de los números impares y pares
        $maxMinOddSums = $this -> maxMinOddSums (); //[$minOddSum, $maxOddSum]
        $maxMinEvenSums = $this -> maxMinEvenSums (); //[$minEvenSum, $maxEvenSum]

        //Se verifica el filtro anterior
        if($this -> test == false) {
            return false;
        }
        //Se verifica si la suma de los números pares e impares está dentro del rango
        if($oddEvenTotalSums[0] < $maxMinOddSums[0] || $oddEvenTotalSums[0] > $maxMinOddSums[1] 
        || $oddEvenTotalSums[1] < $maxMinEvenSums[0] || $oddEvenTotalSums[1] > $maxMinEvenSums[1]) {
            return false;
        } else {
            return true;
        }
    }
}
?>