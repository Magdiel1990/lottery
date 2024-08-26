<?php
//Conection to database
require("classes/Database.Class.php");
$conn = DatabaseClassLoto::dbConnection();

//Initiate the session
session_start();

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

/******** Actualizar los numeros agregados *********/
if(isset($_POST["numbers"])) {
    //Receive the numbers
    $numbers = $_POST["numbers"];
    $date = trim($_POST["date"]);

    //Ordenar los números
    sort($numbers);

    //Query to update the numbers
    $sql = "";

    //Create the query
    for($i = 0; $i < count($numbers); $i++) {
        $sql .= "UPDATE `numbers` SET `number` = " . $numbers [$i] . " WHERE `position` = " . $i + 1 . " AND `date` = '" . $date . "';"; 
    }
    
    //Execute the query
    try {
        $conn -> begin_transaction();
        $conn -> multi_query($sql);
        $conn -> commit();

        $_SESSION ["message"] = "Números actualizados correctamente";
        $_SESSION ["message-alert"] = "success";

        header('Location: ' . root . 'loto/agregar');
        exit;
    } catch (Exception $e) {
        $conn -> rollback();
        $_SESSION ["message"] = "Error al actualizar los números";
        $_SESSION ["message-alert"] = "danger";

        header('Location: ' . root . 'loto/agregar');
        exit;
    }
}
?>