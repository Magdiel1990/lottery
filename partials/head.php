<?php
session_start();
error_reporting(0);

$result = $conn -> query("SELECT id FROM numbers LIMIT 1;");
$num_rows = $result -> num_rows;

if ($num_rows > 0) {   
    $_SESSION ["id"] = "ok";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lotería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
    <script>
//Avoid resubmission form  
        if (window.history.replaceState) { 
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <style>
        * {
            font-family: "Times New Roman"; 
            font-size: 1.2rem
        }
        td a {
            text-decoration: none;
        }
        span {
            font-size: 1.5rem
        }
    </style>
</head>
<body>