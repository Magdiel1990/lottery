<?php
require "pages/partials/head.php";

//Se elimina la tabla bid
$sql = "DROP TABLE IF EXISTS `bid`;";
//Se crea la tabla bid
$sql .= "CREATE TABLE `bid`";
$sql .= "(`id` INT NOT NULL AUTO_INCREMENT,";
$sql .= "`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,";
$sql .= "`numbers` VARCHAR(20) NOT NULL,";
$sql .= "PRIMARY KEY (`id`));";
//Se ejecuta la consulta de las fechas
$sql2 = "SELECT `date` FROM numbers GROUP BY `date` ORDER BY `date`;";

//Se obtienen las fechas
if($result = $conn->query($sql2)) {
    //Arreglo para guardar los números
    $numbers = [];
    //Se recorren las fechas
    while($row = $result->fetch_assoc()) {
        $date = $row['date'];
        //Se obtienen los números de la fecha
        $sql3 = "SELECT * FROM numbers WHERE `date` = '". $date ."';";

        if($result2 = $conn->query($sql3)) {                
            //Variable para guardar el número
            $num = "";
            while($row2 = $result2->fetch_assoc()) {                    
                $num .= strval($row2['number']) . " ";
            }
            //Se elimina la última coma
            $num = rtrim($num, " ");
        }

        //Se agrega el número al arreglo
        $numbers[$date] = $num;
    }
}

//Se recorren los números a insertar
foreach($numbers as $date => $num) {
    $sql .= "INSERT INTO `bid` (`date`, `numbers`) VALUES ('". $date ."', '". $num ."');";
}

//Se ejecuta la consulta
if($conn -> multi_query($sql)) {
    echo "Exito";
} else {
    echo "Error: " . $conn -> error;
}

$conn -> close();   
?>