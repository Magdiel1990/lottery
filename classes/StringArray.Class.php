<?php
class StringArray {
    private $string;

    public function __construct($string) {
        $this -> string = $string;
    }

    public function stringtoArray() {
    //Se convierten los números en un array
    $numbers = explode(" ", $this -> string);

    //Se convierten los números a enteros
        for($i = 0; $i < count($numbers); $i++) {
            $numbers[$i] = (int) $numbers[$i];
        }   

        return $numbers;
    }
}




?>