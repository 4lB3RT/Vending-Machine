# Laravel Example

A modern Laravel starter template to kick off your next PHP project with best practices, batteries included.

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.4%2B-blue?style=flat-square)
![Docker](https://img.shields.io/badge/Docker-ready-blue?style=flat-square)
![License](https://img.shields.io/github/license/albertgarcia4/laravel-example?style=flat-square)

---

## 🚀 Features

- Laravel 12.x, PHP 8.4+
- Pre-configured Docker containers (PHP, Nginx, MySQL, Redis)
- Ready-to-use scripts for setup, development, and testing
- Out-of-the-box support for Vite, Sail, Pint, Pail, and more
- Opinionated code style and testing tools
- Custom Docker commands for `artisan` and `composer`
- Ready-to-use `.env` and SQLite database setup

---

## 🐳 Docker Quick Start

```bash
# Copy .env and set your environment variables
cp .env.example .env

# Start containers (from the docker folder)
docker compose -f docker/docker-compose.yml up -d

# Run artisan or composer inside the container
./docker/commands/artisan.sh migrate
./docker/commands/composer.sh install
```

---

## 🛠️ Local Development (No Docker)

```bash
git clone https://github.com/albertgarcia4/laravel-example.git my-app
cd my-app

# Setup project (composer, npm, env, key, migrate, build)
composer run setup

# Start local development (PHP server, queue, logs, Vite)
composer run dev
```

---

## 🧩 Scripts

- `composer run setup` — Full project bootstrap
- `composer run dev` — Start all dev services concurrently
- `composer run test` — Run tests

---

## 📝 Customization

- Update `composer.json` for dependencies and scripts
- Edit `.env` for environment variables
- Add your own packages via Composer or NPM
- Tweak Docker configuration in `docker/`

---

## 🤝 Contributing

Feel free to fork, open issues, or submit PRs to improve this template!

---

## 📄 License

This project is open-sourced under the [MIT license](LICENSE).

