# خطوات تشغيل Migrations على السيرفر
# Migration Steps on Server

---

## 🔴 المشكلة الحالية | Current Issue

```
ERROR  Migration table not found.
```

هذا يعني أن جدول `migrations` غير موجود في قاعدة البيانات، وهذا طبيعي في أول مرة.

---

## ✅ الحل | Solution

### الخطوة 1: التحقق من الاتصال بقاعدة البيانات

```bash
# تأكد من أن ملف .env موجود ومضبوط بشكل صحيح
cat .env | grep DB_

# يجب أن ترى:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sarfesak_almyzan
# DB_USERNAME=sarfesak_sarfesak_almyzan
# DB_PASSWORD=Gg65N$1mzL
```

### الخطوة 2: اختبار الاتصال بقاعدة البيانات

```bash
php artisan tinker
```

ثم في Tinker:
```php
DB::connection()->getPdo();
// إذا نجح الاتصال، سترى: PDO object
// إذا فشل، سترى رسالة خطأ
exit
```

### الخطوة 3: تشغيل Migrations

```bash
# تشغيل جميع Migrations
php artisan migrate --force

# أو إذا أردت رؤية ما سيحدث قبل التنفيذ:
php artisan migrate
```

**ملاحظة:** `--force` مطلوب في بيئة الإنتاج (production).

### الخطوة 4: التحقق من حالة Migrations

بعد تشغيل Migrations بنجاح:

```bash
php artisan migrate:status
```

يجب أن ترى قائمة بجميع Migrations وحالتها (Ran/Pending).

---

## 📋 الأوامر الكاملة | Complete Commands

```bash
# 1. تأكد من أنك في المجلد الصحيح
cd /home/sarfesak/public_html/almyzan

# 2. تحقق من ملف .env
ls -la .env

# 3. توليد APP_KEY (إذا لم يكن موجوداً)
php artisan key:generate

# 4. تنظيف الكاش
php artisan config:clear

# 5. تشغيل Migrations
php artisan migrate --force

# 6. التحقق من الحالة
php artisan migrate:status

# 7. إنشاء Storage Link
php artisan storage:link

# 8. تنظيف وبناء الكاش
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 🆘 استكشاف الأخطاء | Troubleshooting

### خطأ: Database Connection Failed

```bash
# تحقق من بيانات .env
cat .env | grep DB_

# جرب الاتصال يدوياً
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan
# أدخل كلمة المرور: Gg65N$1mzL
```

### خطأ: Access Denied

- تأكد من أن المستخدم لديه صلاحيات على قاعدة البيانات
- من Webuzo: MySQL Databases → تحقق من الصلاحيات

### خطأ: Table Already Exists

إذا ظهر خطأ أن الجدول موجود بالفعل:

```bash
# تحقق من الجداول الموجودة
php artisan tinker
DB::select('SHOW TABLES');
exit

# إذا كانت الجداول موجودة، يمكنك:
php artisan migrate:status
```

### خطأ: SQLSTATE[HY000] [2002]

هذا يعني أن MySQL غير متاح على `127.0.0.1`. جرب:

```bash
# في ملف .env، غيّر:
DB_HOST=localhost
# بدلاً من:
DB_HOST=127.0.0.1
```

---

## ✅ بعد نجاح Migrations

بعد تشغيل Migrations بنجاح، يجب أن ترى:

```
Migration table created successfully.
Migrating: 2024_01_01_000001_create_users_table
Migrated:  2024_01_01_000001_create_users_table (XX.XXms)
...
```

ثم:

```bash
# التحقق من الحالة
php artisan migrate:status

# يجب أن ترى جميع Migrations مع حالة "Ran"
```

---

## 📝 ملاحظات مهمة | Important Notes

1. **--force:** مطلوب في بيئة الإنتاج
2. **النسخ الاحتياطي:** قبل تشغيل Migrations، احفظ نسخة احتياطية من قاعدة البيانات
3. **الوقت:** قد تستغرق Migrations بعض الوقت حسب عدد الجداول

---

**بعد تشغيل `php artisan migrate --force` بنجاح، سيعمل `migrate:status` بدون مشاكل! ✅**
