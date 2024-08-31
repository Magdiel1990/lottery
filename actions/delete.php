<?php
//Conection to database
require("classes/Database.Class.php");
$conn = DatabaseClassLoto::dbConnection();

//Require the class to convert strings to arrays
require ("classes/StringArray.Class.php");

//Initiate the session
session_start();

//Eliminar números
if(isset($_GET["date"])) {
    $date = $_GET["date"];
    
    //Instance of the class to get all the data of the play
    $getAllDataFromPlays = new StringArray();
    $result = $getAllDataFromPlays -> getAllDataFromDate($date);

    //Check if the date exists
    if($result -> num_rows > 0) {
        $resultDelete = $conn -> query("DELETE FROM `bid` WHERE date = '". $date ."';");

        if($resultDelete) {
            $_SESSION ["message"] = "Números eliminados correctamente";
            $_SESSION ["message-alert"] = "success";

            header('Location: ' . root . 'loto/agregar');
            exit;
        } else {
            $_SESSION ["message"] = "Error al eliminar números";
            $_SESSION ["message-alert"] = "danger";
            
            header('Location: ' . root . 'loto/agregar');
            exit;
        }  
    } else {
        $_SESSION ["message"] = "Estos números ya fueron eliminados";
        $_SESSION ["message-alert"] = "success";

        header('Location: ' . root . 'loto/agregar');
        exit;
    }
}

//Eliminar jugada almacenada para analizar
if(isset($_GET['storedId'])){
    $id = $_GET['storedId'];

    $stmt = $conn -> prepare("SELECT id FROM `stored` WHERE id = ?;");
    $stmt->bind_param("i", $id);    
    $stmt -> execute();
    $result = $stmt -> get_result(); 

    if($result -> num_rows > 0) {
        $resultDelete = $conn -> query("DELETE FROM `stored` WHERE id = '$id';");

        if($resultDelete) {
            $_SESSION ["message"] = "Jugada eliminada correctamente";
            $_SESSION ["message-alert"] = "success";

            header('Location: ' . root . 'loto/test');
            exit;
        } else {
            $_SESSION ["message"] = "Error al eliminar jugada";
            $_SESSION ["message-alert"] = "danger";
            
            header('Location: ' . root . 'loto/test');
            exit;
        }  
    } else {
        $_SESSION ["message"] = "Esta jugada ya fue eliminada";
        $_SESSION ["message-alert"] = "success";

        header('Location: ' . root . 'loto/test');
        exit;
    }
}
?>