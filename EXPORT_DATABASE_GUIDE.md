# 📤 دليل تصدير ونقل قاعدة البيانات

## الملف المُصدّر الحالي

**الموقع:** 
```
/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system/database/local_database_export_20260116_142857.sql
```

**المحتوى:**
- ✅ **Schema (هيكل الجداول)** - موجود
- ✅ **البيانات (Data)** - موجودة
- ⚠️ **الصيغة:** SQLite (يحتاج تحويل إلى MySQL)

---

## ⚠️ مشكلة مهمة

الملف المُصدّر بصيغة **SQLite**، والسيرفر يستخدم **MySQL**. 

### الحلول:

#### الحل 1: استخدام full_database_fresh.sql (موصى به)

هذا الملف جاهز لـ MySQL ويحتوي على Schema كامل:

```bash
# على السيرفر
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < database/full_database_fresh.sql
```

**لكن:** هذا الملف لا يحتوي على بياناتك المحلية.

#### الحل 2: استيراد البيانات فقط (بدون Schema)

1. **أولاً:** استخدم `full_database_fresh.sql` لإنشاء Schema
2. **ثانياً:** استخرج البيانات فقط من SQLite وادخلها يدوياً

#### الحل 3: استخدام mysqldump من قاعدة بيانات MySQL محلية (إن وجدت)

إذا كان لديك MySQL محلياً:

```bash
mysqldump -u root -p database_name > database_export.sql
```

---

## نقل الملف إلى السيرفر

### استخدام SCP:

```bash
scp "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system/database/local_database_export_20260116_142857.sql" root@159.198.75.10:/home/sarfesak/public_html/almyzan/database/
```

---

## على السيرفر - الخطوات

### 1. إصلاح الجداول أولاً (مهم جداً!)

```bash
cd /home/sarfesak/public_html/almyzan

# إصلاح refractive_profiles
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < database/fix_refractive_profiles_table.sql

# إصلاح medical_histories
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < database/fix_medical_histories_table.sql
```

### 2. استيراد Schema من full_database_fresh.sql

```bash
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < database/full_database_fresh.sql
```

**تحذير:** هذا سيحذف جميع البيانات الموجودة!

### 3. استيراد البيانات (إذا أردت)

**ملاحظة:** ملف SQLite يحتاج تحويل يدوي أو استخدام أداة تحويل.

---

## الخيار الأفضل

**استخدم `full_database_fresh.sql`** لأنه:
- ✅ جاهز لـ MySQL
- ✅ يحتوي على Schema كامل
- ✅ يحتوي على بيانات تجريبية

**ثم أضف بياناتك يدوياً من خلال النظام.**
