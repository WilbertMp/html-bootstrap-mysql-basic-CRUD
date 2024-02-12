<?php
$hostname = "localhost";
$username = "id21794190_wilbert";
$password = "wilbert1A#";
$db_name = "id21794190_gaskuytopupstore";
$port = 3306;

try {
    $db_connection = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
} catch (PDOException $e) {
    die($e->getMessage());
}
