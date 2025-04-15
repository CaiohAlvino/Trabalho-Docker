<?php
// API para buscar Pokémon por nome ou tipo
header('Content-Type: application/json');

try {
    // Conectar ao banco de dados
    $pdo = require_once '../config/Database.php';

    // Verificar se o termo de busca foi fornecido
    $searchTerm = isset($_GET['q']) ? $_GET['q'] : '';

    if (empty($searchTerm)) {
        // Se não houver termo de busca, retornar todos os Pokémon
        $stmt = $pdo->query('SELECT * FROM pokemons ORDER BY pokemon_id ASC');
    } else {
        // Buscar por nome ou tipo
        $stmt = $pdo->prepare('SELECT * FROM pokemons WHERE nome LIKE ? OR tipo LIKE ? ORDER BY pokemon_id ASC');
        $searchParam = "%{$searchTerm}%";
        $stmt->execute([$searchParam, $searchParam]);
    }

    $pokemons = $stmt->fetchAll();

    // Retornar como JSON
    echo json_encode($pokemons);
} catch (PDOException $e) {
    // Em caso de erro, retornar mensagem de erro
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar Pokémon: ' . $e->getMessage()]);
}
