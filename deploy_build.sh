#!/bin/bash
# ============================================================
# تشغيل السيرفر وتنظيف الكاش وعمل Build
# Server Start, Cache Clear, and Build
# ============================================================

cd /home/sarfesak/public_html/almyzan

echo "🚀 بدء عملية التحديث..."
echo ""

# 1. التأكد من أن CACHE_DRIVER=file
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

# 2. حذف config cache يدوياً
echo "🗑️  حذف config cache..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes-v7.php
rm -f bootstrap/cache/services.php

# 3. تنظيف الكاش
echo "🧹 تنظيف الكاش..."
php artisan config:clear 2>/dev/null || echo "⚠️  config:clear تم تخطيه"
php artisan route:clear 2>/dev/null || echo "⚠️  route:clear تم تخطيه"
php artisan view:clear 2>/dev/null || echo "⚠️  view:clear تم تخطيه"
php artisan cache:clear 2>/dev/null || echo "⚠️  cache:clear تم تخطيه"
php artisan permission:cache-reset 2>/dev/null || echo "⚠️  permission:cache-reset تم تخطيه"

# 4. إعادة بناء config cache
echo "🔨 إعادة بناء config cache..."
php artisan config:cache

# 5. إعادة بناء route cache
echo "🔨 إعادة بناء route cache..."
php artisan route:cache

# 6. عمل Build للملفات (npm)
echo "📦 تجميع الملفات (npm run build)..."
if [ -f "package.json" ]; then
    npm run build
    echo "✅ تم تجميع الملفات بنجاح"
else
    echo "⚠️  package.json غير موجود - تم تخطي npm build"
fi

# 7. تحسين الأداء
echo "⚡ تحسين الأداء..."
php artisan optimize

# 8. إعادة تعيين الصلاحيات (اختياري)
echo "🔐 إعادة تعيين كاش الصلاحيات..."
php artisan permission:cache-reset 2>/dev/null || echo "⚠️  permission:cache-reset تم تخطيه"

echo ""
echo "✅ تم الانتهاء!"
echo ""
echo "📋 ملخص ما تم:"
echo "  ✓ تنظيف الكاش"
echo "  ✓ إعادة بناء Config Cache"
echo "  ✓ إعادة بناء Route Cache"
echo "  ✓ تجميع الملفات (npm build)"
echo "  ✓ تحسين الأداء"
echo ""
echo "🌐 النظام جاهز للاستخدام!"
