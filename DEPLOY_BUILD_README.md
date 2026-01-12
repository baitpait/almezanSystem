# دليل تشغيل السيرفر وتنظيف الكاش وعمل Build
# Server Start, Cache Clear, and Build Guide

---

## 🚀 الاستخدام السريع

### على السيرفر:

```bash
cd /home/sarfesak/public_html/almyzan

# نسخ Script إلى السيرفر (إذا لم يكن موجوداً)
# ثم:
chmod +x deploy_build.sh
./deploy_build.sh
```

---

## 📋 ما يقوم به Script

1. **التحقق من إعدادات الكاش:**
   - يغير `CACHE_DRIVER` من `database` إلى `file` إذا لزم الأمر
   - يغير `SESSION_DRIVER` من `database` إلى `file` إذا لزم الأمر

2. **حذف Config Cache:**
   - يحذف `bootstrap/cache/config.php`
   - يحذف `bootstrap/cache/routes-v7.php`
   - يحذف `bootstrap/cache/services.php`

3. **تنظيف الكاش:**
   - `php artisan config:clear`
   - `php artisan route:clear`
   - `php artisan view:clear`
   - `php artisan cache:clear`
   - `php artisan permission:cache-reset`

4. **إعادة بناء Cache:**
   - `php artisan config:cache`
   - `php artisan route:cache`

5. **تجميع الملفات:**
   - `npm run build`

6. **تحسين الأداء:**
   - `php artisan optimize`

---

## 🔧 الاستخدام اليدوي

إذا أردت تشغيل الأوامر يدوياً:

```bash
cd /home/sarfesak/public_html/almyzan

# 1. إصلاح إعدادات الكاش
sed -i 's/CACHE_DRIVER=database/CACHE_DRIVER=file/g' .env
sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/g' .env

# 2. حذف config cache
rm -f bootstrap/cache/config.php

# 3. تنظيف الكاش
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. إعادة بناء cache
php artisan config:cache
php artisan route:cache

# 5. تجميع الملفات
npm run build

# 6. تحسين الأداء
php artisan optimize

# 7. إعادة تعيين كاش الصلاحيات
php artisan permission:cache-reset
```

---

## ⚠️ ملاحظات مهمة

1. **npm run build** قد يستغرق وقتاً (1-2 دقيقة)
2. **php artisan optimize** يجمع كل شيء في ملف واحد لتحسين الأداء
3. بعد تشغيل Script، يجب أن يعمل النظام بشكل أسرع

---

## 🔍 التحقق من النجاح

بعد تشغيل Script، تحقق من:

```bash
# التحقق من أن config cache تم إنشاؤه
ls -lh bootstrap/cache/config.php

# التحقق من أن build تم
ls -lh public/build/

# التحقق من إعدادات .env
grep "CACHE_DRIVER\|SESSION_DRIVER" .env
```

---

## 📝 الملفات

- `deploy_build.sh` - Script لتشغيل السيرفر وتنظيف الكاش وعمل Build
- `DEPLOY_BUILD_README.md` - هذا الملف

---

**تم التجهيز بواسطة:** النظام الآلي  
**التاريخ:** 2026-01-11
