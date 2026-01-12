# إصلاح مشكلة الكاش
# Fix Cache Issue

---

## 🔍 المشكلة

عند محاولة مسح الكاش، يظهر خطأ:
```
SQLSTATE[HY000] [2002] No such file or directory (Connection: mysql, SQL: delete from `cache`)
```

**السبب:** Laravel يحاول مسح الكاش من قاعدة البيانات، لكن الاتصال بقاعدة البيانات فشل أو `CACHE_DRIVER` لا يزال `database`.

---

## ✅ الحل

### الطريقة 1: استخدام Script (سريع)

```bash
# على السيرفر
cd /home/sarfesak/public_html/almyzan

# جعل الملف قابل للتنفيذ
chmod +x fix_cache_issue.sh

# تشغيل Script
./fix_cache_issue.sh
```

### الطريقة 2: يدوياً

```bash
cd /home/sarfesak/public_html/almyzan

# 1. تعديل .env
nano .env
# أو
vi .env

# تأكد من أن:
# CACHE_DRIVER=file
# CACHE_STORE=file
# SESSION_DRIVER=file

# 2. حذف config cache يدوياً
rm -f bootstrap/cache/config.php

# 3. مسح الكاش
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. إعادة بناء config cache
php artisan config:cache
```

### الطريقة 3: استخدام sed (سريع)

```bash
cd /home/sarfesak/public_html/almyzan

# تغيير CACHE_DRIVER إلى file
sed -i 's/CACHE_DRIVER=database/CACHE_DRIVER=file/g' .env
sed -i 's/CACHE_STORE=database/CACHE_STORE=file/g' .env
sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/g' .env

# حذف config cache
rm -f bootstrap/cache/config.php

# مسح الكاش
php artisan config:clear
php artisan route:clear
php artisan view:clear

# إعادة بناء config cache
php artisan config:cache
```

---

## 🔄 بعد الإصلاح

بعد إصلاح مشكلة الكاش، يمكنك تشغيل:

```bash
# إعادة تعيين كاش الصلاحيات
php artisan permission:cache-reset

# أو مسح كل شيء
php artisan optimize:clear
```

---

## ✅ التحقق من الإعدادات

```bash
# التحقق من .env
grep "CACHE_DRIVER\|SESSION_DRIVER" .env

# يجب أن يظهر:
# CACHE_DRIVER=file
# SESSION_DRIVER=file
```

---

## ⚠️ ملاحظات

1. **CACHE_DRIVER=file** يعني أن الكاش سيُحفظ في ملفات بدلاً من قاعدة البيانات
2. **SESSION_DRIVER=file** يعني أن الجلسات ستُحفظ في ملفات بدلاً من قاعدة البيانات
3. هذا أفضل عندما تكون قاعدة البيانات غير متاحة أو بطيئة

---

## 📝 الملفات

- `fix_cache_issue.sh` - Script لإصلاح مشكلة الكاش
- `FIX_CACHE_README.md` - هذا الملف

---

**تم التجهيز بواسطة:** النظام الآلي  
**التاريخ:** 2026-01-11
