<?php 

try {
    $connexion = new PDO("mysql:host=localhost;dbname=bitly;charset=utf8", "root", "");
} 
catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}