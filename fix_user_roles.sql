-- Fix user roles yang tidak konsisten
-- Jalankan di SQLite: sqlite3 database/database.sqlite < fix_user_roles.sql

UPDATE users SET role = 'warga' WHERE email = 'ariqn@gmail.com';
UPDATE users SET role = 'rt' WHERE email = 'ariqns@gmail.com';
UPDATE users SET role = 'rw' WHERE email = 'ariqns280702@gmail.com';

-- Verifikasi hasil
SELECT id, email, name, role FROM users ORDER BY id;
