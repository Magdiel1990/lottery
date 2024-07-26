<?php
require_once ("pages/partials/head.php");

//Reset the database
if(isset($_GET["reset"])) {
//Delete all numbers
    $sql = "DELETE FROM numbers";

    if($conn -> query($sql)) {
        $_SESSION ["message"] = "Todos los números han sido eliminados";
        $_SESSION ["message-alert"] = "success";

        header("Location: " . root);
        exit;      

    } else {
        $_SESSION ["message"] = "Error al eliminar números";
        $_SESSION ["message-alert"] = "danger";

        header("Location: " . root);
        exit;           
    }
}
?>