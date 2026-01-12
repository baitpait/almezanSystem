-- ============================================================
-- إصلاح الصلاحيات والأدوار للمستخدم Admin
-- Fix Permissions and Roles for Admin User
-- ============================================================
-- 
-- الاستخدام: استورد هذا الملف بعد استيراد full_database_fresh.sql
-- Usage: Import this file after importing full_database_fresh.sql
-- 
-- mysql -u [username] -p [database_name] < fix_permissions.sql
-- 
-- ============================================================

-- حذف الصلاحيات القديمة (إذا كانت موجودة)
DELETE FROM `permissions` WHERE `name` LIKE 'view-%' OR `name` LIKE 'create-%' OR `name` LIKE 'edit-%' OR `name` LIKE 'delete-%' OR `name` LIKE 'manage-%';

-- إنشاء الصلاحيات الصحيحة (مع نقطة)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
-- Patients
(1, 'view.patients', 'web', NOW(), NOW()),
(2, 'create.patients', 'web', NOW(), NOW()),
(3, 'edit.patients', 'web', NOW(), NOW()),
(4, 'delete.patients', 'web', NOW(), NOW()),

-- Appointments
(5, 'view.appointments', 'web', NOW(), NOW()),
(6, 'create.appointments', 'web', NOW(), NOW()),
(7, 'edit.appointments', 'web', NOW(), NOW()),
(8, 'delete.appointments', 'web', NOW(), NOW()),

-- Operations
(9, 'view.operations', 'web', NOW(), NOW()),
(10, 'create.operations', 'web', NOW(), NOW()),
(11, 'edit.operations', 'web', NOW(), NOW()),
(12, 'delete.operations', 'web', NOW(), NOW()),

-- Assessment
(13, 'view.assessment', 'web', NOW(), NOW()),
(14, 'create.assessment', 'web', NOW(), NOW()),
(15, 'edit.assessment', 'web', NOW(), NOW()),
(16, 'delete.assessment', 'web', NOW(), NOW()),

-- Invoices
(17, 'view.invoices', 'web', NOW(), NOW()),
(18, 'create.invoices', 'web', NOW(), NOW()),
(19, 'edit.invoices', 'web', NOW(), NOW()),
(20, 'delete.invoices', 'web', NOW(), NOW()),

-- Services
(21, 'view.services', 'web', NOW(), NOW()),
(22, 'create.services', 'web', NOW(), NOW()),
(23, 'edit.services', 'web', NOW(), NOW()),
(24, 'delete.services', 'web', NOW(), NOW()),

-- Users
(25, 'view.users', 'web', NOW(), NOW()),
(26, 'create.users', 'web', NOW(), NOW()),
(27, 'edit.users', 'web', NOW(), NOW()),
(28, 'delete.users', 'web', NOW(), NOW()),

-- Doctors
(29, 'view.doctors', 'web', NOW(), NOW()),
(30, 'create.doctors', 'web', NOW(), NOW()),
(31, 'edit.doctors', 'web', NOW(), NOW()),
(32, 'delete.doctors', 'web', NOW(), NOW()),

-- Branches
(33, 'view.branches', 'web', NOW(), NOW()),
(34, 'create.branches', 'web', NOW(), NOW()),
(35, 'edit.branches', 'web', NOW(), NOW()),
(36, 'delete.branches', 'web', NOW(), NOW()),

-- Operation Notes
(37, 'view.operation-notes', 'web', NOW(), NOW()),
(38, 'create.operation-notes', 'web', NOW(), NOW()),
(39, 'edit.operation-notes', 'web', NOW(), NOW()),
(40, 'delete.operation-notes', 'web', NOW(), NOW()),

-- Admin Permissions
(41, 'manage.users', 'web', NOW(), NOW()),
(42, 'manage.branches', 'web', NOW(), NOW()),
(43, 'manage.settings', 'web', NOW(), NOW()),
(44, 'view.reports', 'web', NOW(), NOW());

-- حذف الروابط القديمة بين الأدوار والصلاحيات
DELETE FROM `role_has_permissions`;

-- ربط جميع الصلاحيات بدور Admin (role_id = 1)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1), (2, 1), (3, 1), (4, 1), (5, 1), (6, 1), (7, 1), (8, 1),
(9, 1), (10, 1), (11, 1), (12, 1), (13, 1), (14, 1), (15, 1), (16, 1),
(17, 1), (18, 1), (19, 1), (20, 1), (21, 1), (22, 1), (23, 1), (24, 1),
(25, 1), (26, 1), (27, 1), (28, 1), (29, 1), (30, 1), (31, 1), (32, 1),
(33, 1), (34, 1), (35, 1), (36, 1), (37, 1), (38, 1), (39, 1), (40, 1),
(41, 1), (42, 1), (43, 1), (44, 1);

-- ربط الصلاحيات بدور Doctor (role_id = 2)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 2), (2, 2), (3, 2), (5, 2), (6, 2), (7, 2),
(9, 2), (10, 2), (11, 2), (12, 2), (13, 2), (14, 2), (15, 2), (16, 2),
(17, 2), (37, 2), (38, 2), (39, 2), (40, 2);

-- ربط الصلاحيات بدور Secretary (role_id = 3)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 3), (2, 3), (3, 3), (4, 3), (5, 3), (6, 3), (7, 3), (8, 3),
(17, 3), (18, 3), (19, 3), (20, 3);

-- التأكد من أن Admin لديه دور admin
DELETE FROM `model_has_roles` WHERE `model_id` = 1;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- ============================================================
-- ✅ تم الإصلاح!
-- ============================================================
-- 
-- الآن Admin لديه:
-- - دور: admin
-- - جميع الصلاحيات (44 صلاحية)
-- 
-- يجب مسح الكاش بعد التحديث:
-- php artisan cache:clear
-- php artisan config:clear
-- 
-- ============================================================
