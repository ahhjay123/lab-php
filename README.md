# Stockroom – Simple Inventory System (PHP + MySQL + Bootstrap 5)

Features: login, product CRUD, search/filter, categories, low-stock dashboard.

## Setup (XAMPP / WAMP / Laragon)
1. Copy this folder into `htdocs` (XAMPP) or `www` (WAMP/Laragon).
2. Start Apache and MySQL.
3. Import `database.sql` in phpMyAdmin (creates `inventory_db` with sample data).
4. Edit DB credentials in `config.php` if needed.
5. Open `http://localhost/inventory/` → "Create the first admin" → log in.

## Notes
- PDO prepared statements, password_hash, CSRF tokens, output escaping.
- Bootstrap and icons load from CDN (needs internet), no build step.
- Register page closes itself after the first account is created.
