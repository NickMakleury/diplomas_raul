<?php
// backend/database.php

$host = 'localhost';
$dbname = 'diplomas_raul';
$user = 'root'; // Padrão do XAMPP
$pass = '';     // Padrão do XAMPP é sem senha

try {
    // Cria a conexão segura com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    // Configura o PDO para jogar erros na tela se algo der errado (ótimo para programar)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Se der erro de conexão, ele avisa e para o site
    die(json_encode(["erro" => "Falha na conexão com o banco de dados: " . $e->getMessage()]));
}
?>