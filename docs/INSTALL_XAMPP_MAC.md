# تثبيت XAMPP على macOS (الطريقة الأسهل)

## ما هو XAMPP؟

XAMPP هو حزمة تحتوي على:
- ✅ **Apache** (خادم الويب)
- ✅ **MySQL** (قاعدة البيانات)
- ✅ **PHP** (لغة البرمجة)
- ✅ **phpMyAdmin** (إدارة قاعدة البيانات)

**مميزات XAMPP:**
- سهل التثبيت (مثل Windows)
- كل شيء في مكان واحد
- لا يحتاج Terminal معقد
- واجهة رسومية سهلة

---

## خطوات التثبيت

### الخطوة 1: تحميل XAMPP

1. افتح المتصفح واذهب إلى:
   **https://www.apachefriends.org/download.html**

2. اختر إصدار macOS:
   - **XAMPP for macOS** (اختر الإصدار الأحدث)

3. اختر الإصدار:
   - **PHP 8.2.x** (موصى به للمشروع)
   - أو **PHP 8.1.x** (إذا لم يتوفر 8.2)

4. حمّل الملف (سيكون ملف `.dmg`)

### الخطوة 2: تثبيت XAMPP

1. افتح ملف `.dmg` الذي حمّلته
2. اسحب مجلد **XAMPP** إلى مجلد **Applications**
3. انتظر حتى ينتهي النسخ

### الخطوة 3: تشغيل XAMPP

1. اذهب إلى **Applications** (التطبيقات)
2. افتح مجلد **XAMPP**
3. انقر نقراً مزدوجاً على **XAMPP** (أو **manager-osx**)

### الخطوة 4: بدء الخدمات

في واجهة XAMPP:

1. اضغط على **Start** بجانب **Apache**
2. اضغط على **Start** بجانب **MySQL**
3. يجب أن يتحول اللون إلى **أخضر** ✅

---

## إعداد PHP مع Composer

### الخطوة 1: إضافة PHP إلى PATH

1. افتح Terminal
2. انسخ هذا الأمر:

```bash
echo 'export PATH="/Applications/XAMPP/xamppfiles/bin:$PATH"' >> ~/.zshrc
```

3. الصق في Terminal واضغط Enter

4. نفذ هذا الأمر:

```bash
source ~/.zshrc
```

### الخطوة 2: التحقق من PHP

```bash
php -v
```

يجب أن يظهر إصدار PHP من XAMPP.

### الخطوة 3: تثبيت Composer

```bash
# تحميل Composer
curl -sS https://getcomposer.org/installer | php

# نقل Composer إلى مجلد النظام
sudo mv composer.phar /usr/local/bin/composer

# إعطاء صلاحيات
chmod +x /usr/local/bin/composer

# التحقق من التثبيت
composer --version
```

**ملاحظة:** قد يطلب منك كلمة مرور Mac عند استخدام `sudo`.

---

## إعداد قاعدة البيانات

### الخطوة 1: إنشاء قاعدة البيانات

1. افتح المتصفح
2. اذهب إلى: **http://localhost/phpmyadmin**
3. انقر على **New** (جديد) في القائمة الجانبية
4. أدخل اسم قاعدة البيانات: **dralmyzin**
5. اختر **utf8mb4_unicode_ci** من القائمة المنسدلة
6. اضغط **Create** (إنشاء)

### أو استخدام Terminal:

```bash
# الدخول إلى MySQL
/Applications/XAMPP/xamppfiles/bin/mysql -u root -p

# في MySQL، أنشئ قاعدة البيانات:
CREATE DATABASE dralmyzin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# الخروج
exit;
```

---

## إعداد المشروع

### الخطوة 1: الانتقال لمجلد المشروع

```bash
cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"
```

### الخطوة 2: تثبيت التبعيات

```bash
# تثبيت تبعيات PHP
composer install

# تثبيت تبعيات Node.js (إذا كان Node.js مثبتاً)
npm install
```

### الخطوة 3: إعداد ملف .env

```bash
# نسخ ملف البيئة
cp .env.example .env

# تحرير ملف .env
```

افتح ملف `.env` وعدّل إعدادات قاعدة البيانات:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dralmyzin
DB_USERNAME=root
DB_PASSWORD=
```

### الخطوة 4: إنشاء مفتاح التطبيق

```bash
php artisan key:generate
```

### الخطوة 5: تشغيل Migrations

```bash
php artisan migrate

# (اختياري) إضافة بيانات تجريبية
php artisan db:seed
```

---

## تشغيل المشروع

### الطريقة 1: استخدام Laravel Serve (موصى به)

```bash
# في Terminal
php artisan serve
```

افتح المتصفح على: **http://localhost:8000**

### الطريقة 2: استخدام Apache من XAMPP

1. انسخ مجلد المشروع إلى:
   ```
   /Applications/XAMPP/xamppfiles/htdocs/
   ```

2. افتح المتصفح على:
   ```
   http://localhost/Dr-system/public
   ```

---

## معلومات تسجيل الدخول الافتراضية

- **Email:** `admin@example.com`
- **Password:** `password`

---

## حل المشاكل

### المشكلة: Apache لا يبدأ

**الحل:**
1. تأكد من عدم وجود برنامج آخر يستخدم المنفذ 80
2. جرب إعادة تشغيل XAMPP
3. تحقق من الصلاحيات

### المشكلة: MySQL لا يبدأ

**الحل:**
1. تأكد من عدم وجود MySQL مثبت مسبقاً
2. تحقق من المنفذ 3306
3. جرب إعادة تشغيل XAMPP

### المشكلة: PHP لا يعمل في Terminal

**الحل:**
1. تأكد من إضافة PATH (الخطوة 1 في إعداد PHP)
2. أغلق Terminal وافتحه من جديد
3. نفذ: `source ~/.zshrc`

### المشكلة: Composer لا يعمل

**الحل:**
1. تأكد من تثبيت Composer بشكل صحيح
2. تحقق من PATH
3. جرب: `which composer`

---

## مقارنة: XAMPP vs Homebrew

| الميزة | XAMPP | Homebrew |
|--------|-------|----------|
| سهولة التثبيت | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| واجهة رسومية | ✅ نعم | ❌ لا |
| مناسب للمبتدئين | ✅ نعم | ❌ لا |
| التحديثات | يدوي | تلقائي |
| الحجم | كبير (~150MB) | صغير |

---

## نصائح مهمة

1. **ابدأ XAMPP دائماً** قبل العمل على المشروع
2. **استخدم phpMyAdmin** لإدارة قاعدة البيانات بسهولة
3. **احفظ كلمة مرور MySQL** (عادة فارغة أو `root`)
4. **لا تغلق Terminal** أثناء تشغيل `php artisan serve`

---

## الأوامر السريعة

```bash
# بدء Apache و MySQL من Terminal
sudo /Applications/XAMPP/xamppfiles/xampp start

# إيقاف Apache و MySQL
sudo /Applications/XAMPP/xamppfiles/xampp stop

# إعادة التشغيل
sudo /Applications/XAMPP/xamppfiles/xampp restart

# فتح phpMyAdmin
open http://localhost/phpmyadmin
```

---

## الخلاصة

XAMPP هو الخيار الأفضل إذا كنت:
- ✅ تفضل الواجهة الرسومية
- ✅ تريد سهولة في الاستخدام
- ✅ معتاد على XAMPP من Windows
- ✅ لا تريد التعامل مع Terminal كثيراً

---

**آخر تحديث:** 2025-12-10

