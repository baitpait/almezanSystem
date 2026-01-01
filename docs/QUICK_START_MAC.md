# دليل البدء السريع على macOS - Quick Start Guide

## المتطلبات الأساسية (يجب تثبيتها أولاً)

1. **PHP 8.2+**
2. **Composer**
3. **MySQL/MariaDB**
4. **Node.js و npm**

---

## التثبيت السريع (5 خطوات)

### الخطوة 1: تثبيت المتطلبات

```bash
# تثبيت Homebrew (إن لم يكن مثبتاً)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# تثبيت جميع المتطلبات
brew install php@8.2 composer mysql node

# بدء خدمة MySQL
brew services start mysql
```

### الخطوة 2: الانتقال للمشروع وتثبيت التبعيات

```bash
cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"

# تثبيت تبعيات PHP
composer install

# تثبيت تبعيات Node.js
npm install
```

### الخطوة 3: إعداد قاعدة البيانات

```bash
# إنشاء قاعدة البيانات
mysql -u root -p -e "CREATE DATABASE dralmyzin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### الخطوة 4: إعداد المشروع

```bash
# نسخ ملف البيئة
cp .env.example .env

# إنشاء مفتاح التطبيق
php artisan key:generate

# تشغيل Migrations
php artisan migrate

# (اختياري) إضافة بيانات تجريبية
php artisan db:seed
```

### الخطوة 5: تشغيل المشروع

```bash
# في Terminal الأول: تجميع ملفات CSS/JS
npm run dev

# في Terminal الثاني: تشغيل الخادم
php artisan serve
```

افتح المتصفح على: **http://localhost:8000**

---

## معلومات تسجيل الدخول الافتراضية

- **Email:** `admin@example.com`
- **Password:** `password`

---

## حل المشاكل السريع

### إذا ظهر خطأ في PHP:
```bash
brew link php@8.2
```

### إذا ظهر خطأ في MySQL:
```bash
brew services restart mysql
```

### إذا ظهر خطأ 419:
```bash
php artisan config:clear
php artisan cache:clear
```

### إذا ظهر خطأ في الصلاحيات:
```bash
chmod -R 775 storage bootstrap/cache
```

---

**للمزيد من التفاصيل، راجع:** `docs/INSTALLATION_GUIDE_MAC.md`

