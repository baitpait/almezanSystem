# تعليمات إعداد ملف .env على السيرفر
# Instructions for Setting Up .env File on Server

---

## 📋 المعلومات المطلوبة | Required Information

✅ **قاعدة البيانات:** `sarfesak_almyzan`  
✅ **اسم المستخدم:** `sarfesak_sarfesak_almyzan`  
✅ **كلمة المرور:** `Gg65N$1mzL`  
✅ **رابط الموقع:** `https://almyzan.baitpait.space`  

---

## 🚀 خطوات الإعداد | Setup Steps

### على السيرفر (Webuzo):

#### الطريقة الأولى: عبر File Manager
1. افتح **File Manager** في Webuzo
2. اذهب إلى مجلد المشروع (`public_html`)
3. انسخ ملف `.env.production` أو `ENV_PRODUCTION.txt`
4. أعد تسميته إلى `.env`
5. تأكد من أن الملف موجود في الجذر الرئيسي للمشروع

#### الطريقة الثانية: عبر Terminal/SSH
```bash
cd /home/your-username/public_html

# انسخ ملف .env.production إلى .env
cp .env.production .env

# أو إذا كان الملف ENV_PRODUCTION.txt
cp ENV_PRODUCTION.txt .env
```

---

## ⚙️ بعد رفع ملف .env

### 1. توليد APP_KEY
```bash
php artisan key:generate
```

### 2. اختبار الاتصال بقاعدة البيانات
```bash
# أولاً: تشغيل Migrations لإنشاء الجداول
php artisan migrate --force

# ثم: التحقق من الحالة
php artisan migrate:status
```

### 3. تشغيل Migrations (إذا لم تكن قد شغلت)
```bash
php artisan migrate --force
```

### 4. إنشاء Storage Link
```bash
php artisan storage:link
```

### 5. تنظيف الكاش
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## ✅ التحقق من الإعدادات

### تحقق من ملف .env:
```bash
# تأكد من أن الملف موجود
ls -la .env

# تحقق من محتوى الملف (بدون عرض كلمة المرور)
grep -v "PASSWORD" .env
```

### تحقق من الاتصال بقاعدة البيانات:
```bash
php artisan tinker
# ثم في Tinker:
DB::connection()->getPdo();
# إذا نجح الاتصال، سترى معلومات الاتصال
```

---

## 🔒 الأمان | Security

⚠️ **مهم جداً:**
- ✅ تأكد من أن ملف `.env` غير قابل للوصول من المتصفح
- ✅ لا ترفع ملف `.env` على Git
- ✅ احفظ نسخة احتياطية من `.env` في مكان آمن
- ✅ استخدم كلمات مرور قوية

---

## 📝 ملاحظات | Notes

1. **اسم المستخدم:** `sarfesak_sarfesak_almyzan`  
   - إذا كان هناك خطأ في اسم المستخدم، تحقق من لوحة Webuzo

2. **كلمة المرور:** تحتوي على رموز خاصة (`$`)  
   - تأكد من وضعها بين علامات اقتباس إذا لزم الأمر

3. **APP_KEY:** سيتم توليده تلقائياً عند تشغيل `php artisan key:generate`

4. **APP_DEBUG:** مضبوط على `false` للإنتاج (أمان)

---

## 🆘 استكشاف الأخطاء | Troubleshooting

### خطأ: Database Connection Failed
```bash
# تحقق من بيانات الاتصال
php artisan tinker
DB::connection()->getPdo();
```

### خطأ: APP_KEY is not set
```bash
php artisan key:generate
```

### خطأ: 500 Internal Server Error
```bash
# تحقق من السجلات
tail -f storage/logs/laravel.log

# تنظيف الكاش
php artisan config:clear
php artisan cache:clear
```

---

**تم الإعداد بنجاح! 🎉**
