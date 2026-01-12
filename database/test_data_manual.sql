-- ============================================================
-- ملف SQL يدوي لتجهيز بيانات تجريبية للنظام
-- Test Data SQL Script for Manual Database Setup
-- ============================================================
-- 
-- الاستخدام: استورد هذا الملف في قاعدة البيانات على السيرفر
-- Usage: Import this file into the database on the server
-- 
-- mysql -u [username] -p [database_name] < test_data_manual.sql
-- 
-- ============================================================

-- تنظيف البيانات القديمة (اختياري - احذر!)
-- Clean old test data (Optional - Be careful!)
-- DELETE FROM appointments WHERE patient_id IN (SELECT id FROM patients WHERE city IN ('رام الله', 'نابلس', 'القدس', 'الخليل', 'بيت لحم', 'جنين', 'طولكرم', 'قلقيلية', 'سلفيت', 'طوباس'));
-- DELETE FROM patients WHERE city IN ('رام الله', 'نابلس', 'القدس', 'الخليل', 'بيت لحم', 'جنين', 'طولكرم', 'قلقيلية', 'سلفيت', 'طوباس');
-- DELETE FROM doctors WHERE name IN ('Dr. Alaa Al-Talbishi', 'Dr. Tariq Al-Husseini');
-- DELETE FROM users WHERE email IN ('alaa@almyzan.ps', 'tariq@almyzan.ps');

