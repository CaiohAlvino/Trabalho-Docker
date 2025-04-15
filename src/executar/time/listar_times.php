<?php
// API para listar todos os times com seus Pokémon
header('Content-Type: application/json');

try {
    // Conectar ao banco de dados
    $pdo = require_once '../../config/Database.php';

    // Consultar todos os times
    $stmt = $pdo->query('SELECT * FROM times ORDER BY data_criacao DESC');
    $times = $stmt->fetchAll();

    // Para cada time, buscar os Pokémon associados
    foreach ($times as &$time) {
        $stmt = $pdo->prepare('
            SELECT p.* 
            FROM pokemons p
            JOIN time_pokemon tp ON p.id = tp.pokemon_id
            WHERE tp.time_id = ?
            ORDER BY p.pokemon_id ASC
        ');
        $stmt->execute([$time['id']]);
        $time['pokemons'] = $stmt->fetchAll();
    }

    // Retornar como JSON
    echo json_encode($times);
} catch (PDOException $e) {
    // Em caso de erro, retornar mensagem de erro
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao listar times: ' . $e->getMessage()]);
}
