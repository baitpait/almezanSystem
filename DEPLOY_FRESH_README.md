# 🚀 دليل النشر الجديد من GitHub - Fresh Deployment Guide

## نظرة عامة
هذا السكريبت يقوم بمسح الملفات القديمة وجلب كل شيء من GitHub بشكل جديد.

## ⚠️ تحذيرات مهمة

### قبل التشغيل:
1. **نسخ احتياطي لـ .env** - السكريبت يقوم بنسخ احتياطي تلقائياً
2. **نسخ احتياطي لقاعدة البيانات** - إذا كان لديك بيانات مهمة
3. **التأكد من اتصال GitHub** - يجب أن يكون الـ repository متاح

### ما سيتم حذفه:
- ✅ `vendor/` - سيتم إعادة تثبيته
- ✅ `node_modules/` - سيتم إعادة تثبيته
- ✅ `public/build/` - سيتم إعادة بنائه
- ✅ `bootstrap/cache/*` - سيتم إعادة بنائه

### ما لن يتم حذفه:
- ✅ `.env` - محفوظ مع نسخة احتياطية
- ✅ `storage/` - محفوظ (الملفات المرفوعة)
- ✅ قاعدة البيانات - محفوظة

## خطوات الاستخدام

### 1. على السيرفر - رفع السكريبت
```bash
# رفع السكريبت إلى السيرفر (إذا لم يكن موجوداً)
# أو استخدم git pull لسحب التحديثات
cd /home/sarfesak/public_html/almyzan
git pull origin main
```

### 2. جعل السكريبت قابل للتنفيذ
```bash
chmod +x deploy_fresh_from_github.sh
```

### 3. تشغيل السكريبت
```bash
./deploy_fresh_from_github.sh
```

## ما يقوم به السكريبت

### 1. النسخ الاحتياطي
- نسخ `.env` إلى `.env.backup.[timestamp]`

### 2. حذف الملفات القديمة
- حذف `vendor/`
- حذف `node_modules/`
- حذف `public/build/`
- حذف `bootstrap/cache/*`

### 3. جلب من GitHub
- `git fetch origin`
- `git reset --hard origin/main`
- `git clean -fd`

### 4. تثبيت Dependencies
- `composer install --no-dev --optimize-autoloader`
- `npm install`

### 5. إعدادات الكاش
- تغيير `CACHE_DRIVER` إلى `file`
- تغيير `SESSION_DRIVER` إلى `file`

### 6. تنظيف وإعادة بناء
- تنظيف جميع أنواع الكاش
- إعادة بناء Config Cache
- إعادة بناء Route Cache
- بناء Assets (`npm run build`)
- تحسين الأداء (`php artisan optimize`)

### 7. إصلاح الصلاحيات
- إصلاح صلاحيات `storage/`
- إصلاح صلاحيات `bootstrap/cache/`
- إصلاح صلاحيات `public/`

## بعد التشغيل

### 1. التحقق من قاعدة البيانات
إذا كان لديك بيانات موجودة في `operation_notes`:
```bash
mysql -u [username] -p [database_name] < database/fix_operation_notes_table.sql
```

### 2. التحقق من .env
```bash
# إذا كان هناك مشاكل، استعد .env من النسخة الاحتياطية
cp .env.backup.[timestamp] .env
```

### 3. التحقق من النظام
- افتح الموقع في المتصفح
- تحقق من عدم وجود أخطاء
- تحقق من Console (F12) - لا يجب أن تكون هناك أخطاء JavaScript

## استكشاف الأخطاء

### مشكلة: فشل composer install
```bash
# تحقق من اتصال الإنترنت
# تحقق من صلاحيات الملفات
chmod -R 755 .
```

### مشكلة: فشل npm install
```bash
# تحقق من Node.js و npm
node --version
npm --version

# إذا لم يكن مثبتاً
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
```

### مشكلة: فشل npm run build
```bash
# تحقق من الصلاحيات
chmod -R 755 public
chown -R sarfesak:sarfesak public

# إنشاء مجلد build إذا لم يكن موجوداً
mkdir -p public/build
chmod -R 755 public/build
```

### مشكلة: خطأ في قاعدة البيانات
```bash
# تحقق من إعدادات .env
cat .env | grep DB_

# تحقق من اتصال قاعدة البيانات
mysql -u [username] -p [database_name] -e "SELECT 1;"
```

## ملاحظات إضافية

- السكريبت آمن - لا يحذف `.env` أو `storage/`
- النسخة الاحتياطية لـ `.env` محفوظة في نفس المجلد
- إذا فشل أي خطوة، السكريبت يتوقف ويعرض رسالة خطأ
- جميع الأوامر لها معالجة للأخطاء

## الدعم

إذا واجهت أي مشاكل:
1. تحقق من سجلات الأخطاء
2. راجع ملف `CODE_REVIEW_REPORT.md`
3. راجع ملف `SERVER_DEPLOY_FIX.md`
