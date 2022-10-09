
# Serkan Celik IDE - Challenge

## Installation

Clone Project

```bash
  git clone https://github.com/serkancelik17/ide-challenge
```

Go to project directory

```bash
  cd ide-challange
```

Run Docker Compose

```bash
  docker-compose up
```

Create database

```bash
  php bin/console doctrine:database:create
```

Create database schema

```bash
php bin/console  doctrine:schema:create
```

Create doctrine schema for Demo

```bash
php bin/console doctrine:fixtures:load
```

# API Usage

### Orders

```http
  GET /orders
```

#### Sample JSON
```json
[
  {
    "id": 1,
    "customerId": 16,
    "items": [
      {
        "productId": 6,
        "quantity": 2,
        "unitPrice": 40,
        "total": 80
      },
      ...
    ],
    "total": 745
  }
]
```

### Order Create

```http
  POST /orders
```
#### Sample Body
```json
    {
        "customer_id": 361,
        "items": [
            {
                "product_id": 161,
                "quantity": 10,
                "unit_price": "11.28",
                "total": "112.80"
            }
        ],
        "total": "112.80"
    }
```
#### Sample Response
```json
  {
  "status":true,
  "message":"Created new order successfully with id 131"
  }
```

### Order Delete

```http
  DELETE /order/{order}
```

#### Sample JSON
```json
{
"status":  true, 
"message": "Deleted order successfully with id 1"
}
```
# TESTING
#### If you want create a test database and filled with textures (Optional)

Create test database
```bash
  php bin/console doctrine:database:create --env=test
```

Create test database schema

```bash
php bin/console  doctrine:schema:create --env=test
```

Create test doctrine schema for Demo

```bash
php bin/console doctrine:fixtures:load --env=test
```
### Just run
```bash
vendor/bin/phpunit
```