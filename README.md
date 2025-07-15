# PHP Project with Docker and MySQL

This project sets up a development environment using Docker to run a PHP application with Apache server and a MySQL database. It also includes PHPMyAdmin to facilitate database management.

## Prerequisites

- [Docker](https://www.docker.com/get-started) installed on your machine.
- [Docker Compose](https://docs.docker.com/compose/install/) to orchestrate the containers.

## Project Structure

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

- `src/`: Contains the PHP application source code.
- `docker-compose.yml`: Defines the services (PHP, Apache, MySQL, and PHPMyAdmin).
- `Dockerfile`: Specifies the custom PHP with Apache image.
- `init.sql`: SQL script to initialize the database.

## Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/CaiohAlvino/Trabalho-Docker.git
   cd Trabalho-Docker
   ```
   Make sure you are on the "main" branch, as it is the correct one.

2. **Build and start the containers:**

   ```bash
   docker-compose up -d --build
   ```

3. **Access the application:**

   Open your browser and go to `http://localhost:8080`. You should see the home page of your PHP application.

## Notes

- **Database Initialization:** The `init.sql` script will be executed automatically the first time the MySQL container starts, creating the database and defined tables.
- **Data Persistence:** Database data is stored in the `db_data` volume, ensuring data is kept between container restarts.

## Contributions

1. Fork this repository.
2. Create a branch for your feature (`git checkout -b feature/feature-name`).
3. Make your changes and commit them (`git commit -am 'Description of changes'`).
4. Push to your branch (`git push origin feature/feature-name`).
5. Open a Pull Request detailing your changes.

## Improvements & Author's Note

This is my first project using Docker, and there is plenty of room for improvements. Future updates will include new features, optimizations, and best practices as I continue learning and evolving this project. Feedback and suggestions are welcome!
