# What Is This?

This is a Laravel 8 API that performs CRUD operations on a Product object. It has a simple auth system that uses Laravel Sanctum tokens.

## How do i fire this baby up?

* make a database named 'sanctum_api'

* clone project and cd into it
* `cd sanctum_api`
* install composer dependencies
* `composer install`
* copy the .env example file to a new .env & set db creds
* `cp .env.example .env`
* generate a new Laravel key 
* `php artisan key:generate`
* run migrations
* `php artisan migrate`
* seed the database
* `php artisan db:seed`

* serve up a tasty hot API dish with `php artisan serve`

The project will be running at localhost:8000, but browser beware... This project has no front end, so you'll need a tool such as Postman or Insomnia to interact with it. 

## Tests

The `UserTest` will create a test user, `test@example.com` with a password of `password`

`./vendor/bin/phpunit`

## Routes

### unprotected routes
#### POST /api/register
* Expected params: name, email, password, password_confirmation
#### POST /api/login
* Expected params: email, password
#### GET /api/products
#### GET /api/products/{id}
#### GET /api/products/search/{name}

### protected routes - these routes require a token which can be obtained with login
#### POST /api/logout
#### POST /api/products
* Expected params: name, description, price
#### PUT /api/products/{id}
#### DELETE /api/products/{id}

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
