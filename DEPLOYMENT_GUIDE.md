# دليل النشر على سيرفر VPS Ubuntu مع Webuzo
## Deployment Guide for VPS Ubuntu with Webuzo

---

## المتطلبات الأساسية | Prerequisites

### على السيرفر | On Server:
- ✅ PHP 8.2 أو أعلى
- ✅ Composer
- ✅ Node.js و npm
- ✅ MySQL/MariaDB
- ✅ Apache/Nginx (عادة متوفر مع Webuzo)
- ✅ Git (اختياري)

---

## الخطوة 1: إعداد قاعدة البيانات | Step 1: Database Setup

### من لوحة تحكم Webuzo:
1. افتح **MySQL Databases**
2. أنشئ قاعدة بيانات جديدة (مثلاً: `dralmyzin`)
3. أنشئ مستخدم MySQL جديد
4. امنح المستخدم صلاحيات كاملة على قاعدة البيانات
5. سجل:
   - اسم قاعدة البيانات
   - اسم المستخدم
   - كلمة المرور
   - Host (عادة `localhost`)

---

## الخطوة 2: رفع الملفات | Step 2: Upload Files

### الطريقة الأولى: عبر FTP/SFTP
1. استخدم FileZilla أو أي عميل FTP
2. اتصل بالسيرفر
3. ارفع جميع الملفات إلى المجلد الرئيسي للموقع (عادة `public_html` أو `httpdocs`)

### الطريقة الثانية: عبر Git (موصى بها)
```bash
# على السيرفر
cd /home/your-username/public_html
git clone https://your-repository-url.git .
```

### الملفات التي يجب رفعها:
- ✅ جميع ملفات المشروع عدا:
  - `vendor/` (سيتم تثبيتها لاحقاً)
  - `node_modules/` (سيتم تثبيتها لاحقاً)
  - `.env` (سيتم إنشاؤه لاحقاً)
  - `storage/logs/*` (يجب أن يكون المجلد موجوداً لكن فارغاً)

---

## الخطوة 3: إعداد ملف .env | Step 3: Configure .env File

### على السيرفر:
1. انسخ `.env.example` إلى `.env`:
```bash
cp .env.example .env
```

2. عدّل ملف `.env`:
```env
APP_NAME="Dr Alaa System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dralmyzin
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Mail Configuration (إذا كنت تستخدم البريد الإلكتروني)
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

3. أنشئ APP_KEY:
```bash
php artisan key:generate
```

---

## الخطوة 4: تثبيت Dependencies | Step 4: Install Dependencies

### على السيرفر:
```bash
# تثبيت PHP dependencies
composer install --optimize-autoloader --no-dev

# تثبيت Node.js dependencies
npm install

# بناء الأصول (Assets)
npm run build
```

---

## الخطوة 5: إعداد الأذونات | Step 5: Set Permissions

### على السيرفر:
```bash
# إعطاء صلاحيات الكتابة للمجلدات المطلوبة
chmod -R 775 storage bootstrap/cache
chmod -R 775 storage/app/public

# إنشاء الرابط الرمزي للملفات العامة
php artisan storage:link
```

**ملاحظة:** في Webuzo، قد تحتاج إلى تعديل الأذونات من لوحة التحكم أيضاً.

---

## الخطوة 6: تشغيل Migrations | Step 6: Run Migrations

### على السيرفر:
```bash
# تشغيل جميع Migrations
php artisan migrate --force

# (اختياري) إذا كنت تريد إضافة بيانات تجريبية
# php artisan db:seed
```

---

## الخطوة 7: تنظيف الكاش | Step 7: Clear Cache

### على السيرفر:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## الخطوة 8: إعداد Apache/Nginx | Step 8: Configure Web Server

### إذا كان Apache:
تأكد من أن ملف `.htaccess` موجود في مجلد `public/`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### في Webuzo:
1. افتح **Apache Settings**
2. تأكد من تفعيل `mod_rewrite`
3. حدد Document Root إلى: `/home/your-username/public_html/public`

---

## الخطوة 9: إعداد SSL (اختياري لكن موصى به) | Step 9: SSL Setup

### من لوحة Webuzo:
1. افتح **SSL Certificates**
2. قم بتثبيت شهادة SSL (Let's Encrypt مجانية)
3. فعّل HTTPS

---

## الخطوة 10: اختبار الموقع | Step 10: Test Website

1. افتح المتصفح واذهب إلى: `https://your-domain.com`
2. تأكد من:
   - ✅ الصفحة الرئيسية تظهر
   - ✅ تسجيل الدخول يعمل
   - ✅ قاعدة البيانات متصلة
   - ✅ الملفات المرفوعة تعمل

---

## استكشاف الأخطاء | Troubleshooting

### خطأ: 500 Internal Server Error
```bash
# تحقق من ملفات السجل
tail -f storage/logs/laravel.log

# تأكد من الأذونات
chmod -R 775 storage bootstrap/cache
```

### خطأ: Database Connection Failed
- تحقق من بيانات الاتصال في `.env`
- تأكد من أن MySQL يعمل
- تحقق من أن المستخدم لديه صلاحيات

### خطأ: Storage Link Not Working
```bash
php artisan storage:link
# تأكد من أن public/storage موجود
```

### خطأ: Assets Not Loading
```bash
# أعد بناء الأصول
npm run build
```

---

## الأمان | Security

### تأكد من:
1. ✅ `APP_DEBUG=false` في `.env`
2. ✅ `APP_ENV=production` في `.env`
3. ✅ حماية ملف `.env` (لا يجب أن يكون قابل للوصول من المتصفح)
4. ✅ تحديث Laravel و Dependencies بانتظام
5. ✅ استخدام كلمات مرور قوية

---

## النسخ الاحتياطي | Backup

### قاعدة البيانات:
```bash
# من Webuzo: MySQL Backup
# أو من Terminal:
mysqldump -u username -p dralmyzin > backup.sql
```

### الملفات:
- استخدم Webuzo Backup أو
- رفع نسخة احتياطية من مجلد المشروع

---

## التحديثات المستقبلية | Future Updates

```bash
# 1. سحب التحديثات
git pull origin main

# 2. تحديث Dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 3. تشغيل Migrations الجديدة
php artisan migrate --force

# 4. تنظيف الكاش
php artisan optimize
```

---

## معلومات الاتصال | Contact Info

إذا واجهت أي مشاكل، تحقق من:
- Laravel Logs: `storage/logs/laravel.log`
- Apache/Nginx Error Logs
- Webuzo Error Logs

---

**تم النشر بنجاح! 🎉**
