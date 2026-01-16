# 📥 دليل تحميل الملفات من GitHub إلى السيرفر

## الطريقة 1: استخدام السكريبت (موصى به)

### الخطوة 1: تحميل السكريبت
```bash
cd /home/sarfesak/public_html/almyzan

# تحميل السكريبت مباشرة من GitHub
wget https://raw.githubusercontent.com/baiitpait/almezanSystem/main/download_from_github.sh

# أو نسخه يدوياً إذا كان موجوداً محلياً
```

### الخطوة 2: جعل السكريبت قابل للتنفيذ
```bash
chmod +x download_from_github.sh
```

### الخطوة 3: تشغيل السكريبت
```bash
./download_from_github.sh
```

## الطريقة 2: يدوياً (خطوة بخطوة)

### الخطوة 1: نسخ احتياطي لـ .env
```bash
cd /home/sarfesak/public_html/almyzan
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
```

### الخطوة 2: إنشاء مجلد مؤقت
```bash
TEMP_DIR="/tmp/almyzan_download_$(date +%s)"
mkdir -p "$TEMP_DIR"
cd "$TEMP_DIR"
```

### الخطوة 3: تحميل من GitHub
```bash
# الطريقة الأولى: git clone
git clone https://github.com/baiitpait/almezanSystem.git .

# أو الطريقة الثانية: تحميل الأرشيف
wget https://github.com/baiitpait/almezanSystem/archive/refs/heads/main.zip
unzip main.zip
mv almezanSystem-main/* .
```

### الخطوة 4: نسخ الملفات إلى المجلد النهائي
```bash
cd /home/sarfesak/public_html/almyzan

# حذف الملفات القديمة (باستثناء .env و storage)
find . -maxdepth 1 -type f ! -name '.env' ! -name '.env.*' -delete
find . -maxdepth 1 -type d ! -name '.' ! -name 'storage' ! -name '.env*' -exec rm -rf {} +

# نسخ الملفات الجديدة
cp -r "$TEMP_DIR"/* .
cp -r "$TEMP_DIR"/.* . 2>/dev/null || true

# تنظيف
rm -rf "$TEMP_DIR"
```

## الطريقة 3: استخدام git clone مباشرة

```bash
cd /home/sarfesak/public_html

# حذف المجلد القديم (إذا لزم الأمر)
rm -rf almyzan_old

# نسخ المجلد الحالي احتياطياً
cp -r almyzan almyzan_old

# حذف محتويات المجلد (باستثناء .env و storage)
cd almyzan
find . -maxdepth 1 ! -name '.' ! -name '.env' ! -name '.env.*' ! -name 'storage' -exec rm -rf {} +

# تحميل من GitHub
git clone https://github.com/baiitpait/almezanSystem.git temp_download
cp -r temp_download/* .
cp -r temp_download/.* . 2>/dev/null || true
rm -rf temp_download
```

## بعد التحميل

### 1. استعادة .env
```bash
# إذا كان .env غير موجود
cp .env.backup.[timestamp] .env
```

### 2. تثبيت Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 3. إعداد Laravel
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan optimize
```

## ملاحظات مهمة

- السكريبت يحفظ `.env` تلقائياً
- السكريبت لا يحذف `storage/`
- السكريبت يحذف الملفات القديمة قبل نسخ الجديدة
- إذا فشل git clone، السكريبت يحاول تحميل الأرشيف كبديل
