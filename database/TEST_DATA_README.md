# دليل تجهيز البيانات التجريبية
# Test Data Setup Guide

---

## 📋 نظرة عامة

هذا الملف يحتوي على بيانات تجريبية جاهزة للنظام:
- **10 مرضى** بأسماء فلسطينية
- **طبيبان**: الدكتور علاء والدكتور طارق (مع حسابات User)
- **10 زيارات**: 5 Assessment و 5 Operation

---

## 🚀 الطريقة 1: استخدام Seeder (محلي)

### الخطوات:

```bash
cd /path/to/Dr-system

# تشغيل Seeder
php artisan db:seed --class=TestDataSeeder
```

### معلومات تسجيل الدخول للأطباء:
- **Dr. Alaa**: `alaa@almyzan.ps` / `password123`
- **Dr. Tariq**: `tariq@almyzan.ps` / `password123`

---

## 🗄️ الطريقة 2: استخدام SQL Script (السيرفر)

### الخطوات:

1. **نسخ ملف SQL إلى السيرفر:**
```bash
scp Dr-system/database/test_data_manual.sql user@server:/path/to/
```

2. **الدخول إلى السيرفر:**
```bash
ssh user@server
```

3. **استيراد الملف في قاعدة البيانات:**
```bash
cd /home/sarfesak/public_html/almyzan

# استيراد SQL
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < test_data_manual.sql
```

أو من داخل MySQL:
```sql
mysql -u sarfesak_sarfesak_almyzan -p
USE sarfesak_almyzan;
SOURCE /path/to/test_data_manual.sql;
```

---

## 📊 البيانات المُنشأة

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

### الأطباء (2):
- **Dr. Alaa Al-Talbishi** (Ophthalmology)
- **Dr. Tariq Al-Husseini** (Refractive Surgery)

### الزيارات (10):
- **5 Assessment**: تقييم أولي للمرضى
- **5 Operation**: عمليات جراحية (LASIK / Femto-LASIK)

---

## ⚠️ ملاحظات مهمة

1. **كلمة المرور**: جميع كلمات المرور للأطباء هي `password123`
2. **الفرع**: سيتم إنشاء فرع باسم "Main Branch" إذا لم يكن موجوداً
3. **الأدوار**: سيتم تعيين دور "doctor" للأطباء تلقائياً
4. **التواريخ**: بعض الزيارات في الماضي (completed) وبعضها في المستقبل (scheduled)

---

## 🔄 إعادة التشغيل

إذا أردت إعادة إنشاء البيانات:

### باستخدام Seeder:
```bash
php artisan db:seed --class=TestDataSeeder
```

### باستخدام SQL:
قم بتشغيل الأوامر التالية في SQL (احذر - ستحذف البيانات القديمة!):
```sql
DELETE FROM appointments WHERE patient_id IN (
    SELECT id FROM patients WHERE city IN ('رام الله', 'نابلس', 'القدس', 'الخليل', 'بيت لحم', 'جنين', 'طولكرم', 'قلقيلية', 'سلفيت', 'طوباس')
);
DELETE FROM patients WHERE city IN ('رام الله', 'نابلس', 'القدس', 'الخليل', 'بيت لحم', 'جنين', 'طولكرم', 'قلقيلية', 'سلفيت', 'طوباس');
DELETE FROM doctors WHERE name IN ('Dr. Alaa Al-Talbishi', 'Dr. Tariq Al-Husseini');
DELETE FROM users WHERE email IN ('alaa@almyzan.ps', 'tariq@almyzan.ps');
```

ثم قم بتشغيل `test_data_manual.sql` مرة أخرى.

---

## ✅ التحقق من البيانات

### التحقق من المرضى:
```sql
SELECT id, full_name, city, phone FROM patients WHERE city IN ('رام الله', 'نابلس', 'القدس', 'الخليل', 'بيت لحم', 'جنين', 'طولكرم', 'قلقيلية', 'سلفيت', 'طوباس');
```

### التحقق من الأطباء:
```sql
SELECT d.id, d.name, u.email FROM doctors d JOIN users u ON d.user_id = u.id;
```

### التحقق من الزيارات:
```sql
SELECT a.id, p.full_name, d.name, a.visit_type, a.appointment_date, a.visit_stage 
FROM appointments a 
JOIN patients p ON a.patient_id = p.id 
JOIN doctors d ON a.doctor_id = d.id 
ORDER BY a.appointment_date;
```

---

## 📝 الملفات

- `TestDataSeeder.php`: Seeder للاستخدام المحلي
- `test_data_manual.sql`: SQL script للاستخدام على السيرفر
- `TEST_DATA_README.md`: هذا الملف

---

## 🆘 استكشاف الأخطاء

### مشكلة: "Duplicate entry"
- **الحل**: الملف يستخدم `ON DUPLICATE KEY UPDATE` - سيتم تحديث البيانات الموجودة بدلاً من إنشاء نسخ مكررة.

### مشكلة: "Foreign key constraint fails"
- **الحل**: تأكد من وجود:
  - جدول `branches` مع فرع واحد على الأقل
  - جدول `users` مع مستخدم admin واحد على الأقل (id = 1)
  - جدول `roles` مع دور "doctor"

### مشكلة: "Table doesn't exist"
- **الحل**: تأكد من تشغيل جميع migrations أولاً:
```bash
php artisan migrate
```

---

**تم التجهيز بواسطة:** النظام الآلي  
**التاريخ:** 2026-01-11
