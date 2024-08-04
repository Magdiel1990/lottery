<?php
//Conection to database
require("classes/Database.Class.php");
$conn = DatabaseClassLoto::dbConnection();

//Initiate the session
session_start();

if(isset($_POST['numbers'])) {
    //Se recibe la jugada y se conviete en string separado por espacio
    $numbers = $_POST['numbers'];   

    //Se ordenan las jugadas
    sort($numbers);

    $numbers = implode (' ', $numbers);

    //Se almacena la jugada en la base de datos
    $stmt = $conn -> prepare("INSERT INTO `stored` (numbers) VALUES (?)");
    $stmt->bind_param("s", $numbers);

    if($stmt->execute()) {
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