# Symfony Project

## Prerequisites

Before you begin, ensure you have the following installed on your machine:

- PHP 8.0 or higher
- Composer
- Symfony CLI
- Node.js and npm (for managing frontend assets)
- A database server (e.g., MySQL, PostgreSQL)

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/lechatn/symfonyProject.git
    cd symfonyProject
    ```

2. Install PHP dependencies using Composer:

    ```bash
    composer install
    ```

3. Create a `.env` file and configure your database connection:

    ```dotenv
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
    ```

4. Create the database and run migrations:

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

## Running the Project

1. Start the Symfony server:

    ```bash
    symfony server:start
    ```

2. Open your browser and navigate to:

    ```
    http://127.0.0.1:8000
    ```

## Additional Commands

- To stop the Symfony server:

    ```bash
    symfony server:stop
    ```

- To clear the cache:

    ```bash
    php bin/console cache:clear
    ```

- To run tests:

    ```bash
    php bin/phpunit
    ```
