# SLIM API
Slim API is a Slim Framework API with Basic auth and some unit tests.

## Installation
Clone this repository.

```bash
git clone git@github.com:dgostin/slim_api.git
```
This will create a slim_api folder for the project. "cd" into the folder and install it.

```bash
composer install
```

## Put values in the .env file
```bash
API_USER="root"
API_PASS="abc123"

DB_HOST="localhost"
DB_USER="root"
DB_PASS="abc123"
DB_NAME="slim"
```
You can use the default values for testing.  
Normally you would put the .env in the .gitignore for security purposes

## Set up your local MySQL database

Use either the MySQL CLI or phpMyAdmin.  Use the values specified in the .env for the database.

Run the db.sql script in db/db.sql.  This will create the necessary "users" and "transactions" tables.
You can optionally import the users.csv file for test data.


## Run the PHP web server
Use the public dir as the "web root".

```bash
php -S localhost:8888 -t public
```

# Valid endpoints
* GET /users: Retrieves a list of all users and their data.
* POST /users: Creates a new user with an initial points balance of 0.
* POST /users/{id}/earn: Earns points for a user. The request should include the number of points to earn and a description of the transaction.
* POST /users/{id}/redeem: Redeems points for a user. The request should include the number of points to redeem and a description of the transaction.
* DELETE /users/{id}: Deletes a user by their ID.


## Testing
You can test the API with curl bash scripts or the PHP Unit tests.

### Bash scripts
```bash
#!/bin/bash

# Test GET all users endpoint
curl -v -u "root:abc123" http://localhost:8888/users

# Test POST endpoint for adding a user
curl -v -X POST http://localhost:8888/users \
   -H "Content-Type: application/json" \
   -d '{"name": "Joe Jones", "email": "joejones@test.com", "points_balance": 0}' \
   --user "root:abc123"

# Test DELETE endpoint for deleting a user
curl -v -X DELETE http://localhost:8888/users/6 \
   --user "root:abc123"
```

### PHP Unit tests
You can run these tests from the project directory with:

```bash
vendor/bin/phpunit tests/
```

## License

[MIT](https://choosealicense.com/licenses/mit/)