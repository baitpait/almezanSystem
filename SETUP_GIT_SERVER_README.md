# 🔧 إعداد Git على السيرفر من الصفر - Setup Git on Server

## المشكلة
عند محاولة `git pull origin main`، يظهر الخطأ:
```
fatal: not a git repository (or any of the parent directories): .git
```

## الحل

### الطريقة 1: استخدام السكريبت (موصى به)
```bash
cd /home/sarfesak/public_html/almyzan

# رفع السكريبت إلى السيرفر أولاً (إذا لم يكن موجوداً)
# أو استخدم wget/curl لتحميله من GitHub

# جعل السكريبت قابل للتنفيذ
chmod +x setup_git_on_server.sh

# تشغيل السكريبت
./setup_git_on_server.sh
```

### الطريقة 2: يدوياً
```bash
cd /home/sarfesak/public_html/almyzan

# 1. نسخ احتياطي لـ .env
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

# 2. تهيئة Git
git init
git branch -M main

# 3. إضافة Remote
git remote add origin https://github.com/baiitpait/almezanSystem.git

# 4. جلب من GitHub
git fetch origin

# 5. سحب الملفات
git pull origin main --allow-unrelated-histories
```

## بعد إعداد Git

### 1. استعادة .env
```bash
# إذا كان .env غير موجود، استعد من النسخة الاحتياطية
cp .env.backup.[timestamp] .env
```

### 2. تثبيت Dependencies
```bash
# Composer
composer install --no-dev --optimize-autoloader

# NPM
npm install
npm run build
```

### 3. إعداد Laravel
```bash
# تنظيف الكاش
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# إعادة بناء الكاش
php artisan config:cache
php artisan route:cache

# تحسين الأداء
php artisan optimize
```

### 4. إصلاح قاعدة البيانات (إذا لزم الأمر)
```bash
# إذا كان لديك بيانات موجودة في operation_notes
mysql -u [username] -p [database_name] < database/fix_operation_notes_table.sql
```

## ملاحظات مهمة

- السكريبت يقوم بنسخ احتياطي تلقائي لـ `.env`
- إذا كان لديك ملفات محلية مهمة، تأكد من نسخها احتياطياً قبل `git pull`
- بعد `git pull`، قد تحتاج إلى إعادة تثبيت Dependencies

## استكشاف الأخطاء

### مشكلة: خطأ في المصادقة مع GitHub
```bash
# استخدم Personal Access Token
git remote set-url origin https://[TOKEN]@github.com/baiitpait/almezanSystem.git
```

### مشكلة: تعارض في الملفات
```bash
# إذا كان هناك تعارض، احفظ ملفاتك أولاً ثم:
git stash
git pull origin main
git stash pop
```

### مشكلة: .env مفقود
```bash
# استعد من النسخة الاحتياطية
cp .env.backup.[timestamp] .env

# أو أنشئ ملف .env جديد من .env.example
cp .env.example .env
php artisan key:generate
```
