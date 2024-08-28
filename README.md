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
    cd antique-bid-backend
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Set up environment variables:**

    Update the `.env` file with your database credentials and other necessary configurations:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=auction
    DB_USERNAME=root
    DB_PASSWORD=yourpassword
    ```

    The application uses a mailer to send notifications to users, such as bid alerts and auction results. Configure the mailer settings in the .env file:

    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io   # Replace with your SMTP server
    MAIL_PORT=2525               # Replace with your SMTP port
    MAIL_USERNAME=your_username  # Replace with your SMTP username
    MAIL_PASSWORD=your_password  # Replace with your SMTP password
    MAIL_ENCRYPTION=tls          # Use 'tls' or 'ssl' depending on your mail server
    MAIL_FROM_ADDRESS=no-reply@yourdomain.com  # Replace with your sender address
    MAIL_FROM_NAME="Antique Bid"  # Replace with your desired sender name
    ```

    The application uses Pusher to handle real-time notifications and updates, such as broadcasting bid updates to users in real-time. Configure Pusher settings in the .env file:

    ```env
    BROADCAST_CONNECTION=pusher
    BROADCAST_DRIVER=pusher
    PUSHER_APP_ID=your_app_id            # Replace with your Pusher App ID
    PUSHER_APP_KEY=your_app_key          # Replace with your Pusher App Key
    PUSHER_APP_SECRET=your_app_secret    # Replace with your Pusher App Secret
    ```

    You can simply copy the .env.example file as it already includes default configurations credential for the database, mailer, and Pusher.

   ```bash
    cp .env.example .env
    ```


5. **Generate application key:**

    ```bash
    php artisan key:generate
    ```

6. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

7. **Seed the database:**

    The project includes a seeder to create default admin and user accounts.

    ```bash
    php artisan db:seed
    ```

8. **Link the Storage:**

    ```bash
    php artisan storage:link
    ```

9. **Run the application:**

    Start the local development server:

    ```bash
    php artisan serve
    ```

    The application will be accessible at `http://localhost:8000`.

    To ensure that background tasks such as processing bids, sending notifications, and handling auto-bids are executed properly, you also need to run the queue worker and scheduler         (The scheduler is used to automatically award the item to the highest bidder when the bidding deadline arrives):

    ```bash
    php artisan queue:work
    ```

    ```bash
    php artisan schedule:work
    ```


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
 
You can also register new users from the registration menu in the application. Make sure to use a valid email address during registration, as this will allow you to receive important email notifications, such as bid alerts and auction results.
