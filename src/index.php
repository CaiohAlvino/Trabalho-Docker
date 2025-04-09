<?php
// Página inicial
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Times Pokémon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            padding-top: 20px;
        }

        .pokemon-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .pokemon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .pokemon-card img {
            max-width: 100%;
            height: auto;
        }

        .selected {
            border: 3px solid #007bff;
            background-color: #e6f2ff;
        }

        .header {
            background-color: #e51c23;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 0 0 10px 10px;
        }

        .btn-pokemon {
            background-color: #e51c23;
            border-color: #c41c22;
            color: white;
        }

        .btn-pokemon:hover {
            background-color: #c41c22;
            border-color: #a21c22;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header text-center">
            <h1>Gerenciador de Times Pokémon</h1>
            <p>Selecione 6 Pokémon da 1ª geração para formar seu time</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Criar Novo Time
                    </div>
                    <div class="card-body">
                        <form id="teamForm" action="api/salvar_time.php" method="post">
                            <div class="mb-3">
                                <label for="teamName" class="form-label">Nome do Time</label>
                                <input type="text" class="form-control" id="teamName" name="teamName" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pokémon Selecionados (<span id="selectedCount">0</span>/6)</label>
                                <div id="selectedPokemon" class="row">
                                    <!-- Pokémon selecionados aparecerão aqui -->
                                </div>
                                <input type="hidden" id="pokemonIds" name="pokemonIds">
                            </div>

                            <button type="submit" class="btn btn-pokemon" id="saveTeam" disabled>Salvar Time</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        Times Salvos
                    </div>
                    <div class="card-body">
                        <div id="savedTeams">
                            <!-- Os times salvos serão carregados com AJAX -->
                            <div class="text-center">
                                <button id="loadTeams" class="btn btn-pokemon">Carregar Times</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pokémon Disponíveis</h5>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" id="searchPokemon" class="form-control" placeholder="Buscar Pokémon...">
                            <button class="btn btn-outline-light" type="button" id="btnSearch">Buscar</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="pokemonList" class="row">
                            <!-- A lista de Pokémon será carregada com AJAX -->
                            <div class="text-center mt-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                                <p>Carregando Pokémon...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Carregar lista de Pokémon
            $.ajax({
                url: 'api/listar_pokemons.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    loadPokemonList(data);
                },
                error: function() {
                    $('#pokemonList').html('<div class="col-12 text-center"><p class="text-danger">Erro ao carregar Pokémon.</p></div>');
                }
            });

            // Buscar Pokémon
            $('#btnSearch').click(function() {
                const searchTerm = $('#searchPokemon').val().toLowerCase();

                $.ajax({
                    url: 'api/buscar_pokemon.php',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        q: searchTerm
                    },
                    success: function(data) {
                        loadPokemonList(data);
                    },
                    error: function() {
                        $('#pokemonList').html('<div class="col-12 text-center"><p class="text-danger">Erro na busca.</p></div>');
                    }
                });
            });

            // Carregar times salvos
            $('#loadTeams').click(function() {
                $.ajax({
                    url: 'api/listar_times.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let html = '';
                        if (data.length === 0) {
                            html = '<p class="text-center">Nenhum time salvo ainda.</p>';
                        } else {
                            data.forEach(team => {
                                html += `
                                    <div class="card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5>${team.nome}</h5>
                                            <small>Criado em: ${team.data_criacao}</small>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">`;

                                team.pokemons.forEach(pokemon => {
                                    html += `
                                        <div class="col-2">
                                            <div class="pokemon-card text-center">
                                                
                                                <p class="mt-2 mb-0">${pokemon.nome}</p>
                                                <small class="text-muted">${pokemon.tipo}</small>
                                            </div>
                                        </div>
                                    `;
                                });

                                html += `
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                        $('#savedTeams').html(html);
                    },
                    error: function() {
                        $('#savedTeams').html('<p class="text-danger">Erro ao carregar times.</p>');
                    }
                });
            });

            // Função para carregar a lista de Pokémon
            function loadPokemonList(pokemons) {
                let html = '';

                pokemons.forEach(pokemon => {
                    html += `
                        <div class="col-md-2 col-sm-3 col-4 mb-3">
                            <div class="pokemon-card text-center" data-id="${pokemon.id}" data-name="${pokemon.nome}">
                                
                                <p class="mt-2 mb-0">${pokemon.nome}</p>
                                <small class="text-muted">${pokemon.tipo}</small>
                            </div>
                        </div>
                    `;
                });

                $('#pokemonList').html(html);

                // Evento para selecionar/deselecionar Pokémon
                $('.pokemon-card').click(function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');
                    const isSelected = $(this).hasClass('selected');
                    const selectedCount = $('.pokemon-card.selected').length;

                    if (!isSelected && selectedCount < 6) {
                        $(this).addClass('selected');
                        addToSelectedList(id, name, $(this).find('img').attr('src'));
                    } else if (isSelected) {
                        $(this).removeClass('selected');
                        removeFromSelectedList(id);
                    }

                    updateSelectedCount();
                });
            }

            // Funções para gerenciar os Pokémon selecionados
            function addToSelectedList(id, name, imgSrc) {
                const html = `
                    <div class="col-4 mb-2" data-id="${id}">
                        <div class="pokemon-card text-center selected-pokemon">
                            <img src="${imgSrc}" alt="${name}" style="max-height: 50px;">
                            <p class="mt-1 mb-0 small">${name}</p>
                            <button type="button" class="btn btn-sm btn-danger mt-1 remove-pokemon" data-id="${id}">Remover</button>
                        </div>
                    </div>
                `;

                $('#selectedPokemon').append(html);
                updatePokemonIds();

                // Evento para remover Pokémon da seleção
                $('.remove-pokemon').click(function() {
                    const id = $(this).data('id');
                    removeFromSelectedList(id);
                    $(`.pokemon-card[data-id="${id}"]`).removeClass('selected');
                    updateSelectedCount();
                });
            }

            function removeFromSelectedList(id) {
                $(`#selectedPokemon div[data-id="${id}"]`).remove();
                updatePokemonIds();
            }

            function updateSelectedCount() {
                const count = $('#selectedPokemon > div').length;
                $('#selectedCount').text(count);

                if (count === 6) {
                    $('#saveTeam').prop('disabled', false);
                } else {
                    $('#saveTeam').prop('disabled', true);
                }
            }

            function updatePokemonIds() {
                const ids = [];
                $('#selectedPokemon > div').each(function() {
                    ids.push($(this).data('id'));
                });
                $('#pokemonIds').val(ids.join(','));
            }

            // Evento de envio do formulário
            $('#teamForm').submit(function(e) {
                e.preventDefault();

                const teamName = $('#teamName').val();
                const pokemonIds = $('#pokemonIds').val();

                if (!teamName) {
                    alert('Por favor, insira um nome para o time.');
                    return;
                }

                if (pokemonIds.split(',').length !== 6) {
                    alert('Selecione exatamente 6 Pokémon para o time.');
                    return;
                }

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: {
                        teamName: teamName,
                        pokemonIds: pokemonIds
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Time salvo com sucesso!');
                            $('#teamForm')[0].reset();
                            $('#selectedPokemon').empty();
                            $('.pokemon-card').removeClass('selected');
                            updateSelectedCount();
                            $('#loadTeams').click(); // Recarregar a lista de times
                        } else {
                            alert('Erro ao salvar time: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Erro ao salvar o time. Tente novamente.');
                    }
                });
            });
        });
    </script>
</body>

</html>