-- ============================================================
-- 1. إنشاء/التحقق من الفرع الرئيسي
-- ============================================================
INSERT INTO branches (name, address, phone, email, is_active, created_at, updated_at)
VALUES ('Main Branch', 'رام الله - فلسطين', '0599999999', 'main@almyzan.ps', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    address = 'رام الله - فلسطين',
    phone = '0599999999',
    email = 'main@almyzan.ps',
    is_active = 1,
    updated_at = NOW();

SET @branch_id = LAST_INSERT_ID();
SELECT @branch_id := id FROM branches WHERE name = 'Main Branch' LIMIT 1;

-- ============================================================
-- 2. إنشاء حسابات User للأطباء
-- ============================================================

-- الدكتور علاء
INSERT INTO users (name, email, password, role, branch_id, phone, is_active, created_at, updated_at)
VALUES (
    'Dr. Alaa Al-Talbishi',
    'alaa@almyzan.ps',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password123
    'doctor',
    @branch_id,
    '0591234567',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    name = 'Dr. Alaa Al-Talbishi',
    password = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    role = 'doctor',
    branch_id = @branch_id,
    phone = '0591234567',
    is_active = 1,
    updated_at = NOW();

SET @alaa_user_id = LAST_INSERT_ID();
SELECT @alaa_user_id := id FROM users WHERE email = 'alaa@almyzan.ps' LIMIT 1;

-- الدكتور طارق
INSERT INTO users (name, email, password, role, branch_id, phone, is_active, created_at, updated_at)
VALUES (
    'Dr. Tariq Al-Husseini',
    'tariq@almyzan.ps',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password123
    'doctor',
    @branch_id,
    '0597654321',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    name = 'Dr. Tariq Al-Husseini',
    password = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    role = 'doctor',
    branch_id = @branch_id,
    phone = '0597654321',
    is_active = 1,
    updated_at = NOW();

SET @tariq_user_id = LAST_INSERT_ID();
SELECT @tariq_user_id := id FROM users WHERE email = 'tariq@almyzan.ps' LIMIT 1;

-- ============================================================
-- 3. إنشاء الأطباء
-- ============================================================

-- الدكتور علاء
INSERT INTO doctors (user_id, branch_id, name, phone, specialization, notify_via_sms, notify_via_email, follow_up, created_at, updated_at)
VALUES (
    @alaa_user_id,
    @branch_id,
    'Dr. Alaa Al-Talbishi',
    '0591234567',
    'Ophthalmology',
    1,
    1,
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    user_id = @alaa_user_id,
    branch_id = @branch_id,
    name = 'Dr. Alaa Al-Talbishi',
    phone = '0591234567',
    specialization = 'Ophthalmology',
    notify_via_sms = 1,
    notify_via_email = 1,
    follow_up = 1,
    updated_at = NOW();

SET @alaa_doctor_id = LAST_INSERT_ID();
SELECT @alaa_doctor_id := id FROM doctors WHERE user_id = @alaa_user_id LIMIT 1;

-- الدكتور طارق
INSERT INTO doctors (user_id, branch_id, name, phone, specialization, notify_via_sms, notify_via_email, follow_up, created_at, updated_at)
VALUES (
    @tariq_user_id,
    @branch_id,
    'Dr. Tariq Al-Husseini',
    '0597654321',
    'Refractive Surgery',
    1,
    1,
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    user_id = @tariq_user_id,
    branch_id = @branch_id,
    name = 'Dr. Tariq Al-Husseini',
    phone = '0597654321',
    specialization = 'Refractive Surgery',
    notify_via_sms = 1,
    notify_via_email = 1,
    follow_up = 1,
    updated_at = NOW();

SET @tariq_doctor_id = LAST_INSERT_ID();
SELECT @tariq_doctor_id := id FROM doctors WHERE user_id = @tariq_user_id LIMIT 1;

-- ============================================================
-- 4. إنشاء 10 مرضى بأسماء فلسطينية
-- ============================================================

-- مريض 1: محمد أحمد النابلسي
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('محمد أحمد النابلسي', '1985-03-15', 'male', '0591000001', 'رام الله', 'فلسطين', 'مريض جديد - يحتاج تقييم شامل', NOW(), NOW());
SET @patient_1 = LAST_INSERT_ID();

-- مريض 2: فاطمة خليل القدسي
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('فاطمة خليل القدسي', '1990-07-22', 'female', '0591000002', 'نابلس', 'فلسطين', NULL, NOW(), NOW());
SET @patient_2 = LAST_INSERT_ID();

-- مريض 3: علي محمود الخليل
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('علي محمود الخليل', '1978-11-08', 'male', '0591000003', 'القدس', 'فلسطين', 'مريض جديد - يحتاج تقييم شامل', NOW(), NOW());
SET @patient_3 = LAST_INSERT_ID();

-- مريض 4: سارة يوسف رام الله
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('سارة يوسف رام الله', '1992-05-30', 'female', '0591000004', 'الخليل', 'فلسطين', NULL, NOW(), NOW());
SET @patient_4 = LAST_INSERT_ID();

-- مريض 5: خالد إبراهيم نابلس
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('خالد إبراهيم نابلس', '1988-09-12', 'male', '0591000005', 'بيت لحم', 'فلسطين', 'مريض جديد - يحتاج تقييم شامل', NOW(), NOW());
SET @patient_5 = LAST_INSERT_ID();

-- مريض 6: مريم حسن بيت لحم
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('مريم حسن بيت لحم', '1995-01-25', 'female', '0591000006', 'جنين', 'فلسطين', NULL, NOW(), NOW());
SET @patient_6 = LAST_INSERT_ID();

-- مريض 7: أحمد سعد غزة
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('أحمد سعد غزة', '1982-12-18', 'male', '0591000007', 'طولكرم', 'فلسطين', NULL, NOW(), NOW());
SET @patient_7 = LAST_INSERT_ID();

-- مريض 8: ليلى عمر يافا
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('ليلى عمر يافا', '1987-06-05', 'female', '0591000008', 'قلقيلية', 'فلسطين', NULL, NOW(), NOW());
SET @patient_8 = LAST_INSERT_ID();

-- مريض 9: يوسف فهد حيفا
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('يوسف فهد حيفا', '1993-08-20', 'male', '0591000009', 'سلفيت', 'فلسطين', NULL, NOW(), NOW());
SET @patient_9 = LAST_INSERT_ID();

-- مريض 10: نورا عبدالرحمن عكا
INSERT INTO patients (full_name, date_of_birth, gender, phone, city, country, notes, created_at, updated_at)
VALUES ('نورا عبدالرحمن عكا', '1991-04-14', 'female', '0591000010', 'طوباس', 'فلسطين', NULL, NOW(), NOW());
SET @patient_10 = LAST_INSERT_ID();

-- ============================================================
-- 5. إنشاء 10 زيارات (5 Assessment و 5 Operation)
-- ============================================================

-- زيارات Assessment (5 زيارات)
INSERT INTO appointments (patient_id, doctor_id, branch_id, created_by, appointment_date, appointment_time, duration, visit_type, visit_stage, status, notes, notify_patient_sms, notify_doctor_sms, notify_doctor_email, follow_up, created_at, updated_at)
VALUES
    (@patient_1, @alaa_doctor_id, @branch_id, 1, DATE_SUB(NOW(), INTERVAL 10 DAY), '09:00:00', 30, 'Assessment', 'completed', 'completed', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW()),
    (@patient_2, @tariq_doctor_id, @branch_id, 1, DATE_SUB(NOW(), INTERVAL 5 DAY), '09:30:00', 30, 'Assessment', 'completed', 'completed', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW()),
    (@patient_3, @alaa_doctor_id, @branch_id, 1, DATE_ADD(NOW(), INTERVAL 3 DAY), '10:00:00', 30, 'Assessment', 'waiting', 'scheduled', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW()),
    (@patient_4, @tariq_doctor_id, @branch_id, 1, DATE_ADD(NOW(), INTERVAL 7 DAY), '10:30:00', 30, 'Assessment', 'waiting', 'scheduled', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW()),
    (@patient_5, @alaa_doctor_id, @branch_id, 1, DATE_ADD(NOW(), INTERVAL 15 DAY), '11:00:00', 30, 'Assessment', 'waiting', 'scheduled', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW());

-- زيارات Operation (5 زيارات)
INSERT INTO appointments (patient_id, doctor_id, branch_id, created_by, appointment_date, appointment_time, duration, visit_type, visit_stage, status, notes, notify_patient_sms, notify_doctor_sms, notify_doctor_email, follow_up, created_at, updated_at)
VALUES
    (@patient_6, @tariq_doctor_id, @branch_id, 1, DATE_SUB(NOW(), INTERVAL 8 DAY), '11:30:00', 60, 'Operation', 'completed', 'completed', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW()),
    (@patient_7, @alaa_doctor_id, @branch_id, 1, DATE_SUB(NOW(), INTERVAL 2 DAY), '12:00:00', 60, 'Operation', 'completed', 'completed', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW()),
    (@patient_8, @tariq_doctor_id, @branch_id, 1, DATE_ADD(NOW(), INTERVAL 5 DAY), '12:30:00', 60, 'Operation', 'waiting', 'scheduled', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW()),
    (@patient_9, @alaa_doctor_id, @branch_id, 1, DATE_ADD(NOW(), INTERVAL 12 DAY), '13:00:00', 60, 'Operation', 'waiting', 'scheduled', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW()),
    (@patient_10, @tariq_doctor_id, @branch_id, 1, DATE_ADD(NOW(), INTERVAL 20 DAY), '13:30:00', 60, 'Operation', 'waiting', 'scheduled', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW());

-- ============================================================
-- 6. تعيين الأدوار للأطباء (Spatie Permission)
-- ============================================================

-- الحصول على role_id للدور 'doctor'
SET @doctor_role_id = (SELECT id FROM roles WHERE name = 'doctor' LIMIT 1);

-- تعيين الدور للدكتور علاء
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT @doctor_role_id, 'App\\Models\\User', @alaa_user_id
WHERE NOT EXISTS (
    SELECT 1 FROM model_has_roles 
    WHERE role_id = @doctor_role_id 
    AND model_type = 'App\\Models\\User' 
    AND model_id = @alaa_user_id
);

-- تعيين الدور للدكتور طارق
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT @doctor_role_id, 'App\\Models\\User', @tariq_user_id
WHERE NOT EXISTS (
    SELECT 1 FROM model_has_roles 
    WHERE role_id = @doctor_role_id 
    AND model_type = 'App\\Models\\User' 
    AND model_id = @tariq_user_id
);

-- ============================================================
-- ✅ اكتمل التجهيز!
-- ============================================================
-- 
-- البيانات المُنشأة:
-- - فرع واحد: Main Branch
-- - طبيبان: Dr. Alaa Al-Talbishi و Dr. Tariq Al-Husseini
-- - 10 مرضى بأسماء فلسطينية
-- - 10 زيارات (5 Assessment و 5 Operation)
-- 
-- معلومات تسجيل الدخول للأطباء:
-- - Dr. Alaa: alaa@almyzan.ps / password123
-- - Dr. Tariq: tariq@almyzan.ps / password123
-- 
-- ============================================================
