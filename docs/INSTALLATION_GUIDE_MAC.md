# دليل التثبيت على macOS - Installation Guide for macOS

## المتطلبات الأساسية - System Requirements

### البرمجيات المطلوبة:

1. **PHP 8.2 أو أحدث** (PHP >= 8.2.12)
2. **Composer** (مدير الحزم لـ PHP)
3. **MySQL أو MariaDB** (قاعدة البيانات)
4. **Node.js و npm** (لإدارة ملفات JavaScript/CSS)
5. **Git** (اختياري - لإدارة الإصدارات)

---

## خطوات التثبيت - Installation Steps

### 1. تثبيت Homebrew (إن لم يكن مثبتاً)

Homebrew هو مدير الحزم الموصى به لـ macOS:

```bash
# افتح Terminal واكتب:
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

بعد التثبيت، أضف Homebrew إلى PATH:
```bash
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
eval "$(/opt/homebrew/bin/brew shellenv)"
```

### 2. تثبيت PHP 8.2

```bash
# تثبيت PHP 8.2
brew install php@8.2

# ربط PHP مع النظام
brew link php@8.2

# التحقق من الإصدار
php -v
```

**ملاحظة:** يجب أن يكون الإصدار 8.2.12 أو أحدث.

### 3. تثبيت Composer

```bash
# تثبيت Composer
brew install composer

# التحقق من التثبيت
composer --version
```

### 4. تثبيت MySQL

```bash
# تثبيت MySQL
brew install mysql

# بدء خدمة MySQL
brew services start mysql

# تأمين MySQL (اختياري - لكن موصى به)
mysql_secure_installation
```

**أو يمكنك استخدام MariaDB:**
```bash
brew install mariadb
brew services start mariadb
```

### 5. تثبيت Node.js و npm

```bash
# تثبيت Node.js (يتضمن npm)
brew install node

# التحقق من الإصدارات
node -v
npm -v
```

---

## إعداد المشروع - Project Setup

### 1. الانتقال إلى مجلد المشروع

```bash
cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"
```

### 2. تثبيت التبعيات (Dependencies)

```bash
# تثبيت تبعيات PHP
composer install

# تثبيت تبعيات Node.js
npm install
```

### 3. إعداد ملف البيئة (.env)

```bash
# نسخ ملف البيئة
cp .env.example .env

# أو إنشاء ملف جديد إذا لم يكن موجوداً
touch .env
```

قم بتحرير ملف `.env` وأضف الإعدادات التالية:

```env
APP_NAME="Dr Alaa Medical System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000
APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=ar_SA

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dralmyzin
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. إنشاء مفتاح التطبيق (Application Key)

```bash
php artisan key:generate
```

### 5. إنشاء قاعدة البيانات

#### الطريقة الأولى: استخدام MySQL Command Line

```bash
# الدخول إلى MySQL
mysql -u root -p

# في MySQL، قم بإنشاء قاعدة البيانات:
CREATE DATABASE dralmyzin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# الخروج من MySQL
exit;
```

#### الطريقة الثانية: استخدام ملف SQL الموجود

```bash
# إذا كان هناك ملف create_database.sql
mysql -u root -p < database/create_database.sql
```

### 6. تشغيل Migrations (إنشاء الجداول)

```bash
php artisan migrate
```

### 7. تشغيل Seeders (إضافة بيانات تجريبية - اختياري)

```bash
php artisan db:seed
```

هذا سيضيف:
- مستخدم admin افتراضي
- بيانات تجريبية للأطباء والفئات

**معلومات تسجيل الدخول الافتراضية:**
- Email: `admin@example.com`
- Password: `password`

### 8. إنشاء رابط التخزين (Storage Link)

```bash
php artisan storage:link
```

### 9. تجميع ملفات CSS و JavaScript

```bash
# للتطوير (Development)
npm run dev

# للإنتاج (Production)
npm run build
```

---

## تشغيل المشروع - Running the Project

### 1. تشغيل خادم التطوير

```bash
php artisan serve
```

سيتم تشغيل المشروع على: `http://127.0.0.1:8000`

### 2. فتح المتصفح

افتح المتصفح وانتقل إلى:
```
http://localhost:8000
```

أو

```
http://127.0.0.1:8000
```

---

## حل المشاكل الشائعة - Troubleshooting

### مشكلة: PHP غير موجود

```bash
# التحقق من PHP
which php

# إذا لم يكن موجوداً، أضفه إلى PATH
export PATH="/opt/homebrew/bin:$PATH"
```

### مشكلة: Composer غير موجود

