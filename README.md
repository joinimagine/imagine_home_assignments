# Imagine Bookstore API

Welcome to the Imagine Bookstore API! This backend API is designed to power an online bookstore, allowing users to browse books, manage their shopping carts, and place orders. Built using Laravel and MySQL, it provides a robust and secure environment for your bookstore operations.

## Table of Contents

- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)
- [Error Handling](#error-handling)
- [Bonus Features](#bonus-features)
- [Contributing](#contributing)
- [License](#license)

## Getting Started

### Prerequisites

Before you begin, ensure you have the following installed on your machine:

- [PHP](https://www.php.net/manual/en/install.php)
- [Composer](https://getcomposer.org/download/)
- [MySQL](https://dev.mysql.com/downloads/)

### Installation

1. Clone the repository to your local machine:

```bash
git clone https://github.com/yourusername/imagine-bookstore-api.git
```

2. Navigate to the project directory:

```bash
cd imagine-bookstore-api
```

3. Install dependencies using Composer:

```bash
composer install
```

4. Create a copy of the `.env.example` file and name it `.env`. Update the database configuration with your MySQL credentials:

```bash
cp .env.example .env
```

5. Generate an application key:

```bash
php artisan key:generate
```

6. Run the database migrations and seed the database with sample data:

```bash
php artisan migrate --seed
```

7. Run the Seeders for each table by using the command:

```bash
php artisan db:seed <name_of_the_seeder>
```
   
8. Start the Laravel development server:

```bash
php artisan serve
```

The API is now running locally at `http://localhost:8000`.

## API Endpoints

The API provides the following endpoints:

- **GET /api/v1/books**: Retrieve a list of all books available in the store.
- **GET /api/v1/books/{id}**: Retrieve details of a specific book by its ID.
- **GET /api/v1/books/search**: Search for books by title, author, or genre.
- **GET /api/v1/cart**: Retrieve the user's shopping cart with a list of added books.
- **POST /api/v1/cart/add/{id}**: Add a book to the user's shopping cart.
- **DELETE /api/v1/cart/remove/{id}**: Remove a book from the user's shopping cart.
- **GET /api/v1/orders**: Retrieve a list of the user's previous orders.
- **POST /api/v1/orders/place**: Place a new order with the books currently in the user's shopping cart.

## Authentication

The API uses token-based authentication. To access protected endpoints, follow these steps:

1. Register a new user: **POST /api/v1/register**
2. Obtain an authentication token by logging in: **POST /api/v1/login**
3. Include the token in the Authorization header for subsequent requests.

## Error Handling

The API gracefully handles errors and provides informative feedback to the user. If an endpoint receives invalid input or encounters an issue, it will return an appropriate error response.

## Bonus Features

- Pagination is implemented for `/api/v1/books` and `/api/v1/orders` endpoints to limit the number of results returned per request.
- An admin role is available with special privileges to manage books (add, update, delete) and view all user orders.

## Contributing

If you would like to contribute to the project, please follow the [contribution guidelines](CONTRIBUTING.md).
