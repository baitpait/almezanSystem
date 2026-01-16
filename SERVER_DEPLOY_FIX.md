# 🔧 إصلاح مشاكل السيرفر - Server Deployment Fix

## المشاكل التي تم حلها

### 1. مشكلة `dropdownOriginalParents` has already been declared
**السبب:** المتغير `dropdownOriginalParents` كان يُعلن عدة مرات عند تحديث Livewire.

**الحل:** تم استخدام `window.dropdownOriginalParents` مع فحص `if (!window.dropdownOriginalParents)` قبل الإعلان.

**الملف:** `resources/views/components/layouts/app.blade.php` (السطر 383)

### 2. مشكلة 500 Internal Server Error في Livewire
**السبب:** قد يكون بسبب:
- خطأ في الكود (branch_id null)
- مشكلة في الكاش
- مشكلة في الصلاحيات

**الحل:** 
- إصلاح `branch_id` في `ScheduledOperations.php` و `OperationManager.php`
- تنظيف الكاش بالكامل
- إعادة بناء config cache

## خطوات التشغيل على السيرفر

### 1. رفع التعديلات إلى السيرفر
```bash
# على الجهاز المحلي
git add .
git commit -m "fix: resolve dropdownOriginalParents and 500 errors"
git push origin main
```

### 2. على السيرفر - سحب التعديلات
```bash
cd /home/sarfesak/public_html/almyzan
git pull origin main
```

### 3. تشغيل سكريبت البناء
```bash
chmod +x deploy_build.sh
./deploy_build.sh
```

أو يدوياً:
```bash
cd /home/sarfesak/public_html/almyzan

# 1. التأكد من CACHE_DRIVER=file
sed -i 's/CACHE_DRIVER=database/CACHE_DRIVER=file/g' .env
sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/g' .env

# 2. حذف config cache
rm -f bootstrap/cache/config.php

# 3. تنظيف الكاش
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 4. إعادة بناء config cache
php artisan config:cache
php artisan route:cache

# 5. عمل Build
npm run build

# 6. تحسين الأداء
php artisan optimize
```

## التعديلات المهمة

### 1. `ScheduledOperations.php`
- إصلاح `branch_id` في `viewOperation()`:
```php
'branch_id' => $appointment->branch_id ?? auth()->user()->branch_id ?? 1,
```

### 2. `OperationManager.php`
- إصلاح `branch_id` في `viewOperation()`:
```php
'branch_id' => $appointment->branch_id ?? auth()->user()->branch_id ?? 1,
```

### 3. `app.blade.php`
- إصلاح `dropdownOriginalParents`:
```javascript
if (!window.dropdownOriginalParents) {
    window.dropdownOriginalParents = new Map();
}
```

## التحقق من الحل

1. افتح المتصفح واذهب إلى: `https://almyzan.baitpait.space`
2. افتح Console (F12) وتحقق من عدم وجود أخطاء JavaScript
3. جرب فتح صفحة Operations أو Assessment
4. تحقق من عدم وجود 500 errors في Network tab

## ملاحظات مهمة

- تأكد من أن `CACHE_DRIVER=file` و `SESSION_DRIVER=file` في `.env`
- بعد أي تعديل على `.env`، احذف `bootstrap/cache/config.php` يدوياً
- بعد أي تعديل على الكود، قم بعمل `npm run build` على السيرفر
