<?php
//Conection to database
require("classes/Database.Class.php");
$conn = DatabaseClassLoto::dbConnection();

//Require the class to insert the numbers
require("classes/StoredNumbersQuery.Class.php");

//Initiate the session
session_start();

if(isset($_POST['numbers'])) {
    //Se recibe la jugada y se conviete en string separado por espacio
    $numbers = $_POST['numbers'];   

    //Se ordenan las jugadas
    sort($numbers);

    //Se insertan los datos de la jugada de manera provisional
    $stored = new StdInsertQuery ($numbers, $conn);
    $stored -> insertQueryConfirm (); //Se insertan los números en la base de datos

    if($stored) {
        $_SESSION ["message"] = "Jugada almacenada correctamente";
        $_SESSION ["message-alert"] = "success";

        header('Location: ' . root . 'loto/test');
        exit;
    } else {
        $_SESSION ["message"] = "Error al almacenar jugada";
        $_SESSION ["message-alert"] = "danger";
        
        header('Location: ' . root . 'loto/test');
        exit;
    }  
}
?>