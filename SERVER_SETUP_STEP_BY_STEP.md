# 📋 خطوات إعداد Git على السيرفر - خطوة بخطوة

## الخطوة 1: التحقق من المجلد الحالي
```bash
pwd
# يجب أن يظهر: /home/sarfesak/public_html/almyzan
```

## الخطوة 2: نسخ احتياطي لـ .env (مهم جداً!)
```bash
# تحقق من وجود .env
ls -la .env

# إذا كان موجوداً، انسخه احتياطياً
if [ -f ".env" ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ تم نسخ .env احتياطياً"
else
    echo "⚠️  ملف .env غير موجود"
fi
```

## الخطوة 3: تهيئة Git Repository
```bash
git init
```

**النتيجة المتوقعة:** `Initialized empty Git repository in /home/sarfesak/public_html/almyzan/.git/`

## الخطوة 4: تعيين Branch الرئيسي
```bash
git branch -M main
```

## الخطوة 5: إضافة Remote Origin
```bash
git remote add origin https://github.com/baiitpait/almezanSystem.git
```

**التحقق:**
```bash
git remote -v
# يجب أن يظهر:
# origin  https://github.com/baiitpait/almezanSystem.git (fetch)
# origin  https://github.com/baiitpait/almezanSystem.git (push)
```

## الخطوة 6: جلب من GitHub
```bash
git fetch origin
```

**النتيجة المتوقعة:** `remote: Enumerating objects: ...`

## الخطوة 7: سحب الملفات من main
```bash
git pull origin main --allow-unrelated-histories
```

**النتيجة المتوقعة:** `Merge made by the 'recursive' strategy.`

## الخطوة 8: التحقق من الملفات
```bash
ls -la
# يجب أن ترى جميع الملفات من GitHub
```

## الخطوة 9: استعادة .env (إذا لزم الأمر)
```bash
# إذا كان .env غير موجود بعد git pull
if [ ! -f ".env" ]; then
    # استعد من النسخة الاحتياطية
    ls -la .env.backup.*
    # ثم انسخ النسخة الاحتياطية
    cp .env.backup.[timestamp] .env
fi
```

## الخطوة 10: تثبيت Composer Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

**انتظر حتى يكتمل** - قد يستغرق بضع دقائق

## الخطوة 11: تثبيت NPM Dependencies
```bash
npm install
```

**انتظر حتى يكتمل**

## الخطوة 12: بناء Assets
```bash
npm run build
```

**انتظر حتى يكتمل**

## الخطوة 13: تنظيف الكاش
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## الخطوة 14: إعادة بناء الكاش
```bash
php artisan config:cache
php artisan route:cache
```

## الخطوة 15: تحسين الأداء
```bash
php artisan optimize
```

## الخطوة 16: إصلاح الصلاحيات
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 755 public
```

---

## ✅ التحقق النهائي

```bash
# تحقق من أن Git يعمل
git status

# تحقق من الملفات
ls -la | head -20

# تحقق من .env
ls -la .env
```

---

## ⚠️ إذا واجهت مشاكل

### مشكلة: خطأ في المصادقة
```bash
# استخدم Personal Access Token
git remote set-url origin https://[YOUR_TOKEN]@github.com/baiitpait/almezanSystem.git
```

### مشكلة: تعارض في الملفات
```bash
# احفظ ملفاتك أولاً
git stash
git pull origin main
git stash pop
```
