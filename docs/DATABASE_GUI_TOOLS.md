# واجهات رسومية لإدارة قواعد البيانات على macOS

## الخيارات المتاحة

عند استخدام Homebrew، يمكنك استخدام واجهات رسومية لإدارة قاعدة البيانات MySQL. إليك أفضل الخيارات:

---

## 1. phpMyAdmin (الأشهر والأسهل) ⭐⭐⭐⭐⭐

### المميزات:
- ✅ مجاني تماماً
- ✅ يعمل من المتصفح (لا يحتاج تثبيت)
- ✅ واجهة عربية متاحة
- ✅ سهل الاستخدام
- ✅ مناسب للمبتدئين

### التثبيت:

```bash
# تثبيت phpMyAdmin
brew install phpmyadmin

# أو تثبيت يدوي (أسهل)
brew tap homebrew/php
brew install phpmyadmin
```

### الاستخدام:

1. تأكد من أن Apache و MySQL يعملان
2. افتح المتصفح على: **http://localhost/phpmyadmin**
3. اسم المستخدم: **root**
4. كلمة المرور: (فارغة أو كلمة المرور التي حددتها)

### أو استخدام Laravel:

إذا كان لديك Laravel يعمل، يمكنك الوصول عبر:
```
http://localhost:8000/phpmyadmin
```

---

## 2. TablePlus (الأفضل والأجمل) ⭐⭐⭐⭐⭐

### المميزات:
- ✅ واجهة جميلة جداً
- ✅ سريع جداً
- ✅ يدعم MySQL, PostgreSQL, SQLite
- ✅ مجاني (مع بعض القيود)
- ✅ مناسب للمطورين

### التثبيت:

```bash
# تثبيت TablePlus
brew install --cask tableplus
```

### أو التحميل المباشر:
- الموقع: **https://tableplus.com**
- حمّل النسخة المجانية

### الاستخدام:
1. افتح TablePlus
2. اضغط **Create a new connection**
3. اختر **MySQL**
4. أدخل:
   - **Host:** `127.0.0.1` أو `localhost`
   - **User:** `root`
   - **Password:** (فارغة أو كلمة المرور)
   - **Database:** `dralmyzin`
5. اضغط **Connect**

---

## 3. Sequel Pro (مجاني تماماً) ⭐⭐⭐⭐

### المميزات:
- ✅ مجاني 100%
- ✅ خفيف وسريع
- ✅ واجهة بسيطة
- ✅ مناسب لـ MySQL فقط

### التثبيت:

```bash
# تثبيت Sequel Pro
brew install --cask sequel-pro
```

### أو التحميل المباشر:
- الموقع: **https://www.sequelpro.com**

### الاستخدام:
1. افتح Sequel Pro
2. أدخل:
   - **Host:** `127.0.0.1`
   - **Username:** `root`
   - **Password:** (فارغة)
   - **Database:** `dralmyzin`
3. اضغط **Connect**

---

## 4. MySQL Workbench (رسمي من Oracle) ⭐⭐⭐⭐

### المميزات:
- ✅ رسمي من Oracle
- ✅ قوي جداً
- ✅ ✅ تصميم قاعدة البيانات
- ✅ أدوات متقدمة

### التثبيت:

```bash
# تثبيت MySQL Workbench
brew install --cask mysql-workbench
```

### أو التحميل المباشر:
- الموقع: **https://dev.mysql.com/downloads/workbench/**

---

## 5. DBeaver (مجاني ومفتوح المصدر) ⭐⭐⭐⭐

### المميزات:
- ✅ مجاني تماماً
- ✅ يدعم جميع قواعد البيانات
- ✅ قوي جداً
- ✅ مناسب للمطورين المحترفين

### التثبيت:

```bash
# تثبيت DBeaver
brew install --cask dbeaver-community
```

---

## التوصية: TablePlus أو phpMyAdmin

### للمبتدئين: **phpMyAdmin**
- ✅ سهل جداً
- ✅ يعمل من المتصفح
- ✅ لا يحتاج تثبيت معقد

### للمطورين: **TablePlus**
- ✅ واجهة جميلة
- ✅ سريع جداً
- ✅ تجربة استخدام ممتازة

---

## التثبيت السريع (اختر واحد)

### خيار 1: phpMyAdmin (الأسهل)

```bash
# تثبيت Apache (إذا لم يكن مثبتاً)
brew install httpd

# تثبيت phpMyAdmin
brew install phpmyadmin

# بدء Apache
brew services start httpd
```

ثم افتح: **http://localhost/phpmyadmin**

### خيار 2: TablePlus (الأجمل)

```bash
brew install --cask tableplus
```

ثم افتح TablePlus من Applications.

### خيار 3: Sequel Pro (المجاني)

```bash
brew install --cask sequel-pro
```

---

## إنشاء قاعدة البيانات باستخدام الواجهة الرسومية

### باستخدام phpMyAdmin:

1. افتح **http://localhost/phpmyadmin**
2. انقر **New** (جديد) في القائمة الجانبية
3. أدخل اسم قاعدة البيانات: **dralmyzin**
4. اختر **utf8mb4_unicode_ci** من القائمة
5. اضغط **Create**

### باستخدام TablePlus:

1. افتح TablePlus
2. اتصل بـ MySQL
3. انقر بزر الماوس الأيمن على **Databases**
4. اختر **New Database**
5. أدخل الاسم: **dralmyzin**
6. اختر **utf8mb4_unicode_ci**
7. اضغط **Create**

### باستخدام Sequel Pro:

1. افتح Sequel Pro
2. اتصل بـ MySQL
3. من القائمة: **Database** → **Add Database...**
4. أدخل الاسم: **dralmyzin**
5. اضغط **Add**

---

## مقارنة سريعة

| الأداة | السعر | السهولة | السرعة | المميزات |
|--------|-------|---------|--------|----------|
| **phpMyAdmin** | مجاني | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | يعمل من المتصفح |
| **TablePlus** | مجاني* | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | أجمل واجهة |
| **Sequel Pro** | مجاني | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | بسيط وسريع |
| **MySQL Workbench** | مجاني | ⭐⭐⭐ | ⭐⭐⭐⭐ | رسمي من Oracle |
| **DBeaver** | مجاني | ⭐⭐⭐ | ⭐⭐⭐⭐ | يدعم كل شيء |

*TablePlus: مجاني مع قيود، مدفوع للميزات المتقدمة

---

## نصيحتي الشخصية

**ابدأ بـ TablePlus** - أجمل واجهة وأسهل استخدام. إذا واجهت مشاكل، استخدم **phpMyAdmin** لأنه يعمل من المتصفح مباشرة.

---

## خطوات سريعة (TablePlus)

```bash
# 1. تثبيت TablePlus
brew install --cask tableplus

# 2. افتح TablePlus من Applications

# 3. أنشئ اتصال جديد:
#    - Host: 127.0.0.1
#    - User: root
#    - Password: (فارغة)
#    - Database: dralmyzin

# 4. اضغط Connect
```

---

**آخر تحديث:** 2025-12-10

