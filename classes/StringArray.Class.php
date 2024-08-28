<?php
class StringArray {

    //Se convierte la cadena en un array
    public function stringtoArray($string) {
    //Se convierten los números en un array
    $numbers = explode(" ", $string);

    //Se convierten los números a enteros
        for($i = 0; $i < count($numbers); $i++) {
            $numbers[$i] = (int) $numbers[$i];
        }   

        return $numbers;
    }

    //Se convierte el array en una cadena
    public function arrayToString ($array) {
        //Variable para guardar la cadena
        $string = "";

        for($i = 0; $i < count($array); $i++) {
            $string .= strval($array[$i]) . " ";
        }
        
        //Se elimina el último espacio
        $string = rtrim($string, " ");

        return $string;
    }

    //Se obtiene un array con las fechas
    public function datesArray() {
        //Se obtienen las fechas de la base de datos
        $conn = DatabaseClassLoto::dbConnection();
        $result = $conn -> query("SELECT `date` FROM `bid` ORDER BY `date` desc;");

        $dates = [];

        while($row = $result -> fetch_assoc()) {
            $dates [] = $row ["date"];
        }

        return $dates;
    }
}
?>