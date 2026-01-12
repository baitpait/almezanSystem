# دليل قاعدة البيانات الكاملة - Fresh Database Setup
# Full Database Schema + Data Guide

---

## 📋 نظرة عامة

هذا الملف يحتوي على **قاعدة بيانات كاملة** (Schema + Data) جاهزة للاستيراد المباشر على السيرفر.

**المحتوى:**
- ✅ **DROP** جميع الجداول القديمة
- ✅ **CREATE** جميع الجداول (Schema كامل)
- ✅ **INSERT** البيانات الأساسية (Admin, Roles, Permissions, Branches, Categories, Services)
- ✅ **INSERT** البيانات التجريبية (10 مرضى، طبيبان، 10 زيارات)

---

## 🚀 الاستخدام على السيرفر

### الخطوة 1: نسخ الملف إلى السيرفر

```bash
scp Dr-system/database/full_database_fresh.sql user@server:/path/to/
```

### الخطوة 2: الدخول إلى السيرفر

```bash
ssh user@server
```

### الخطوة 3: استيراد قاعدة البيانات الكاملة

```bash
cd /home/sarfesak/public_html/almyzan

# استيراد SQL (سيتم مسح جميع البيانات القديمة!)
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < full_database_fresh.sql
```

أو من داخل MySQL:

```sql
mysql -u sarfesak_sarfesak_almyzan -p
USE sarfesak_almyzan;
SOURCE /path/to/full_database_fresh.sql;
```

---

## 📊 البيانات المُنشأة

### المستخدمون (3):
1. **Admin**: `admin@gmail.com` / `password123`
2. **Dr. Alaa**: `alaa@almyzan.ps` / `password123`
3. **Dr. Tariq**: `tariq@almyzan.ps` / `password123`

### الفروع (1):
- **Main Branch**: رام الله - فلسطين

### الأطباء (2):
- **Dr. Alaa Al-Talbishi** (Ophthalmology)
- **Dr. Tariq Al-Husseini** (Refractive Surgery)

### المرضى (10):
1. محمد أحمد النابلسي
2. فاطمة خليل القدسي
3. علي محمود الخليل
4. سارة يوسف رام الله
5. خالد إبراهيم نابلس
6. مريم حسن بيت لحم
7. أحمد سعد غزة
8. ليلى عمر يافا
9. يوسف فهد حيفا
10. نورا عبدالرحمن عكا

### الزيارات (10):
- **5 Assessment**: تقييم أولي للمرضى
- **5 Operation**: عمليات جراحية (LASIK / Femto-LASIK)

### الفئات (5):
- Doctors, Consultation, Follow-up, Surgery, Emergency

### الخدمات (7):
- جراحة الساد (5000.00)
- جراحة الليزر (8000.00)
- استشارة طبية (200.00)
- أشعة سينية (150.00)
- تحاليل دم (100.00)
- فحص عيون أساسي (50.00)
- زيارة متابعة (150.00)

### الأدوار (3):
- **admin**: جميع الصلاحيات (24 صلاحية)
- **doctor**: صلاحيات محددة (15 صلاحية)
- **secretary**: صلاحيات محددة (12 صلاحية)

---

## ⚠️ تحذيرات مهمة

1. **⚠️ هذا الملف سيمسح جميع البيانات القديمة!**
   - سيتم حذف جميع الجداول والبيانات الموجودة
   - تأكد من عمل نسخة احتياطية قبل الاستيراد

2. **كلمات المرور:**
   - جميع كلمات المرور هي `password123`
   - **يجب تغييرها فوراً في الإنتاج!**

3. **Foreign Keys:**
   - الملف يعطل Foreign Keys أثناء الاستيراد ثم يعيد تفعيلها
   - هذا يضمن عدم وجود أخطاء أثناء الاستيراد

---

## 🔄 إعادة الاستيراد

إذا أردت إعادة استيراد البيانات:

```bash
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < full_database_fresh.sql
```

**ملاحظة:** الملف يستخدم `DROP TABLE IF EXISTS` لذلك يمكن إعادة الاستيراد بأمان.

---

## ✅ التحقق من البيانات

### التحقق من المستخدمين:
```sql
SELECT id, name, email, role FROM users;
```

### التحقق من الأطباء:
```sql
SELECT d.id, d.name, u.email FROM doctors d JOIN users u ON d.user_id = u.id;
```

### التحقق من المرضى:
```sql
SELECT id, full_name, city, phone FROM patients ORDER BY id;
```

### التحقق من الزيارات:
```sql
SELECT a.id, p.full_name, d.name, a.visit_type, a.appointment_date, a.visit_stage 
FROM appointments a 
JOIN patients p ON a.patient_id = p.id 
JOIN doctors d ON a.doctor_id = d.id 
ORDER BY a.appointment_date;
```

### التحقق من الأدوار والصلاحيات:
```sql
SELECT r.name AS role, COUNT(rhp.permission_id) AS permissions_count
FROM roles r
LEFT JOIN role_has_permissions rhp ON r.id = rhp.role_id
GROUP BY r.id, r.name;
```

---

## 🆘 استكشاف الأخطاء

### مشكلة: "Access denied"
- **الحل:** تأكد من أن المستخدم لديه صلاحيات كاملة على قاعدة البيانات

### مشكلة: "Table already exists"
- **الحل:** الملف يستخدم `DROP TABLE IF EXISTS` - يجب أن يعمل تلقائياً. إذا استمرت المشكلة، قم بمسح الجداول يدوياً أولاً.

### مشكلة: "Foreign key constraint fails"
- **الحل:** الملف يعطل Foreign Keys أثناء الاستيراد. إذا استمرت المشكلة، تأكد من أن جميع الجداول تم إنشاؤها بشكل صحيح.

### مشكلة: "Duplicate entry"
- **الحل:** تأكد من أن قاعدة البيانات فارغة قبل الاستيراد، أو استخدم `DROP DATABASE` ثم `CREATE DATABASE` أولاً.

---

## 📝 الملفات

- `full_database_fresh.sql` - SQL script كامل (Schema + Data)
- `FULL_DATABASE_README.md` - هذا الملف

---

## 🔐 معلومات تسجيل الدخول

### Admin:
- **Email:** `admin@gmail.com`
- **Password:** `100200300`

### Dr. Alaa:
- **Email:** `alaa@almyzan.ps`
- **Password:** `password123`

### Dr. Tariq:
- **Email:** `tariq@almyzan.ps`
- **Password:** `password123`

---

## ⚠️ مهم: إصلاح كلمات المرور

بعد استيراد قاعدة البيانات، **يجب** تحديث كلمات المرور:

```bash
# على السيرفر
cd /home/sarfesak/public_html/almyzan
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < fix_passwords.sql
```

أو استخدم Tinker (انظر `FIX_LOGIN_README.md`).

---

**تم التجهيز بواسطة:** النظام الآلي  
**التاريخ:** 2026-01-11  
**الحالة:** ✅ جاهز للاستخدام
