<?php
// API para listar todos os Pokémon
header('Content-Type: application/json');

try {
    // Conectar ao banco de dados
    $pdo = require_once '../config/database.php';

    // Consultar todos os Pokémon
    $stmt = $pdo->query('SELECT * FROM pokemons ORDER BY pokemon_id ASC');
    $pokemons = $stmt->fetchAll();

    // Retornar como JSON
    echo json_encode($pokemons);
} catch (PDOException $e) {
    // Em caso de erro, retornar mensagem de erro
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao listar Pokémon: ' . $e->getMessage()]);
}
