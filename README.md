# QR Code Generation System

This is a simple QR code generation system built with PHP 8.4 (OOP), MySQL, and Bootstrap 5.3.

## Features

- Generate QR codes for any text content.
- Guest users can generate temporary QR codes that expire in 24 hours.
- Registered users can generate permanent QR codes.
- User registration and login.
- Password recovery with a secure token.
- A dashboard for registered users to manage their QR codes.

## Installation

1. Clone the repository.
2. Create a MySQL database and import the `app/config/database.sql` file.
3. Create an `app/config/.env` file with your database credentials. See `app/config/.env.example` for an example.
4. Run `composer install` to install the dependencies.
5. Point your web server to the `public` directory.

## Usage

- Visit the home page to generate a QR code.
- Register for an account to save your QR codes permanently.
- Log in to your account to view and manage your QR codes.
- If you forget your password, you can use the password recovery feature to reset it.

## Database Structure

The database structure is defined in the `app/config/database.sql` file. It consists of three tables:

- `users`: Stores user information.
- `password_resets`: Stores password reset tokens.
- `qrcodes`: Stores QR code data.

## Dependencies

- [endroid/qr-code](https://github.com/endroid/qr-code) (used via goqr.me API)
- [Bootstrap](https://getbootstrap.com/)
