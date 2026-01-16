# معلومات الاتصال بالسيرفر
# Server Connection Information

---

## 🔐 معلومات SSH

```bash
Host: server1
User: root
Port: 22
Path: /home/sarfesak/public_html/almyzan
```

**الاتصال:**
```bash
ssh root@server1
```

---

## 🌐 معلومات الموقع

```
URL: https://almyzan.baitpait.space
Domain: almyzan.baitpait.space
```

---

## 🗄️ معلومات قاعدة البيانات

```
DB_HOST: 127.0.0.1 (أو localhost)
DB_PORT: 3306
DB_DATABASE: sarfesak_almyzan
DB_USERNAME: sarfesak_sarfesak_almyzan
DB_PASSWORD: Gg65N$1mzL
```

**الاتصال من Terminal:**
```bash
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan
# Password: Gg65N$1mzL
```

---

## 📁 مسارات الملفات المهمة

```
Project Path: /home/sarfesak/public_html/almyzan
.env File: /home/sarfesak/public_html/almyzan/.env
Logs: /home/sarfesak/public_html/almyzan/storage/logs/laravel.log
Public: /home/sarfesak/public_html/almyzan/public
```

---

## 🔑 معلومات تسجيل الدخول

### Admin:
```
Email: admin@gmail.com
Password: 100200300
```

### Dr. Alaa:
```
Email: alaa@almyzan.ps
Password: password123
```

### Dr. Tariq:
```
Email: tariq@almyzan.ps
Password: password123
```

---

## 📋 أوامر مفيدة

### الانتقال إلى مجلد المشروع:
```bash
cd /home/sarfesak/public_html/almyzan
```

### عرض Logs:
```bash
tail -f storage/logs/laravel.log
```

### مسح الكاش:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### تشغيل Build:
```bash
./deploy_build.sh
```

### استيراد قاعدة البيانات:
```bash
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < database/full_database_fresh.sql
```

---

## 🔧 معلومات Git

```
Repository: https://github.com/baiitpait/almezanSystem.git
Branch: main
```

**جلب التحديثات:**
```bash
cd /home/sarfesak/public_html/almyzan
git pull origin main
```

---

## ⚙️ معلومات البيئة

```
APP_NAME: مركز الغد لجراحة العيون والليزك
APP_ENV: production
APP_URL: https://almyzan.baitpait.space
APP_TIMEZONE: Asia/Jerusalem
```

---

## 📝 ملاحظات مهمة

1. **CACHE_DRIVER**: يجب أن يكون `file` وليس `database`
2. **SESSION_DRIVER**: يجب أن يكون `file` وليس `database`
3. **DB_HOST**: استخدم `127.0.0.1` أو `localhost`
4. بعد أي تعديل على `.env`، يجب تشغيل:
   ```bash
   rm -f bootstrap/cache/config.php
   php artisan config:cache
   ```

---

**آخر تحديث:** 2026-01-11
