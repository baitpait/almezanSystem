#!/bin/bash
# ============================================================
# تحميل الملفات مباشرة من GitHub إلى السيرفر
# Download Files Directly from GitHub to Server
# ============================================================

cd /home/sarfesak/public_html/almyzan

echo "🚀 بدء تحميل الملفات من GitHub..."
echo ""

# 1. التحقق من المجلد الحالي
echo "📍 الخطوة 1: التحقق من المجلد الحالي..."
CURRENT_DIR=$(pwd)
echo "المجلد الحالي: $CURRENT_DIR"

if [ ! -d "$CURRENT_DIR" ]; then
    echo "❌ المجلد غير موجود!"
    exit 1
fi

# 2. نسخ احتياطي لـ .env
echo ""
echo "💾 الخطوة 2: نسخ احتياطي لـ .env..."
if [ -f ".env" ]; then
    BACKUP_NAME=".env.backup.$(date +%Y%m%d_%H%M%S)"
    cp .env "$BACKUP_NAME"
    echo "✅ تم نسخ .env احتياطياً إلى: $BACKUP_NAME"
else
    echo "⚠️  ملف .env غير موجود - سيتم إنشاؤه لاحقاً"
fi

# 3. إنشاء مجلد مؤقت للتحميل
echo ""
echo "📁 الخطوة 3: إنشاء مجلد مؤقت..."
TEMP_DIR="/tmp/almyzan_download_$(date +%s)"
mkdir -p "$TEMP_DIR"
echo "✅ تم إنشاء المجلد المؤقت: $TEMP_DIR"

# 4. تحميل من GitHub باستخدام git clone
echo ""
echo "📥 الخطوة 4: تحميل الملفات من GitHub..."
cd "$TEMP_DIR"

# استخدام git clone
if git clone https://github.com/baiitpait/almezanSystem.git . 2>&1; then
    echo "✅ تم تحميل الملفات من GitHub بنجاح"
else
    echo "❌ فشل تحميل الملفات من GitHub"
    echo "🔄 جرب طريقة بديلة..."
    
    # طريقة بديلة: استخدام wget لتحميل الأرشيف
    echo "📥 تحميل الأرشيف من GitHub..."
    wget -q https://github.com/baiitpait/almezanSystem/archive/refs/heads/main.zip -O main.zip
    
    if [ -f "main.zip" ]; then
        echo "✅ تم تحميل الأرشيف"
        unzip -q main.zip
        mv almezanSystem-main/* .
        mv almezanSystem-main/.* . 2>/dev/null || true
        rm -rf almezanSystem-main main.zip
        echo "✅ تم استخراج الملفات"
    else
        echo "❌ فشل تحميل الأرشيف"
        rm -rf "$TEMP_DIR"
        exit 1
    fi
fi

# 5. نسخ الملفات إلى المجلد النهائي
echo ""
echo "📋 الخطوة 5: نسخ الملفات إلى المجلد النهائي..."
cd "$CURRENT_DIR"

# حذف الملفات القديمة (باستثناء .env و storage)
echo "🗑️  حذف الملفات القديمة..."
find . -maxdepth 1 -type f ! -name '.env' ! -name '.env.*' -delete 2>/dev/null || true
find . -maxdepth 1 -type d ! -name '.' ! -name 'storage' ! -name '.env*' -exec rm -rf {} + 2>/dev/null || true

# نسخ الملفات الجديدة
echo "📋 نسخ الملفات الجديدة..."
cp -r "$TEMP_DIR"/* . 2>/dev/null || true
cp -r "$TEMP_DIR"/.* . 2>/dev/null || true

# حذف .git من المجلد النهائي (إذا لم نريد git)
# rm -rf .git 2>/dev/null || true

# 6. تنظيف المجلد المؤقت
echo ""
echo "🧹 الخطوة 6: تنظيف المجلد المؤقت..."
rm -rf "$TEMP_DIR"
echo "✅ تم تنظيف المجلد المؤقت"

# 7. التحقق من الملفات
echo ""
echo "✅ الخطوة 7: التحقق من الملفات..."
echo "الملفات الموجودة:"
ls -la | head -15

echo ""
echo "✅ تم تحميل جميع الملفات من GitHub بنجاح!"
echo ""
echo "📋 الخطوات التالية:"
echo "  1. تحقق من ملف .env - إذا لم يكن موجوداً، استعد من النسخة الاحتياطية"
echo "  2. قم بتشغيل: composer install --no-dev --optimize-autoloader"
echo "  3. قم بتشغيل: npm install && npm run build"
echo "  4. قم بتشغيل: php artisan config:cache && php artisan route:cache"
