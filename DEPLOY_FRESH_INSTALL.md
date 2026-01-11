# 🚀 دليل التثبيت الكامل من GitHub (بعد حذف الملفات)
## Complete Fresh Installation Guide from GitHub

---

## ⚠️ قبل البدء - Prerequisites

تأكد من أن لديك:
- ✅ وصول SSH إلى السيرفر
- ✅ معلومات قاعدة البيانات:
  - Database: `sarfesak_almyzan`
  - Username: `sarfesak_sarfesak_almyzan`
  - Password: `Gg65N$1mzL`
- ✅ Git Token: `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`

---

## 📋 الخطوات خطوة بخطوة - Step by Step

### الخطوة 1: الاتصال بالسيرفر
```bash
# اتصل بالسيرفر عبر SSH
ssh your-username@your-server-ip
# أو
ssh sarfesak@your-server-ip
```

---

### الخطوة 2: الانتقال إلى مجلد المشروع
```bash
# الانتقال إلى مجلد public_html
cd /home/sarfesak/public_html

# التحقق من المجلد الحالي
pwd
# يجب أن يظهر: /home/sarfesak/public_html
```

---

### الخطوة 3: جلب المشروع من GitHub
```bash
# جلب المشروع من GitHub
git clone https://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@github.com/baiitpait/almezanSystem.git almyzan

# الانتقال إلى مجلد المشروع
cd almyzan

# التحقق من الملفات
ls -la
# يجب أن ترى: app/, config/, database/, public/, resources/, إلخ
```

---

### الخطوة 4: إعداد ملف .env
```bash
# إنشاء ملف .env من المثال
cp .env.example .env

# تعديل ملف .env
nano .env
# أو
vi .env
```

**المحتوى المطلوب لملف .env:**
```env
APP_NAME="مركز الغد لجراحة العيون والليزك"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jerusalem
APP_URL=https://almyzan.baitpait.space

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sarfesak_almyzan
DB_USERNAME=sarfesak_sarfesak_almyzan
DB_PASSWORD=Gg65N$1mzL

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

LOG_CHANNEL=stack
LOG_LEVEL=debug
```

**بعد التعديل:**
- اضغط `Ctrl + X` (للخروج)
- اضغط `Y` (للحفظ)
- اضغط `Enter` (لتأكيد)

---

### الخطوة 5: توليد APP_KEY
```bash
# توليد مفتاح التطبيق
php artisan key:generate
```

---

### الخطوة 6: تثبيت التبعيات PHP
```bash
# تثبيت Composer Dependencies
composer install --no-dev --optimize-autoloader

# انتظر حتى يكتمل التثبيت (قد يستغرق بضع دقائق)
```

---

### الخطوة 7: تثبيت التبعيات Node.js
```bash
# تثبيت npm packages
npm install

# تجميع ملفات CSS/JS
npm run build

# انتظر حتى يكتمل التجميع
```

---

### الخطوة 8: إعداد قاعدة البيانات
```bash
# تشغيل Migrations لإنشاء الجداول
php artisan migrate --force

# التحقق من حالة Migrations
php artisan migrate:status

# يجب أن ترى جميع Migrations في حالة "Ran"
```

---

### الخطوة 9: إنشاء Storage Link
```bash
# إنشاء رابط التخزين
php artisan storage:link

# التحقق من الرابط
ls -la public/storage
# يجب أن ترى: public/storage -> ../storage/app/public
```

---

### الخطوة 10: إعداد الصلاحيات
```bash
# إعطاء صلاحيات للمجلدات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# إذا كان السيرفر يستخدم www-data
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

---

### الخطوة 11: تنظيف وتحسين الأداء
```bash
# تنظيف الكاش
php artisan optimize:clear

# إنشاء الكاش للإنتاج
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

### الخطوة 12: إنشاء مستخدم Admin
```bash
# إنشاء مستخدم admin
php artisan tinker
```

**في Tinker:**
```php
$user = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@gmail.com',
    'password' => Hash::make('admin123'),
    'role' => 'admin',
    'is_active' => true,
]);
$user->assignRole('admin');
exit
```

---

### الخطوة 13: التحقق من الإعداد
```bash
# التحقق من الملفات
ls -la

# التحقق من قاعدة البيانات
php artisan migrate:status

# التحقق من Storage Link
ls -la public/storage
```

---

### الخطوة 14: إعداد Document Root في Webuzo
1. افتح لوحة تحكم Webuzo
2. اذهب إلى **Apache Settings**
3. ابحث عن **Document Root**
4. غيّره من `/home/sarfesak/public_html/almyzan` إلى `/home/sarfesak/public_html/almyzan/public`
5. احفظ التغييرات

---

### الخطوة 15: اختبار الموقع
1. افتح المتصفح
2. اذهب إلى: `https://almyzan.baitpait.space`
3. يجب أن ترى صفحة تسجيل الدخول
4. جرب تسجيل الدخول:
   - Email: `admin@gmail.com`
   - Password: `admin123`

---

## ✅ قائمة التحقق النهائية - Final Checklist

- [ ] تم جلب المشروع من GitHub
- [ ] تم إنشاء ملف `.env` مع البيانات الصحيحة
- [ ] تم توليد `APP_KEY`
- [ ] تم تثبيت `composer install`
- [ ] تم تثبيت `npm install` و `npm run build`
- [ ] تم تشغيل `php artisan migrate --force`
- [ ] تم إنشاء `php artisan storage:link`
- [ ] تم إعطاء الصلاحيات للمجلدات
- [ ] تم تشغيل `php artisan optimize`
- [ ] تم إنشاء مستخدم admin
- [ ] تم تعديل Document Root في Webuzo
- [ ] الموقع يعمل بشكل صحيح

---

## 🔧 حل المشاكل - Troubleshooting

### مشكلة: خطأ في Git Clone
```bash
# إذا كان Git غير مثبت
# على Ubuntu/Debian:
sudo apt-get update
sudo apt-get install git

# على CentOS/RHEL:
sudo yum install git
```

### مشكلة: خطأ في Composer
```bash
# إذا كان Composer غير مثبت
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### مشكلة: خطأ في npm
```bash
# إذا كان Node.js غير مثبت
# على Ubuntu/Debian:
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
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
# إصلاح الصلاحيات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

---

## 📝 ملاحظات مهمة - Important Notes

1. **احفظ ملف .env دائماً** - يحتوي على معلومات حساسة
2. **لا تحذف مجلد vendor/** - يتم إنشاؤه بواسطة composer
3. **لا تحذف مجلد node_modules/** - يتم إنشاؤه بواسطة npm
4. **Document Root يجب أن يشير إلى `public/`** - مهم جداً!
5. **تحقق من PHP Version** - يجب أن يكون 8.2 أو أعلى

---

**آخر تحديث:** 2026-01-11
