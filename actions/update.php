<?php
//Conection to database
require("classes/Database.Class.php");
$conn = DatabaseClassLoto::dbConnection();

//Require the class to convert strings to arrays
require ("classes/StringArray.Class.php");

//Initiate the session
session_start();

/******** Actualizar los numeros agregados *********/
if(isset($_POST["numbers"]) && isset($_POST["date"])) {
    //Receive the numbers
    $numbers = $_POST["numbers"];

    //Convert the array into a string
    $stringArray = new StringArray();
    $numbers = $stringArray -> arrayToString($numbers);

    //Receive the date
    $date = trim($_POST["date"]);

    //Query to update the numbers
    $sql = "UPDATE `bid` SET `numbers` = '" . $numbers . "' WHERE `date` = '" . $date . "';"; 

    //Execute the query
    if($conn -> query($sql)) {
        $_SESSION ["message"] = "Números actualizados correctamente";
        $_SESSION ["message-alert"] = "success";

        header('Location: ' . root . 'loto/agregar');
        exit;
    } else {
        $_SESSION ["message"] = "Error al actualizar los números";
        $_SESSION ["message-alert"] = "danger";

        header('Location: ' . root . 'loto/agregar');
        exit;
    }
}
?>