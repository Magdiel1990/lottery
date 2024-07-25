<?php
class RandomGenerator {
    private $down;
    private $up;
    private $balls;
    private $amount;

    function __construct($down, $up, $balls, $amount){
        $this -> down = $down;
        $this -> up = $up;
        $this -> balls = $balls;
        $this -> amount = $amount;
    }   
//Generador de random
    private function randomGenerator() {
        $randomArraysOfTheDay = [];

        while(count($randomArraysOfTheDay) < $this -> amount) {
            $generatedRandomArray = [];
            while(count($generatedRandomArray)< $this -> balls) {
                $generatedRandomArray [] = rand($this -> down, $this -> up);
                $generatedRandomArray = array_unique($generatedRandomArray, SORT_NUMERIC);
            }

            sort($generatedRandomArray);
            
            $randomArraysOfTheDay [] = $generatedRandomArray;
        }

       return $randomArraysOfTheDay;
    }

    public function randGen() {
        return $this -> randomGenerator();
    }
}
?>