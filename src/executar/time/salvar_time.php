<?php
// API para salvar um novo time
header('Content-Type: application/json');

try {
    // Verificar se os dados do formulário foram enviados
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido');
    }

    // Verificar se todos os campos necessários estão presentes
    if (!isset($_POST['teamName']) || !isset($_POST['pokemonIds'])) {
        throw new Exception('Dados incompletos');
    }

    $teamName = trim($_POST['teamName']);
    $pokemonIds = explode(',', $_POST['pokemonIds']);

    // Validar nome do time
    if (empty($teamName)) {
        throw new Exception('O nome do time é obrigatório');
    }

    // Validar quantidade de Pokémon
    if (count($pokemonIds) !== 6) {
        throw new Exception('O time deve ter exatamente 6 Pokémon');
    }

    // Conectar ao banco de dados
    $pdo = require_once '../config/Database.php';

    // Iniciar transação
    $pdo->beginTransaction();

    // Inserir o time
    $stmt = $pdo->prepare('INSERT INTO times (nome) VALUES (?)');
    $stmt->execute([$teamName]);
    $timeId = $pdo->lastInsertId();

    // Inserir a relação entre o time e os Pokémon
    $stmt = $pdo->prepare('INSERT INTO time_pokemon (time_id, pokemon_id) VALUES (?, ?)');

    foreach ($pokemonIds as $pokemonId) {
        $stmt->execute([$timeId, $pokemonId]);
    }

    // Confirmar transação
    $pdo->commit();

    // Retornar sucesso
    echo json_encode([
        'success' => true,
        'message' => 'Time salvo com sucesso!',
        'timeId' => $timeId
    ]);
} catch (Exception $e) {
    // Em caso de erro, reverter transação (se iniciada)
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Retornar erro
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
