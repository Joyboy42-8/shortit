<?php 

try {
    $connexion = new PDO("pgsql:host=localhost;dbname=bitly", "root", "");
} 
catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}