<?php require("pages/template/topo.php") ?>

<div class="container">
    <div class="header text-center">
        <h1>Gerenciador de Times Pokémon</h1>
        <p>Selecione 6 Pokémon da 1ª geração para formar seu time</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Criar Novo Time
                </div>
                <div class="card-body">
                    <form id="teamForm" action="executar/time/salvar_time.php" method="post">
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

        <!-- Card para times salvos -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    Times Salvos
                </div>
                <div class="card-body">
                    <div id="savedTeams">
                        <!-- Os times salvos serão carregados automaticamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card para a lista de Pokémon -->
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

<?php require("pages/template/rodape.php") ?>