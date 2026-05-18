# Godlike Book Library API

REST API for tracking books in a library. Built with Laravel 13 and PHP 8.3.

## Requirements

- PHP 8.3 or newer
- [Composer](https://getcomposer.org/)
- SQLite extension for PHP (usually enabled by default)

## Installation

Clone the repository and install dependencies:

```bash
git clone https://github.com/Illya301a/godlike-book-library
cd godlike-book-library
composer install
```

## Environment setup

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

On Windows (PowerShell):

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

The project uses **SQLite**. In `.env` you should have:

```env
DB_CONNECTION=sqlite
```

Create an empty database file before running migrations:

```bash
# Linux / macOS
touch database/database.sqlite

# Windows (PowerShell)
New-Item -ItemType File -Path database\database.sqlite -Force
```

## Migrations

Run migrations to create database tables:

```bash
php artisan migrate
```

## Run the application

Start the development server:

```bash
php artisan serve
```

The API is available at `http://127.0.0.1:8000`.

## Run tests

```bash
php artisan test
```

Or:

```bash
composer test
```

Tests use an in-memory SQLite database (see `phpunit.xml`).

## API endpoints

Base URL: `http://127.0.0.1:8000/api`

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/books` | List all books |
| POST | `/books` | Create a book |
| GET | `/books/{id}` | Get one book |
| PATCH | `/books/{id}` | Update a book (partial) |
| DELETE | `/books/{id}` | Delete a book |

### Book fields (JSON)

| Field | Type | Required on create |
|-------|------|--------------------|
| `title` | string | yes |
| `publisher` | string | yes |
| `author` | string | yes |
| `genre` | string | yes |
| `publication_date` | date (`YYYY-MM-DD`) | yes |
| `word_count` | integer | yes |
| `price_usd` | number | yes |

### Example: create a book

**POST** `/api/books`

```json
{
  "title": "Tokyo Ghoul",
  "publisher": "Shueisha",
  "author": "Sui Ishida",
  "genre": "Drama",
  "publication_date": "2011-09-08",
  "word_count": 50000,
  "price_usd": 10.99
}
```

Success response: **201 Created** with the book object (including `id`).

### HTTP status codes

| Code | Meaning |
|------|---------|
| 200 | OK |
| 201 | Created |
| 204 | No content (successful delete) |
| 404 | Book not found |
| 422 | Validation error |

## Project structure (main parts)

```
app/Http/Controllers/BookController.php   # API logic
app/Models/Book.php                       # Book model
database/migrations/                      # Database schema
routes/api.php                            # API routes
tests/Feature/BookApiTest.php             # Feature tests
```

## License

MIT
