# Folowup Backend 🚀

Backend API for **Folowup** – a simple lead management system for individual sales users and teams.

Built with:
- Laravel 12
- MySQL 8
- Laravel Sanctum
- Docker + Docker Compose

---

## 📦 Requirements

You only need:
- Docker
- Docker Compose (v2)

❌ No need to install PHP, Composer, MySQL locally.

---

## ⚙️ Installation & Setup

### 1️⃣ Clone the repository
```bash
git clone https://github.com/terminator15/folowup-backend.git
cd folowup-backend



cp .env.example .env

docker compose up --build

docker compose exec app php artisan db:seed
