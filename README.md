## Setting Up and Running Laravel Auth API App
This is a simple Laravel-based API authentication system built using Laravel Sanctum and Laravel Breeze. It provides endpoints for user registration, login, logout, forgot password, reset password and email verification

### Features

- User registration, login and logout.
- Token-based authentication with Laravel Sanctum.
- Email verification.
- Full API support with JSON responses.
- Forgot Password & Reset Password

### Steps to Clone and Set Up this Project
Follow these steps to clone the repo and set up the project:

1. Clone the Repository
Run the following command to clone the project from GitHub:
```shell
git clone git@github.com:RMike1/Api-App.git
```

2. Navigate to the Project Directory
```shell
cd api-app
```

3. Install PHP dependencies:
```shell
composer install
```

4. Create .env file
Copy the .env.example file to create a .env file and .env.testing (For testing):
```shell
cp .env.example .env
```
```shell
cp .env.example .env.testing
```

5. Generate Application Key
```shell
php artisan key:generate
```

6. Set up the database
Run migrations:
```shell
php artisan migrate
```

7. Start the server
```shell
php artisan serve
```

### Usage
- Register a new user with Postman or any tool for testing API use this end-point:
```shell
 POST /api/register
```

- Login to get token:
```shell
 POST /api/login
```

- Logout
```shell
 POST /api/logout
```

### Testing
Run the following to execute the tests:
```shell
 php artisan test
```