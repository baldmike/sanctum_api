# What Is This?

This is a Laravel 8 API that performs CRUD operations on a Product object. It has a simple auth system that uses Laravel Sanctum tokens.

## How do i fire this baby up?

* clone project and cd into it
* `cd sanctum_api`
* install composer dependencies
* `composer install`
* copy the .env example file to a new .env 
* `cp .env.example .env`
* generate a new Laravel key 
* `php artisan key:generate`
* run migrations 
* `php artisan migrate`

* serve up a tasty hot API dish with `php artisan serve`

The project will be running at localhost:8000, but browser beware... This project has no front end, so you'll need a tool such as Postman or Insomnia to interact with it. 

## Routes

// unprotected routes
### POST /register
* Expected params: name, email, password, password_confirmation
### POST /login
* Expected params: email, password
### GET /products
### GET /products/{id}
### GET /products/search/{name}

// protected routes - these routes require a token which can be obtained with login
### POST /logout
### POST /products
### PUT /products/{id}
### DELETE /products/{id}

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
