# دليل النشر السريع | Quick Deployment Guide

## 📋 قائمة التحقق قبل النشر | Pre-Deployment Checklist

- [ ] PHP 8.2+ مثبت على السيرفر
- [ ] Composer مثبت
- [ ] Node.js و npm مثبتان
- [ ] قاعدة بيانات MySQL جاهزة
- [ ] معلومات الاتصال بقاعدة البيانات جاهزة
- [ ] Domain Name مربوط بالسيرفر
- [ ] SSL Certificate جاهز (موصى به)

---

## 🚀 خطوات النشر السريعة | Quick Deployment Steps

### 1️⃣ رفع الملفات
```bash
# عبر FTP/SFTP: ارفع جميع الملفات إلى public_html
# أو عبر Git:
git clone your-repo-url /home/username/public_html
```

### 2️⃣ إعداد .env
```bash
cd /home/username/public_html
cp .env.example .env
# عدّل .env واملأ بيانات قاعدة البيانات
nano .env
```

### 3️⃣ تشغيل Script النشر
```bash
chmod +x deploy.sh
./deploy.sh
```

### 4️⃣ إعداد Document Root في Webuzo
- Apache Settings → Document Root → `/home/username/public_html/public`

### 5️⃣ اختبار الموقع
- افتح: `https://your-domain.com`
- تأكد من أن كل شيء يعمل

---

## ⚡ أوامر سريعة | Quick Commands

```bash
# تثبيت Dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# إعداد Laravel
php artisan key:generate
php artisan storage:link
php artisan migrate --force

# تنظيف الكاش
php artisan optimize

# إصلاح الأذونات
chmod -R 775 storage bootstrap/cache
```

---

## 🔧 إعدادات Webuzo المهمة | Important Webuzo Settings

1. **PHP Version**: 8.2 أو أعلى
2. **Document Root**: `/home/username/public_html/public`
3. **mod_rewrite**: مفعّل
4. **PHP Extensions**: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, fileinfo

---

## 📞 الدعم | Support

- راجع `DEPLOYMENT_GUIDE.md` للتفاصيل الكاملة
- راجع `WEBUZO_SETUP.md` لإعدادات Webuzo المحددة
- تحقق من `storage/logs/laravel.log` للأخطاء

---

**نشر سعيد! 🎉**
