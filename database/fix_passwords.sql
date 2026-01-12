-- ============================================================
-- إصلاح كلمات المرور في قاعدة البيانات
-- Fix Passwords in Database
-- ============================================================
-- 
-- الاستخدام: استورد هذا الملف بعد استيراد full_database_fresh.sql
-- Usage: Import this file after importing full_database_fresh.sql
-- 
-- mysql -u [username] -p [database_name] < fix_passwords.sql
-- 
-- ============================================================

-- تحديث كلمة مرور Admin
UPDATE `users` 
SET `password` = '$2y$12$CEeeBPFzGSRHFQrEF6ewhO4NBI6Qf.CrRK0j2zZKKnBD0AmOLvna6'
WHERE `email` = 'admin@gmail.com';
-- كلمة المرور: 100200300

-- تحديث كلمة مرور Dr. Alaa
UPDATE `users` 
SET `password` = '$2y$12$4UEzGH6LHwW/tMxchR9J3et5u.fErmb0kWGLyh8ygLEzkgW/txxDS'
WHERE `email` = 'alaa@almyzan.ps';
-- كلمة المرور: password123

-- تحديث كلمة مرور Dr. Tariq
UPDATE `users` 
SET `password` = '$2y$12$4UEzGH6LHwW/tMxchR9J3et5u.fErmb0kWGLyh8ygLEzkgW/txxDS'
WHERE `email` = 'tariq@almyzan.ps';
-- كلمة المرور: password123

-- ============================================================
-- ✅ تم التحديث!
-- ============================================================
-- 
-- معلومات تسجيل الدخول:
-- - Admin: admin@gmail.com / 100200300
-- - Dr. Alaa: alaa@almyzan.ps / password123
-- - Dr. Tariq: tariq@almyzan.ps / password123
-- 
-- ============================================================
