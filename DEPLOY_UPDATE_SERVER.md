# 🔄 دليل تحديث السيرفر من GitHub
## Server Update Guide from GitHub

---

## 📋 الخيارات - Options

### **الخيار 1: تحديث الملفات الموجودة (موصى به)**
إذا كان السيرفر يحتوي على نسخة قديمة من المشروع:

```bash
# على السيرفر
cd /home/sarfesak/public_html/almyzan

# حفظ ملف .env (مهم جداً!)
cp .env .env.backup

# جلب التعديلات من GitHub
git pull origin main

# استعادة ملف .env
cp .env.backup .env

# تشغيل Migrations الجديدة
php artisan migrate --force

# تثبيت التبعيات الجديدة (إن وجدت)
composer install --no-dev --optimize-autoloader

# تجميع ملفات CSS/JS
npm install
npm run build

# تنظيف الكاش
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

### **الخيار 2: حذف القديم وإعادة التثبيت (إذا كان هناك مشاكل)**
إذا كان السيرفر يحتوي على ملفات قديمة تسبب مشاكل:

```bash
# على السيرفر
cd /home/sarfesak/public_html

# حفظ ملف .env (مهم جداً!)
cp almyzan/.env .env.backup

# حذف المجلد القديم
rm -rf almyzan

# جلب النسخة الجديدة من GitHub
git clone https://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@github.com/baiitpait/almezanSystem.git almyzan

# الانتقال للمجلد
cd almyzan

# استعادة ملف .env
cp ../.env.backup .env

# تثبيت التبعيات
composer install --no-dev --optimize-autoloader
npm install
npm run build

# تشغيل Migrations
php artisan migrate --force

# إنشاء Storage Link
php artisan storage:link

# تنظيف وتحسين الأداء
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# إصلاح الصلاحيات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

## ⚠️ تحذيرات مهمة - Important Warnings

### 1. **حفظ ملف .env**
**مهم جداً:** احفظ ملف `.env` قبل أي عملية تحديث:
```bash
cp .env .env.backup
```

### 2. **حفظ قاعدة البيانات**
قبل حذف أي شيء، تأكد من عمل نسخة احتياطية:
```bash
# من لوحة تحكم Webuzo أو phpMyAdmin
# أو عبر SSH:
mysqldump -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan > backup_$(date +%Y%m%d).sql
```

### 3. **التحقق من Migrations**
بعد التحديث، تحقق من حالة Migrations:
```bash
php artisan migrate:status
```

---

## ✅ التحقق من التحديث - Verify Update

### 1. التحقق من الملفات
```bash
# التحقق من وجود الملفات الجديدة
ls -la database/migrations/2026_01_*
ls -la resources/views/livewire/unauthorized.blade.php
```

### 2. التحقق من قاعدة البيانات
```bash
# التحقق من Migrations
php artisan migrate:status

# يجب أن ترى جميع Migrations في حالة "Ran"
```

### 3. اختبار الموقع
- افتح: `https://almyzan.baitpait.space`
- جرب تسجيل الدخول: `admin@gmail.com` / `admin123`
- تحقق من أن جميع الصفحات تعمل

---

## 🔧 حل المشاكل - Troubleshooting

### مشكلة: خطأ في Git Pull
```bash
# إذا كان هناك تعارضات
git stash
git pull origin main
git stash pop
```

### مشكلة: خطأ في Migrations
```bash
# إذا كانت هناك migration مكررة
php artisan migrate:status
# تحقق من أي migration في حالة "Pending"
# إذا كانت الجداول موجودة، عدّل Migration للتحقق من وجود الجداول
```

### مشكلة: خطأ في الصلاحيات
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

---

## 📝 ملاحظات - Notes

- **الخيار 1 (git pull)** موصى به إذا كان السيرفر يعمل بشكل صحيح
- **الخيار 2 (حذف وإعادة)** استخدمه فقط إذا كان هناك مشاكل كبيرة
- **احفظ دائماً** ملف `.env` وقاعدة البيانات قبل أي تحديث
- **تحقق من** أن جميع Migrations تم تشغيلها بعد التحديث

---

**آخر تحديث:** 2026-01-11
