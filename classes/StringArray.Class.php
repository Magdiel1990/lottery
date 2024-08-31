<?php
class StringArray {

    //Se convierte la cadena en un array
    public function stringtoArray($string) {
    //Se convierten los números en un array
        if($string == "") {
            $numbers = null;
        } else {
        $numbers = explode(" ", $string);

        //Se convierten los números a enteros
            for($i = 0; $i < count($numbers); $i++) {
                $numbers[$i] = (int) $numbers[$i];
            }              
        }
        
        return $numbers;
    }

    //Se convierte el array en una cadena
    public function arrayToString ($array) {
        //Variable para guardar la cadena
        $string = "";
        
        //Se convierten los números en una cadena
        for($i = 0; $i < count($array); $i++) {
            $string .= strval($array[$i]) . " ";
        }
        
        //Se elimina el último espacio
        $string = rtrim($string, " ");

        return $string;
    }

    //Metodo para obtener los datos
    private function allData($table) {
        //Se obtienen las fechas de la base de datos
        $conn = DatabaseClassLoto::dbConnection();
        $result = $conn -> query("SELECT " . $table . " FROM `bid` ORDER BY `date` desc;");

        return $result;        
    }

    //Se obtiene un array con las fechas
    public function datesArray() {
        //Se utiliza la función allData para obtener las fechas
        $result = $this -> allData("date");

        $dates = [];

        //Se guardan las fechas en un array
        while($row = $result -> fetch_assoc()) {
            $dates [] = $row ["date"];
        }

        return $dates;
    }

    //Se obtienen los números de una fecha
    public function getNumbers() {
        //Se utiliza la función allData para obtener las jugadas
        $result = $this -> allData("numbers");

        $numbers = [];

        //Se obtienen los números de la base de datos
        while($row = $result -> fetch_assoc()) {
            //Se convierten los números en un array
            $numbers []= $this -> stringtoArray($row["numbers"]);
        }       

        //Se devuelven los números   
        return $numbers;         
    }

    //Se obtienen todos los datos de una fechas
    public function getAllDataFromDate($date) {
        //Se obtienen los números de la base de datos
        $conn = DatabaseClassLoto::dbConnection();
        $result = $conn -> query("SELECT * FROM `bid` WHERE `date` = '$date';");

        return $result;     
    }

    //Se obtienen los números de una fecha
    public function getNumbersFromDate($date) {
        //Se obtienen los números de la base de datos
        $result = $this -> getAllDataFromPlays ($date);
        $row = $result -> fetch_assoc();   

        //Se devuelven los números    
        return $row["numbers"];
    }
}
?>