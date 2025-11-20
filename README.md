# 🛠️ Vending Machine

---

## 📖 Description
This challenge is about modeling a vending machine and the state it must maintain during operation. The machine works as expected: it receives money and dispenses products. The candidate decides how the actions on the machine are managed.

---

## 💰 Accepted Coins
- $0.05
- $0.10
- $0.25
- $1.00

---

## 🥤 Available Products
| Product | Price |
|---------|-------|
| Water   | $0.65 |
| Juice   | $1.00 |
| Soda    | $1.50 |

Each product has a selector, price, and available quantity.

---

## 🔄 Valid Actions
- Insert coin: $0.05, $0.10, $0.25, $1.00
- Return Coin: returns all inserted coins
- GET Water, GET Juice, GET Soda: select product
- SERVICE: service personnel set available change and product quantities

---

## 📦 Machine Responses
- $0.05, $0.10, $0.25: coin return
- Water, Juice, Soda: dispense product

---

## 🗃️ State Tracking
- Available products: quantity, price, and selector
- Available change: number of coins
- Currently inserted money

---

## 🧑‍💻 Usage Examples

**1. Buy Soda with exact change**
```
1, 0.25, 0.25, GET-SODA -> SODA
```

**2. Return coins before purchase**
```
0.10, 0.10, RETURN-COIN -> 0.10, 0.10
```

**3. Buy Water without exact change**
```
1, GET-WATER -> WATER, 0.25, 0.10
```

---

## ⚙️ Technical Considerations
- Language: PHP
- Solution with Dockerfile or docker-compose is highly appreciated
- Adding tests is a plus

---


# Vending Machine API Instructions

1. Go to the `docker` folder:
   ```sh
   cd docker
   ```
2. Build the containers (no cache):
   ```sh
   docker compose build --no-cache
   ```
3. Start the containers in detached mode:
   ```sh
   docker compose up -d
   ```
4. Laravel setUp:
   ```sh
   ./docker/commands/composer.sh install
   ./docker/commands/artisan.sh migrate
   ```

## Testing

- To run integration tests:
  ```sh
  ./docker/commands/test-integration.sh
  ```
- To run unit tests:
  ```sh
  ./docker/commands/composer.sh test:unit
  ```
- To run PHPStan static analysis:
  ```sh
  ./docker/commands/composer.sh phpstan
  ```
- To run PHP CS Fixer (code style):
  ```sh
  ./docker/commands/composer.sh cs-fix
  ```

The API and all dependencies will be available and ready for use.

---

## 📋 Requirements
- PHP >= 8.0
- Composer
- Docker (optional)

