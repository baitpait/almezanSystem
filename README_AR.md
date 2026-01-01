# نظام إدارة مركز الغد لجراحة العيون والليزك
## Medical Management System for Al-Ghad Eye Surgery Center

---

## نظرة عامة - Overview

نظام شامل لإدارة العمليات الجراحية والمرضى في مركز الغد لجراحة العيون والليزك. يتضمن إدارة كاملة للمرضى، المواعيد، الفواتير، والعمليات الجراحية مع بيانات طبية مفصلة.

A comprehensive medical management system for Al-Ghad Eye Surgery Center. Includes complete patient, appointment, invoice, and surgical operation management with detailed medical data.

---

## المتطلبات - Requirements

### للـ macOS:

1. **PHP 8.2+** (PHP >= 8.2.12)
2. **Composer** (PHP Package Manager)
3. **MySQL أو MariaDB** (قاعدة البيانات)
4. **Node.js و npm** (لإدارة ملفات JavaScript/CSS)

---

## التثبيت السريع - Quick Installation

### الخطوة 1: تثبيت المتطلبات

```bash
# تثبيت Homebrew (إن لم يكن مثبتاً)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# تثبيت جميع المتطلبات
brew install php@8.2 composer mysql node

# بدء خدمة MySQL
brew services start mysql
```

### الخطوة 2: تثبيت التبعيات

```bash
# الانتقال لمجلد المشروع
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

## الميزات الرئيسية - Main Features

1. ✅ **إدارة المرضى** - Patient Management
2. ✅ **إدارة المواعيد** - Appointment Management
3. ✅ **إدارة الفواتير** - Invoice Management
4. ✅ **إدارة العمليات الجراحية** - Surgical Operations Management
   - Pre-operative assessment
   - Target parameters planning
   - Refractive profiles
   - Medical history
   - Eye examinations
   - Ectasia risk assessment
   - Recommendations
5. ✅ **نظام الموافقات** - Approval System
6. ✅ **لوحة تحكم** - Dashboard with Statistics

---

## البنية التقنية - Technology Stack

- **Backend:** Laravel 11, PHP 8.2+
- **Frontend:** Livewire 3.7, Tailwind CSS, DaisyUI
- **Database:** MySQL/MariaDB
- **Font:** Cairo (Google Fonts)

---

## التوثيق - Documentation

- **دليل التثبيت الكامل:** `docs/INSTALLATION_GUIDE_MAC.md`
- **دليل البدء السريع:** `docs/QUICK_START_MAC.md`
- **توثيق المشروع الكامل:** `docs/PROJECT_CONVERSATION.md`

---

## الأوامر المفيدة - Useful Commands

```bash
# تشغيل Migrations
php artisan migrate

# إعادة تشغيل Migrations مع Seeders
php artisan migrate:fresh --seed

# مسح Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# تجميع ملفات CSS/JS
npm run dev        # للتطوير
npm run build      # للإنتاج
```

---

## حل المشاكل الشائعة - Troubleshooting

### خطأ في PHP:
```bash
brew link php@8.2
```

### خطأ في MySQL:
```bash
brew services restart mysql
```

### خطأ 419 (CSRF Token):
```bash
php artisan config:clear
php artisan cache:clear
```

### خطأ في الصلاحيات:
```bash
chmod -R 775 storage bootstrap/cache
```

---

## الدعم - Support

للمزيد من المعلومات، راجع ملفات التوثيق في مجلد `docs/`

---

**آخر تحديث:** 2025-12-10
**الإصدار:** 1.0

