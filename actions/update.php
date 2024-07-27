<?php
//Conection to database
require("classes/Database.Class.php");
$conn = DatabaseClassLoto::dbConnection();

//Receive date
if(isset($_GET["date"])) {
    $date = $_GET["date"];

    $stmt = $conn -> prepare("SELECT id FROM numbers WHERE date = ?;");
    $stmt->bind_param("s", $date);    
    $stmt -> execute();
    $result = $stmt -> get_result(); 

    if($result -> num_rows > 0) {
        $resultDelete = $conn -> query("DELETE FROM numbers WHERE date = '". $date ."';");

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
?>