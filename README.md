# Projeto PHP com Docker e MySQL

Este projeto configura um ambiente de desenvolvimento utilizando Docker para rodar uma aplicação PHP com servidor Apache e um banco de dados MySQL. Além disso, inclui o PHPMyAdmin para facilitar a administração do banco de dados.

## Pré-requisitos

- [Docker](https://www.docker.com/get-started) instalado na sua máquina.
- [Docker Compose](https://docs.docker.com/compose/install/) para orquestrar os containers.

## Estrutura do Projeto

```bash
.
├── src/
│   └── index.php
│   └── assets
│   │   └── images
├── docker-compose.yml
├── Dockerfile
├── init.sql
└── README.md
```

- `src/`: Contém o código-fonte da aplicação PHP.
- `docker-compose.yml`: Define os serviços (PHP, Apache, MySQL e PHPMyAdmin).
- `Dockerfile`: Especifica a imagem personalizada do PHP com Apache.
- `init.sql`: Script SQL para inicializar o banco de dados.

## Configuração

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/CaiohAlvino/Trabalho-Docker.git
   cd Trabalho-Docker
   ```
   Verificar se a branch "main" que foi clonada pois ela é a certa

2. **Construir e iniciar os containers:**

   ```bash
   docker-compose up -d --build
   ```

3. **Acessar a aplicação:**

   Abra seu navegador e acesse `http://localhost:8080`. Você deverá ver a página inicial da sua aplicação PHP.

## Observações

- **Inicialização do Banco de Dados:** O script `init.sql` será executado automaticamente na primeira vez que o container do MySQL for iniciado, criando o banco de dados e as tabelas definidas.
- **Persistência de Dados:** Os dados do banco de dados são armazenados no volume `db_data`, garantindo que os dados sejam mantidos entre reinicializações dos containers.

## Contribuições

1. Faça um fork deste repositório.
2. Crie uma branch para sua feature (`git checkout -b feature/nome-da-feature`).
3. Realize as alterações desejadas e faça commit (`git commit -am 'Descrição das alterações'`).
4. Envie para a branch principal (`git push origin feature/nome-da-feature`).
5. Abra um Pull Request detalhando as alterações realizadas.
