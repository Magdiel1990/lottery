<?php
session_start();

require("../classes/Database.Class.php");
require "../classes/Random.Generators.Class.php";

if(isset($_POST["numbers"]) && isset($_POST["amount"])) {
    $numbers = $_POST["numbers"];
    $amount = $_POST["amount"];
    
    $randomNumbers = new RandomGenerator(1, 31, 5, $amount);
    $randomNumbers = $randomNumbers -> randGen(); 

    $_SESSION ["lastnumbers"] = [];
    //Nuevo arreglo con los números en enteros
    $numbersInt = [];

    for($i = 0; $i < count($numbers); $i++) { 
        $_SESSION ["lastnumbers"] [] = $numbers[$i];
        $numbersInt [] = intval($numbers[$i]);
    }
    //Ordenar los números a probar
    sort($numbersInt);

    $_SESSION ["lastnumbers"] [6] = $amount;

    for($i = 0; $i < count($randomNumbers); $i++) {
        if($randomNumbers[$i] == $numbersInt) {
            $_SESSION ["message"] = "Números encontrado";
            $_SESSION ["message-alert"] = "success";

            header('Location: /lottery/pages/random_test.php');
            exit;
        } else {
            $_SESSION ["message"] = "Número no encontrado";
            $_SESSION ["message-alert"] = "danger";
            $_SESSION ["try"] += 1; 
            
            header('Location: /lottery/pages/random_test.php');
            exit;
        }
    } 
}
?>