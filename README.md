# Antique-Bid Backend

This is the backend for the Auction Application built with Laravel. It provides RESTful API endpoints for managing users, items, bids, auto-bids, and notifications.

## Requirements

- PHP 8.2+
- Composer
- MySQL or other supported databases
- Laravel 11.x

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/your-username/auction-app.git
    cd auction-app
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Set up environment variables:**

    Copy the example environment file and update it with your specific settings.

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database credentials and other necessary configurations:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=auction
    DB_USERNAME=root
    DB_PASSWORD=yourpassword
    ```

4. **Generate application key:**

    ```bash
    php artisan key:generate
    ```

5. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

6. **Seed the database:**

    The project includes a seeder to create default admin and user accounts.

    ```bash
    php artisan db:seed
    ```

7. **Run the application:**

    Start the local development server:

    ```bash
    php artisan serve
    ```

    The application will be accessible at `http://localhost:8000`.

## Usage

### User Roles

- **Admin:** Has full access to manage items.
- **Regular User:** Can view items and participate in bidding.

## Seeding the Database

The database seeder creates two roles: Admin and Regular users.

- **Admin Credentials:**
  - `admin1@example.com / admin1`
  - `admin2@example.com / admin2`

- **Regular User Credentials:**
  - `user1@example.com / user1`
  - `user2@example.com / user2`
