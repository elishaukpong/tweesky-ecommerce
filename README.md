# About Tweesky

Tweesky is an elegant lean-version of an e-commerce platform which gives users the ability to add their 
favorite products to their wishlist for later purchase.

## Project Details

This Project supports:
- User Authentication
- Product CRUD
- Wishlist CRUD
- Filtering
- Authorization
- Unit + Feature Tests


## API Documentation

There are two options to go with regards API Endpoints documentation for the project.
In the root folder, I have added a postman collection file which you can import into
your local postman instance or you can utilize the publicly available postman documentation endpoint 
(Postman might be down here and there, so best to import the collection file):


[Available Here](https://documenter.getpostman.com/view/37632424/2sB2qWGimG).

## Setup Instructions 

To set this application you can use either an instance of mysql or SQLite - I used sqlite in the development process.

### Assumptions
- I am assuming that you have a working `PHP8.2` installation on your machine
- I am assuming that you have `SQLite` working on your laptop


### Setting Up
- Clone the repo to your local machine
- Run `composer install` to install laravel default dependencies
- Run `cp .env.example .env` to copy in a working environment file.
- Run `php artisan key:generate` to generate an app key for the project.
- In your database folder, in the root directory, create a file: `database.sqlite`
- The `env.example` file is already set to sqlite but just to confirm, check that `DB_CONNECTION=sqlite` 
is same in your env, if not, update to match.
- Run migrations and seeder `php artisan migrate --seed`
- Run `php artisan serve` to start up your application.
- Now you can go over to the API documentation on Postman
- switch the value of the environment variable `TWEEKSY_BASE_URL` to point to the url of the project gotten from 
running `php artisan serve`

### Testing
To test the project, run `php artisan test`

### Errata 
Everything should go smoothly, but if it doesn't - create an issue or shoot me an email: ishukpong418@gmail.com
