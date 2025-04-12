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
   git clone https://github.com/seuusuario/seu-repositorio.git
   cd seu-repositorio
   ```

2. **Crie o arquivo `init.sql`:**

   Na raiz do projeto, crie o arquivo `init.sql` com o script necessário para inicializar seu banco de dados. Por exemplo:

   ```sql
   CREATE DATABASE IF NOT EXISTS pokemon_db;
   USE pokemon_db;

   CREATE TABLE IF NOT EXISTS exemplo (
       id INT AUTO_INCREMENT PRIMARY KEY,
       nome VARCHAR(255) NOT NULL
   );

   INSERT INTO exemplo (nome) VALUES ('Teste');
   ```

3. **Crie o arquivo `Dockerfile`:**

   Na raiz do projeto, crie o arquivo `Dockerfile` com o seguinte conteúdo:

   ```dockerfile
   FROM php:8.0-apache
   RUN docker-php-ext-install pdo pdo_mysql
   COPY src/ /var/www/html/
   ```

4. **Configure o `docker-compose.yml`:**

   Na raiz do projeto, crie o arquivo `docker-compose.yml` com a seguinte configuração:

   ```yaml
   version: '3.8'
   services:
     web:
       build: .
       container_name: php_apache
       ports:
         - "8080:80"
       networks:
         - app_network
       depends_on:
         - db
     db:
       image: mysql:5.7
       container_name: mysql_db
       environment:
         MYSQL_ROOT_PASSWORD: root_password
         MYSQL_DATABASE: pokemon_db
         MYSQL_USER: db_user
         MYSQL_PASSWORD: db_password
       networks:
         - app_network
       volumes:
         - ./init.sql:/docker-entrypoint-initdb.d/init.sql
     phpmyadmin:
       image: phpmyadmin/phpmyadmin
       container_name: phpmyadmin
       environment:
         PMA_HOST: db
         MYSQL_ROOT_PASSWORD: root_password
       ports:
         - "8081:80"
       networks:
         - app_network
       depends_on:
         - db
   networks:
     app_network:
       driver: bridge
   ```

   Este arquivo define três serviços:

   - **web**: Servidor Apache com PHP.
   - **db**: Banco de dados MySQL.
   - **phpmyadmin**: Interface web para administração do MySQL.

## Instruções

1. **Construir e iniciar os containers:**

   ```bash
   docker-compose up -d --build
   ```

2. **Acessar a aplicação:**

   Abra seu navegador e acesse `http://localhost:8080`. Você deverá ver a página inicial da sua aplicação PHP.

3. **Acessar o PHPMyAdmin:**

   Para gerenciar o banco de dados, acesse `http://localhost:8081` no navegador. Utilize as seguintes credenciais:

   - **Servidor:** `db`
   - **Usuário:** `root`
   - **Senha:** `root_password`

## Observações

- **Inicialização do Banco de Dados:** O script `init.sql` será executado automaticamente na primeira vez que o container do MySQL for iniciado, criando o banco de dados e as tabelas definidas.
- **Persistência de Dados:** Os dados do banco de dados são armazenados no volume `db_data`, garantindo que os dados sejam mantidos entre reinicializações dos containers.

## Contribuições

1. Faça um fork deste repositório.
2. Crie uma branch para sua feature (`git checkout -b feature/nome-da-feature`).
3. Realize as alterações desejadas e faça commit (`git commit -am 'Descrição das alterações'`).
4. Envie para a branch principal (`git push origin feature/nome-da-feature`).
5. Abra um Pull Request detalhando as alterações realizadas.

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE). 