```bash
# إعادة تثبيت Composer
brew install composer
```

### مشكلة: MySQL لا يعمل

```bash
# التحقق من حالة MySQL
brew services list

# إعادة تشغيل MySQL
brew services restart mysql
```

### مشكلة: خطأ في قاعدة البيانات

```bash
# التحقق من اتصال قاعدة البيانات
mysql -u root -p -e "SHOW DATABASES;"

# التحقق من أن قاعدة البيانات موجودة
mysql -u root -p -e "USE dralmyzin; SHOW TABLES;"
```

### مشكلة: خطأ في الصلاحيات (Permissions)

```bash
# إعطاء صلاحيات للمجلدات
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/storage
```

### مشكلة: Cache لا يعمل

```bash
# مسح جميع الـ Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### مشكلة: خطأ 419 (CSRF Token)

إذا ظهر خطأ 419 PAGE EXPIRED:
1. تأكد من أن `APP_KEY` موجود في ملف `.env`
2. قم بمسح الـ cache:
```bash
php artisan config:clear
php artisan cache:clear
```
3. أعد تحميل الصفحة

---

## الأوامر المفيدة - Useful Commands

### أوامر Laravel الأساسية

```bash
# عرض جميع الأوامر المتاحة
php artisan list

# إنشاء Model جديد
php artisan make:model ModelName

# إنشاء Migration جديد
php artisan make:migration create_table_name

# تشغيل Migrations
php artisan migrate

# إرجاع آخر Migration
php artisan migrate:rollback

# إرجاع جميع Migrations
php artisan migrate:reset

# إعادة تشغيل Migrations
php artisan migrate:refresh

# إعادة تشغيل Migrations مع Seeders
php artisan migrate:fresh --seed

# مسح Cache
php artisan cache:clear

# مسح Config Cache
php artisan config:clear

# مسح Route Cache
php artisan route:clear

# مسح View Cache
php artisan view:clear

# عرض Routes
php artisan route:list

# فتح Tinker (Laravel REPL)
php artisan tinker
```

### أوامر Composer

```bash
# تثبيت التبعيات
composer install

# تحديث التبعيات
composer update

# تحديث autoload
composer dump-autoload
```

### أوامر npm

```bash
# تثبيت التبعيات
npm install

# تشغيل في وضع التطوير
npm run dev

# بناء للإنتاج
npm run build

# مشاهدة التغييرات (Watch)
npm run watch
```

---

## إعدادات إضافية - Additional Setup

### 1. إعداد Git (اختياري)

```bash
# تهيئة Git
git init

# إضافة جميع الملفات
git add .

# عمل Commit
git commit -m "Initial commit"
```

### 2. إعداد IDE (محرر الكود)

**الخيارات الموصى بها:**
- **VS Code** - مجاني وممتاز
- **PhpStorm** - مدفوع لكن قوي جداً
- **Sublime Text** - خفيف وسريع

### 3. إضافة Extensions مفيدة لـ VS Code

- PHP Intelephense
- Laravel Extension Pack
- Tailwind CSS IntelliSense
- Livewire

---

## التحقق من التثبيت - Verification Checklist

قبل البدء، تأكد من:

- [ ] PHP 8.2+ مثبت ويعمل
- [ ] Composer مثبت ويعمل
- [ ] MySQL/MariaDB مثبت ويعمل
- [ ] Node.js و npm مثبتان
- [ ] ملف `.env` موجود ومعد بشكل صحيح
- [ ] `APP_KEY` تم إنشاؤه
- [ ] قاعدة البيانات `dralmyzin` موجودة
- [ ] Migrations تم تشغيلها بنجاح
- [ ] `npm install` تم تنفيذه
- [ ] `npm run dev` يعمل
- [ ] `php artisan serve` يعمل
- [ ] يمكن الوصول للموقع من المتصفح

---

## معلومات إضافية - Additional Information

### البنية التحتية

- **Framework:** Laravel 11
- **Frontend:** Livewire 3.7, Tailwind CSS, DaisyUI
- **Database:** MySQL/MariaDB
- **PHP Version:** 8.2+
- **Node.js Version:** 18+ (موصى به)

### الروابط المفيدة

- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Homebrew Documentation](https://brew.sh)

### الدعم

إذا واجهت أي مشاكل:
1. راجع قسم "حل المشاكل الشائعة" أعلاه
2. راجع ملف `PROJECT_CONVERSATION.md` للتفاصيل التقنية
3. تحقق من ملفات الـ Log في `storage/logs/laravel.log`

---

**آخر تحديث:** 2025-12-10
**الإصدار:** 1.0

