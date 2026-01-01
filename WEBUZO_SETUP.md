# إعداد Laravel على Webuzo | Laravel Setup on Webuzo

## خطوات سريعة | Quick Steps

### 1. إعداد PHP في Webuzo

1. افتح **PHP Settings** من لوحة Webuzo
2. تأكد من أن PHP 8.2 أو أعلى مفعّل
3. فعّل Extensions التالية:
   - ✅ `pdo_mysql`
   - ✅ `mbstring`
   - ✅ `openssl`
   - ✅ `tokenizer`
   - ✅ `xml`
   - ✅ `ctype`
   - ✅ `json`
   - ✅ `fileinfo`
   - ✅ `gd` أو `imagick` (للملفات)

### 2. إعداد قاعدة البيانات

1. من **MySQL Databases**:
   - أنشئ قاعدة بيانات: `dralmyzin`
   - أنشئ مستخدم: `dralmyzin_user`
   - امنح الصلاحيات الكاملة
   - سجل البيانات

### 3. رفع الملفات

#### عبر File Manager في Webuzo:
1. افتح **File Manager**
2. اذهب إلى `public_html`
3. ارفع جميع الملفات (استثناء `vendor` و `node_modules`)

#### أو عبر FTP:
- Host: `your-server-ip` أو `your-domain.com`
- Port: `21` (FTP) أو `22` (SFTP)
- Username: من Webuzo
- Password: من Webuzo

### 4. إعداد Document Root

**مهم جداً:** في Webuzo، يجب أن يكون Document Root يشير إلى مجلد `public`:

1. افتح **Apache Settings**
2. ابحث عن **Document Root**
3. غيّره إلى: `/home/your-username/public_html/public`
4. احفظ

### 5. إعداد .htaccess

تأكد من وجود ملف `.htaccess` في مجلد `public/`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 6. إعداد الأذونات

من **File Manager** في Webuzo:
1. اذهب إلى مجلد `storage`
2. اضغط **Change Permissions**
3. اضبط على `775`
4. كرر للمجلدات:
   - `storage/framework`
   - `storage/logs`
   - `storage/app/public`
   - `bootstrap/cache`

### 7. إعداد .env

1. انسخ `.env.example` إلى `.env`
2. عدّل البيانات:

```env
APP_NAME="Dr Alaa System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dralmyzin
DB_USERNAME=dralmyzin_user
DB_PASSWORD=your_password_here
```

3. من **Terminal** في Webuzo أو SSH:
```bash
cd /home/your-username/public_html
php artisan key:generate
```

### 8. تثبيت Dependencies

من **Terminal** في Webuzo:
```bash
cd /home/your-username/public_html

# Composer
composer install --optimize-autoloader --no-dev

# NPM
npm install
npm run build
```

### 9. تشغيل Migrations

```bash
php artisan migrate --force
php artisan storage:link
```

### 10. تنظيف الكاش

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## استكشاف الأخطاء الشائعة | Common Issues

### المشكلة: 500 Error
**الحل:**
```bash
# تحقق من السجلات
tail -f storage/logs/laravel.log

# تأكد من الأذونات
chmod -R 775 storage bootstrap/cache
```

### المشكلة: Assets لا تعمل
**الحل:**
```bash
npm run build
```

### المشكلة: Database Connection Failed
**الحل:**
- تحقق من بيانات `.env`
- تأكد من أن MySQL يعمل
- جرب `127.0.0.1` بدلاً من `localhost`

### المشكلة: Storage Files لا تظهر
**الحل:**
```bash
php artisan storage:link
# تأكد من وجود public/storage
```

---

## نصائح الأمان | Security Tips

1. ✅ اضبط `APP_DEBUG=false`
2. ✅ اضبط `APP_ENV=production`
3. ✅ استخدم SSL (HTTPS)
4. ✅ حماية ملف `.env`
5. ✅ تحديث Laravel بانتظام

---

## النسخ الاحتياطي | Backup

### من Webuzo:
1. افتح **Backup Manager**
2. اختر **Full Backup**
3. احفظ قاعدة البيانات والملفات

### يدوياً:
```bash
# قاعدة البيانات
mysqldump -u username -p dralmyzin > backup.sql

# الملفات
tar -czf backup-files.tar.gz /home/your-username/public_html
```

---

**تم الإعداد بنجاح! 🎉**
