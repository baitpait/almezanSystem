# 🔧 إصلاح مشاكل الاتصال بقاعدة البيانات
## Fix Database Connection Issues

---

## المشكلة: SQLSTATE[HY000] [2002] No such file or directory

هذه المشكلة تحدث عندما لا يستطيع Laravel الاتصال بـ MySQL socket.

---

## الحلول:

### الحل 1: التحقق من إعدادات .env

```bash
cd /home/sarfesak/public_html/almyzan

# فتح ملف .env
nano .env
```

**تأكد من أن إعدادات قاعدة البيانات صحيحة:**
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sarfesak_almyzan
DB_USERNAME=sarfesak_sarfesak_almyzan
DB_PASSWORD=Gg65N$1mzL
```

**إذا لم يعمل، جرب:**
```env
DB_HOST=127.0.0.1
```

أو:
```env
DB_HOST=/var/run/mysqld/mysqld.sock
```

---

### الحل 2: إيجاد مسار MySQL Socket

```bash
# البحث عن MySQL socket
find /var -name "mysql.sock" 2>/dev/null
find /var -name "mysqld.sock" 2>/dev/null
find /tmp -name "mysql.sock" 2>/dev/null

# أو
mysql_config --socket
```

**بعد إيجاد المسار، عدّل .env:**
```env
DB_HOST=/path/to/mysql.sock
```

---

### الحل 3: التحقق من حالة MySQL

```bash
# التحقق من حالة MySQL
systemctl status mysql
# أو
service mysql status

# إذا لم يكن مشغولاً، ابدأه:
systemctl start mysql
# أو
service mysql start
```

---

### الحل 4: اختبار الاتصال بقاعدة البيانات

```bash
# اختبار الاتصال مباشرة
mysql -u sarfesak_sarfesak_almyzan -p'Gg65N$1mzL' -h localhost sarfesak_almyzan

# إذا نجح، يجب أن ترى:
# mysql>
```

---

### الحل 5: استخدام IP بدلاً من localhost

```bash
# في ملف .env
DB_HOST=127.0.0.1
```

---

### الحل 6: التحقق من معلومات قاعدة البيانات في Webuzo

1. افتح لوحة تحكم Webuzo
2. اذهب إلى **MySQL Databases**
3. تحقق من:
   - اسم قاعدة البيانات
   - اسم المستخدم
   - كلمة المرور
   - Host (قد يكون `localhost` أو `127.0.0.1`)

---

## بعد إصلاح .env:

```bash
# مسح الكاش
php artisan config:clear

# اختبار الاتصال
php artisan migrate:status

# إذا نجح، شغّل Migrations
php artisan migrate --force
```

---

## أوامر سريعة للتحقق:

```bash
# 1. التحقق من MySQL socket
ls -la /var/run/mysqld/mysqld.sock
ls -la /tmp/mysql.sock

# 2. اختبار الاتصال
mysql -u sarfesak_sarfesak_almyzan -p'Gg65N$1mzL' -h localhost -e "SHOW DATABASES;"

# 3. التحقق من قاعدة البيانات
mysql -u sarfesak_sarfesak_almyzan -p'Gg65N$1mzL' -h localhost -e "USE sarfesak_almyzan; SHOW TABLES;"
```

---

**ملاحظة:** في Webuzo، عادة ما يكون `DB_HOST=localhost` أو `DB_HOST=127.0.0.1` يعمل. إذا لم يعمل، جرب إيجاد MySQL socket path.
