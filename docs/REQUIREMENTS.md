# البرمجيات المطلوبة لتشغيل المشروع على macOS

## قائمة البرمجيات الأساسية

### 1. PHP 8.2 أو أحدث
- **الإصدار المطلوب:** PHP >= 8.2.12
- **طريقة التثبيت:**
  ```bash
  brew install php@8.2
  brew link php@8.2
  ```
- **التحقق من التثبيت:**
  ```bash
  php -v
  ```

### 2. Composer
- **الوصف:** مدير الحزم لـ PHP
- **طريقة التثبيت:**
  ```bash
  brew install composer
  ```
- **التحقق من التثبيت:**
  ```bash
  composer --version
  ```

### 3. MySQL أو MariaDB
- **الوصف:** قاعدة البيانات
- **طريقة التثبيت:**
  ```bash
  brew install mysql
  # أو
  brew install mariadb
  ```
- **بدء الخدمة:**
  ```bash
  brew services start mysql
  ```
- **التحقق من التثبيت:**
  ```bash
  mysql --version
  ```

### 4. Node.js و npm
- **الوصف:** لإدارة ملفات JavaScript و CSS
- **طريقة التثبيت:**
  ```bash
  brew install node
  ```
- **التحقق من التثبيت:**
  ```bash
  node -v
  npm -v
  ```

### 5. Homebrew (مدير الحزم لـ macOS)
- **الوصف:** ضروري لتثبيت البرمجيات الأخرى
- **طريقة التثبيت:**
  ```bash
  /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
  ```
- **الموقع:** https://brew.sh

---

## التثبيت السريع لجميع المتطلبات

```bash
# تثبيت Homebrew (إن لم يكن مثبتاً)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# تثبيت جميع المتطلبات دفعة واحدة
brew install php@8.2 composer mysql node

# بدء خدمة MySQL
brew services start mysql

# ربط PHP مع النظام
brew link php@8.2
```

---

## التحقق من التثبيت

بعد التثبيت، تحقق من أن كل شيء يعمل:

```bash
# التحقق من PHP
php -v
# يجب أن يظهر: PHP 8.2.x

# التحقق من Composer
composer --version
# يجب أن يظهر: Composer version x.x.x

# التحقق من MySQL
mysql --version
# يجب أن يظهر: mysql Ver x.x.x

# التحقق من Node.js
node -v
# يجب أن يظهر: v18.x.x أو أحدث

# التحقق من npm
npm -v
# يجب أن يظهر: 9.x.x أو أحدث
```

---

## ملاحظات مهمة

1. **PHP:** يجب أن يكون الإصدار 8.2.12 أو أحدث
2. **MySQL:** يمكن استخدام MariaDB كبديل
3. **Node.js:** يوصى بالإصدار 18 أو أحدث
4. **Homebrew:** يجب تثبيته أولاً قبل تثبيت البرمجيات الأخرى

---

## روابط التحميل المباشر (بدون Homebrew)

إذا كنت تفضل التحميل المباشر:

- **PHP:** https://www.php.net/downloads.php
- **Composer:** https://getcomposer.org/download/
- **MySQL:** https://dev.mysql.com/downloads/mysql/
- **Node.js:** https://nodejs.org/

**ملاحظة:** استخدام Homebrew هو الطريقة الموصى بها على macOS

---

**آخر تحديث:** 2025-12-10

