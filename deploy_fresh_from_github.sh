#!/bin/bash
# ============================================================
# Fresh Deployment from GitHub
# مسح الملفات القديمة وجلب كل شيء من GitHub
# ============================================================

cd /home/sarfesak/public_html/almyzan

echo "🚀 بدء عملية النشر الجديدة من GitHub..."
echo ""

# 1. النسخ الاحتياطي لـ .env (مهم جداً!)
echo "💾 نسخ احتياطي لـ .env..."
if [ -f ".env" ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ تم نسخ .env احتياطياً"
else
    echo "⚠️  ملف .env غير موجود!"
fi

# 2. حذف الملفات القديمة (باستثناء .env و storage)
echo "🗑️  حذف الملفات القديمة..."
# حذف vendor (سيتم إعادة تثبيته)
if [ -d "vendor" ]; then
    rm -rf vendor
    echo "✅ تم حذف vendor/"
fi

# حذف node_modules (سيتم إعادة تثبيته)
if [ -d "node_modules" ]; then
    rm -rf node_modules
    echo "✅ تم حذف node_modules/"
fi

# حذف public/build (سيتم إعادة بنائه)
if [ -d "public/build" ]; then
    rm -rf public/build
    echo "✅ تم حذف public/build/"
fi

# حذف bootstrap/cache (سيتم إعادة بنائه)
if [ -d "bootstrap/cache" ]; then
    rm -rf bootstrap/cache/*
    echo "✅ تم حذف bootstrap/cache/*"
fi

# 3. جلب كل شيء من GitHub
echo "📥 جلب التحديثات من GitHub..."
git fetch origin
git reset --hard origin/main
git clean -fd
echo "✅ تم جلب جميع الملفات من GitHub"

# 4. تثبيت Composer Dependencies
echo "📦 تثبيت Composer Dependencies..."
composer install --no-dev --optimize-autoloader
if [ $? -eq 0 ]; then
    echo "✅ تم تثبيت Composer Dependencies"
else
    echo "❌ فشل تثبيت Composer Dependencies"
    exit 1
fi

# 5. تثبيت NPM Dependencies
echo "📦 تثبيت NPM Dependencies..."
if [ -f "package.json" ]; then
    npm install
    if [ $? -eq 0 ]; then
        echo "✅ تم تثبيت NPM Dependencies"
    else
        echo "❌ فشل تثبيت NPM Dependencies"
        exit 1
    fi
else
    echo "⚠️  package.json غير موجود - تم تخطي npm install"
fi

# 6. التأكد من أن .env موجود
echo "🔧 التحقق من ملف .env..."
if [ ! -f ".env" ]; then
    echo "⚠️  ملف .env غير موجود - استعادة من النسخة الاحتياطية..."
    if [ -f ".env.backup."* ]; then
        cp .env.backup.* .env
        echo "✅ تم استعادة .env من النسخة الاحتياطية"
    else
        echo "❌ لا توجد نسخة احتياطية لـ .env - يجب إنشاء ملف .env يدوياً"
        exit 1
    fi
fi

# 7. التأكد من إعدادات الكاش
echo "🔧 التحقق من إعدادات الكاش..."
if grep -q "CACHE_DRIVER=database" .env; then
    echo "⚠️  تغيير CACHE_DRIVER من database إلى file..."
    sed -i 's/CACHE_DRIVER=database/CACHE_DRIVER=file/g' .env
    sed -i 's/CACHE_STORE=database/CACHE_STORE=file/g' .env
fi

if grep -q "SESSION_DRIVER=database" .env; then
    echo "⚠️  تغيير SESSION_DRIVER من database إلى file..."
    sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/g' .env
fi

# 8. إنشاء bootstrap/cache إذا لم يكن موجوداً
mkdir -p bootstrap/cache
chmod -R 775 bootstrap/cache

# 9. تنظيف الكاش
echo "🧹 تنظيف الكاش..."
php artisan config:clear 2>/dev/null || echo "⚠️  config:clear تم تخطيه"
php artisan route:clear 2>/dev/null || echo "⚠️  route:clear تم تخطيه"
php artisan view:clear 2>/dev/null || echo "⚠️  view:clear تم تخطيه"
php artisan cache:clear 2>/dev/null || echo "⚠️  cache:clear تم تخطيه"
php artisan permission:cache-reset 2>/dev/null || echo "⚠️  permission:cache-reset تم تخطيه"

# 10. إعادة بناء Config Cache
echo "🔨 إعادة بناء Config Cache..."
php artisan config:cache
if [ $? -eq 0 ]; then
    echo "✅ تم إعادة بناء Config Cache"
else
    echo "❌ فشل إعادة بناء Config Cache"
    exit 1
fi

# 11. إعادة بناء Route Cache
echo "🔨 إعادة بناء Route Cache..."
php artisan route:cache
if [ $? -eq 0 ]; then
    echo "✅ تم إعادة بناء Route Cache"
else
    echo "❌ فشل إعادة بناء Route Cache"
    exit 1
fi

# 12. بناء Assets (npm run build)
echo "📦 بناء Assets (npm run build)..."
if [ -f "package.json" ]; then
    npm run build
    if [ $? -eq 0 ]; then
        echo "✅ تم بناء Assets بنجاح"
    else
        echo "❌ فشل بناء Assets"
        exit 1
    fi
else
    echo "⚠️  package.json غير موجود - تم تخطي npm build"
fi

# 13. تحسين الأداء
echo "⚡ تحسين الأداء..."
php artisan optimize
if [ $? -eq 0 ]; then
    echo "✅ تم تحسين الأداء"
else
    echo "⚠️  تحسين الأداء تم تخطيه"
fi

# 14. إعادة تعيين كاش الصلاحيات
echo "🔐 إعادة تعيين كاش الصلاحيات..."
php artisan permission:cache-reset 2>/dev/null || echo "⚠️  permission:cache-reset تم تخطيه"

# 15. إصلاح الصلاحيات للملفات
echo "🔧 إصلاح صلاحيات الملفات..."
chmod -R 755 storage bootstrap/cache
chown -R sarfesak:sarfesak storage bootstrap/cache
chmod -R 755 public
chown -R sarfesak:sarfesak public

echo ""
echo "✅ تم الانتهاء من عملية النشر الجديدة!"
echo ""
echo "📋 ملخص ما تم:"
echo "  ✓ نسخ احتياطي لـ .env"
echo "  ✓ حذف الملفات القديمة (vendor, node_modules, build, cache)"
echo "  ✓ جلب جميع الملفات من GitHub"
echo "  ✓ تثبيت Composer Dependencies"
echo "  ✓ تثبيت NPM Dependencies"
echo "  ✓ تنظيف الكاش"
echo "  ✓ إعادة بناء Config Cache"
echo "  ✓ إعادة بناء Route Cache"
echo "  ✓ بناء Assets (npm build)"
echo "  ✓ تحسين الأداء"
echo "  ✓ إصلاح الصلاحيات"
echo ""
echo "🌐 النظام جاهز للاستخدام!"
echo ""
echo "⚠️  ملاحظات مهمة:"
echo "  - إذا كان لديك بيانات موجودة في operation_notes، قم بتشغيل:"
echo "    mysql -u [username] -p [database_name] < database/fix_operation_notes_table.sql"
echo "  - تحقق من ملف .env وتأكد من صحة الإعدادات"
echo "  - إذا كان هناك مشاكل، استعد .env من النسخة الاحتياطية"
