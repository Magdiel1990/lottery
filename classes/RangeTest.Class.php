<?php
/************************************* Cálculo del ************************************/
/*************************************   rango     ************************************/

//Se requiere la conexión a la base de datos
require "Database.Class.php";

//Se establece la conexión
global $conn = DatabaseClassLoto::dbConnection();

class RangeClass {
//1.SE ESTABLECE EL RANGO
    public $data;
    public $conn;

    public function __construct($data, $conn) {
        $this -> data = $data;
        $this -> conn = $conn;
    }

    //Maximo numero en cualquier posicion
    protected function maxNumberRange($position, $conn) {       
        $result = $conn -> query("SELECT max(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Mínimo numero en cualquier posicion
    protected function minNumberRange($position, $conn) {       
        $result = $conn -> query("SELECT min(number) FROM numbers WHERE position = " . $position . ";");
        $number = $result -> fetch_array();
        return $number[0];
    }

    //Compara los rangos de la jugada hecha
    public function testRange() {
        //Se recorren los números
        for (i = 0; i < count($this -> data); i++) {
            $maxNumber = $this -> maxNumberRange($i + 1);
            $minNumber = $this -> minNumberRange($i + 1);

            if ($this -> data[$i] > $maxNumber || $this -> data[$i] < $minNumber) {
                return false;
                break;
            } else {
                return true;
            }
        }
    }
}
?>