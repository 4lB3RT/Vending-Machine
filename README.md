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

## 🚀 How to Run

### Using Docker Compose
```
docker compose build --no-cache
docker compose up -d
```

### Laravel setUp
```
./docker/commands/composer install
./docker/commands/artisan migrate
```

---

## 📋 Requirements
- PHP >= 8.0
- Composer
- Docker (optional)
