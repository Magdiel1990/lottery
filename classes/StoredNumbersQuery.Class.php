<?php
class StdInsertQuery {
    private $numbers;
    private $conn;


    public function __construct($numbers, $conn) {
        $this -> numbers = $numbers;
        $this -> conn = $conn;
    }

    //Conversión de array a string
    private function arrToString () {
        $numbers = implode(" ", $this -> numbers);
        return $numbers;
    }

    //Inserción de los números en la base de datos
    private function numberInsertQuery () {
        $numbers = $this -> arrToString();
        
        $result = $this -> conn -> query ("INSERT INTO `stored` (numbers) VALUES ('" . $numbers . "');");        

        return $result;
    }
    //Confirmación de la inserción de los números
    public function insertQueryConfirm (){
        //Comprobacion de la inserción de los números
        $result = $this -> numberInsertQuery();

        if($result) {
            return true;
        } else {
            return false;
        }
    }
}
?>