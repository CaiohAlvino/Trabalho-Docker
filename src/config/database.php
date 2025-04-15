<?php
// Configuração do banco de dados
$host = 'localhost';
$db_name = 'db_pokemon';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    return $pdo;
} catch (PDOException $e) {
    // Em ambiente de produção, isso seria registrado em um arquivo de log
    die('Erro de conexão: ' . $e->getMessage());
}
