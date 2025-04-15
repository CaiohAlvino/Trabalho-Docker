$(document).ready(function() {
    // Carregar a lista de Pokémon
    $.ajax({
        url: 'executar/listar_pokemons.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            loadPokemonList(data);
        },
        error: function() {
            $('#pokemonList').html('<div class="col-12 text-center"><p class="text-danger">Erro ao carregar Pokémon.</p></div>');
        }
    });

    // Carregar times salvos automaticamente
    loadSavedTeams();

    // Buscar Pokémon
    $('#btnSearch').click(function() {
        const searchTerm = $('#searchPokemon').val().toLowerCase();
        $.ajax({
            url: 'executar/buscar_pokemon.php',
            type: 'GET',
            dataType: 'json',
            data: { q: searchTerm },
            success: function(data) {
                loadPokemonList(data);
            },
            error: function() {
                $('#pokemonList').html('<div class="col-12 text-center"><p class="text-danger">Erro na busca.</p></div>');
            }
        });
    });

    // Função para carregar times salvos
    function loadSavedTeams() {
        $.ajax({
            url: 'executar/time/listar_times.php',
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
                                        <img src="assets/images/${pokemon.imagem}.png" alt="${pokemon.nome}" style="max-height: 100px;">
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
    }

    // Função para carregar a lista de Pokémon
    function loadPokemonList(pokemons) {
        let html = '';
        pokemons.forEach(pokemon => {
            html += `
                <div class="col-md-2 col-sm-3 col-4 mb-3">
                    <div class="pokemon-card text-center" data-id="${pokemon.id}" data-name="${pokemon.nome}">
                        <img src="assets/images/${pokemon.imagem}.png" alt="${pokemon.nome}" style="max-height: 100px;">
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
            const imgSrc = $(this).find('img').attr('src');
            const isSelected = $(this).hasClass('selected');
            const selectedCount = $('.pokemon-card.selected').length;

            if (!isSelected && selectedCount < 6) {
                $(this).addClass('selected');
                addToSelectedList(id, name, imgSrc);
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
            <div class="col-2 mb-2" data-id="${id}">
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
        $('.remove-pokemon').off('click').on('click', function() {
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
        $('#saveTeam').prop('disabled', count !== 6);
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
                    loadSavedTeams(); // Atualiza os times salvos automaticamente
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
