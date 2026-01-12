#!/bin/bash
# ============================================================
# إصلاح مشكلة الكاش - Fix Cache Issue
# ============================================================

cd /home/sarfesak/public_html/almyzan

echo "🔧 إصلاح إعدادات الكاش..."

# 1. التأكد من أن CACHE_DRIVER=file في .env
if grep -q "CACHE_DRIVER=database" .env; then
    echo "⚠️  تغيير CACHE_DRIVER من database إلى file..."
    sed -i 's/CACHE_DRIVER=database/CACHE_DRIVER=file/g' .env
    sed -i 's/CACHE_STORE=database/CACHE_STORE=file/g' .env
fi

# 2. التأكد من أن SESSION_DRIVER=file
if grep -q "SESSION_DRIVER=database" .env; then
    echo "⚠️  تغيير SESSION_DRIVER من database إلى file..."
    sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/g' .env
fi

# 3. حذف config cache يدوياً
echo "🗑️  حذف config cache..."
rm -f bootstrap/cache/config.php

# 4. مسح الكاش باستخدام file driver
echo "🧹 مسح الكاش..."
php artisan cache:clear 2>/dev/null || echo "⚠️  cache:clear فشل (متوقع)"
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. إعادة بناء config cache
echo "🔨 إعادة بناء config cache..."
php artisan config:cache

echo "✅ تم الإصلاح!"
echo ""
echo "الآن يمكنك تشغيل:"
echo "  php artisan permission:cache-reset"
