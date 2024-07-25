<?php
session_start();

class DatabaseClass {
    static $hostname = "localhost:3306";
    static $username = "root";
    static $password = "123456";
    static $database = "lottery";

//Connection to the database.
    public static function dbConnection(){
        $conn = new mysqli(self::$hostname, self::$username, self::$password, self::$database);
        
// Check connection
        if ($conn->connect_error) {
            die("Error en conexión: " . $conn->connect_error);
        }
        return $conn;
    }
}

class DatabaseClassLoto {
    static $hostname = "localhost:3306";
    static $username = "root";
    static $password = "123456";
    static $database = "Loto";

//Connection to the database.
    public static function dbConnection(){
        $conn = new mysqli(self::$hostname, self::$username, self::$password, self::$database);
        
// Check connection
        if ($conn->connect_error) {
            die("Error en conexión: " . $conn->connect_error);
        }
        return $conn;
    }
}

class DatabaseClassKino {
    static $hostname = "localhost:3306";
    static $username = "root";
    static $password = "123456";
    static $database = "kinotv";

//Connection to the database.
    public static function dbConnection(){
        $conn = new mysqli(self::$hostname, self::$username, self::$password, self::$database);
        
// Check connection
        if ($conn->connect_error) {
            die("Error en conexión: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>