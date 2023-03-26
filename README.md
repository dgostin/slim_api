# SLIM API

Slim API is a Slim Framework API with Basic auth and some unit tests.

## Installation
Use either the MySQL CLI or phpMyAdmin

Create a MySQL localhost database.  The database configuration details go in the .env file

```bash
DB_HOST="localhost"
DB_USER="root"
DB_PASS="abc123"
DB_NAME="slim"
```

Run the db.sql script in db/db.sql.  This will create the necessary "users" and "transactions" tables.

Clone this repository.

```bash
git clone git@github.com:dgostin/slim_api.git
```
This will create a slim_api folder for the project. "cd" into the folder and install it.

```bash
composer install
```

Run the php web server, using the public dir as the "web root".

```bash
php -S localhost:8888 -t public
```

## Testing

You can test the API with curl bash scripts.

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

You can also test the endpoints with the built-in PHP unit tests.  You can run these tests from the project directory with:

```bash
vendor/bin/phpunit tests/
```

## License

[MIT](https://choosealicense.com/licenses/mit/)