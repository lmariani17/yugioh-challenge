# It's Yu-Gi-Oh! time
Welcome to the Yu-Gi-OH Rest API!

## Getting started

### Repository
To start using the API first clone the repository
- `git clone https://github.com/lmariani17/yugioh-challenge.git`

### Starting up the environment
To starting up and configure the local environment we are going to use Laravel Sail. It is a light-weight command-line interface for interacting with Laravel's default Docker development environment. So to set up our environment:
- Go to project folder in terminal.
- Create a temporary alias for the Laravel Sail execution -> `alias sail='bash vendor/bin/sail’`
- Run sail to start the app in the background -> `sail up -d`
- Run the migrations to build the database -> `sail artisan migrate`
- Run the seeders for the initial state of the database -> `sail artisan db:seed`

### Endpoints to test
Inside the root of the project you will find the following file `Insomnia-postman-collection.har`. It contains the collection of endpoints available for you to interact with the application. Import the file with Postman or Insomnia.

### To stop the application
When you are done testing you can stop the docker containers with the following Laravel Sail command.
- `sail down`

## Authentication
The authorization system to access endpoint information was developed with Laravel Sanctum. It's provides a featherweight authentication system token based APIs.
To generate an API token:
- Check or create a user. For this you can use the `Get users collection` or `Store user` endpoint.
- Use the endpoint `Create API token` and copy the generated token.
- Replace the example token with the one generated on the rest of the endpoints.
