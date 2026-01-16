# تشغيل النظام محلياً
# Start Local Server

---

## 🚀 التشغيل السريع

### الطريقة 1: استخدام Artisan Serve (الأسهل)

```bash
cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"
php artisan serve
```

**النتيجة:**
- السيرفر سيعمل على: `http://127.0.0.1:8000`
- أو: `http://localhost:8000`

---

## 🔧 خطوات إضافية (إذا لزم الأمر)

### 1. التأكد من تثبيت Dependencies

```bash
# تثبيت Composer Dependencies
composer install

# تثبيت npm Dependencies
npm install
```

### 2. إعداد ملف .env

```bash
# نسخ ملف .env.example إلى .env (إذا لم يكن موجوداً)
cp .env.example .env

# توليد APP_KEY
php artisan key:generate
```

### 3. إعداد قاعدة البيانات

تأكد من أن ملف `.env` يحتوي على:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

ثم:

```bash
# تشغيل Migrations
php artisan migrate

# تشغيل Seeders (اختياري)
php artisan db:seed --class=TestDataSeeder
```

### 4. تجميع الملفات (Frontend)

```bash
# تجميع الملفات للاستخدام المحلي
npm run dev

# أو للاستخدام Production
npm run build
```

---

## 🌐 الوصول إلى النظام

بعد تشغيل `php artisan serve`:

- **الرابط:** http://127.0.0.1:8000
- **أو:** http://localhost:8000

---

## 🔐 معلومات تسجيل الدخول

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

## 🛑 إيقاف السيرفر

اضغط `Ctrl + C` في Terminal الذي يعمل فيه السيرفر.

---

## 🔄 تشغيل على Port مختلف

```bash
php artisan serve --port=8080
```

---

## 📝 ملاحظات

1. **السيرفر يعمل في الخلفية** - يمكنك الاستمرار في استخدام Terminal
2. **لإيقاف السيرفر:** ابحث عن العملية واقتلها:
   ```bash
   lsof -ti:8000 | xargs kill
   ```
3. **لرؤية Logs:** 
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

**تم التجهيز بواسطة:** النظام الآلي  
**التاريخ:** 2026-01-11
