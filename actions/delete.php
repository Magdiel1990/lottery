<?php
session_start();

require("../classes/Database.Class.php");

$conn = DatabaseClass::dbConnection();

if(isset($_GET["date"])) {
    $date = $_GET["date"];

    $result = $conn -> query("SELECT id FROM numbers WHERE date = '". $date."';");

    if($result -> num_rows > 0) {
        $result = $conn -> query("DELETE FROM numbers WHERE date = '". $date ."';");

        if($result) {
            $_SESSION ["message"] = "Números eliminados correctamente";
            $_SESSION ["message-alert"] = "success";

            header('Location: /lottery/pages/add.php');
            exit;
        } else {
            $_SESSION ["message"] = "Error al eliminar números";
            $_SESSION ["message-alert"] = "danger";
            
            header('Location: /lottery/pages/add.php');
            exit;
        }  
    } else {
        $_SESSION ["message"] = "Estos números ya fueron eliminados";
        $_SESSION ["message-alert"] = "success";

        header('Location: /lottery/pages/add.php');
        exit;
    }
}
?>