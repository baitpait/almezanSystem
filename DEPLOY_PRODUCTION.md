# إعداد النظام على السيرفر الإنتاجي

## معلومات قاعدة البيانات
```
اسم قاعدة البيانات: sarfesak_almyzan
اسم المستخدم: sarfesak_sarfesak_almyzan
كلمة المرور: Gg65N$1mzL
الرابط: https://almyzan.baitpait.space
```

## خطوات النشر

### 1. رفع الملفات على السيرفر
```bash
# انقل جميع ملفات المشروع إلى مجلد السيرفر
# تأكد من أن public_html أو مجلد الويب يشير إلى مجلد public/
```

### 2. إعداد ملف .env
قم بإنشاء ملف `.env` في المجلد الجذر للمشروع بالمحتوى التالي:
```env
APP_NAME="Dr System"
APP_ENV=production
APP_KEY=base64:LfRlJgJv9g/OZ6IzIchC33C6VIR4VoP9OOvr9UZBCb4=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jerusalem
APP_URL=https://almyzan.baitpait.space

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sarfesak_almyzan
DB_USERNAME=sarfesak_sarfesak_almyzan
DB_PASSWORD=Gg65N$1mzL
```

### 3. تثبيت التبعيات
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 4. إعداد قاعدة البيانات
```bash
php artisan migrate --seed
php artisan storage:link
```

### 5. تنظيف وتحسين الأداء
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 6. إعداد الصلاحيات
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## التحقق من النشر
- افتح الرابط: https://almyzan.baitpait.space
- تأكد من عمل جميع الوظائف
- تحقق من اتصال قاعدة البيانات

## بيانات الدخول الافتراضية
```
البريد الإلكتروني: admin@gmail.com
كلمة المرور: admin123
```

## ملاحظات مهمة
- تم تعطيل وضع التصحيح (DEBUG=false)
- تم تفعيل البيئة الإنتاجية
- تم تحديث إعدادات قاعدة البيانات للإنتاج
