<?php
$dsn = "mysql:host=localhost;dbname=login;charset=UTF8";

try {
    $pdo = new PDO($dsn, "kel3", "12345678");
}  catch (PDOException $e) {                
    echo $e->getMessage();
}
?>

