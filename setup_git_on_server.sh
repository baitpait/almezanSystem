#!/bin/bash
# ============================================================
# إعداد Git على السيرفر من الصفر
# Setup Git Repository on Server from Scratch
# ============================================================

cd /home/sarfesak/public_html/almyzan

echo "🚀 بدء إعداد Git Repository من الصفر..."
echo ""

# 1. التحقق من وجود المجلد
if [ ! -d "/home/sarfesak/public_html/almyzan" ]; then
    echo "❌ المجلد غير موجود!"
    exit 1
fi

# 2. نسخ احتياطي لـ .env إذا كان موجوداً
echo "💾 نسخ احتياطي لـ .env..."
if [ -f ".env" ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ تم نسخ .env احتياطياً"
else
    echo "⚠️  ملف .env غير موجود - سيتم إنشاؤه لاحقاً"
fi

# 3. تهيئة Git Repository
echo "📦 تهيئة Git Repository..."
git init
git branch -M main

# 4. إضافة Remote Origin
echo "🔗 إضافة Remote Origin..."
git remote add origin https://github.com/baiitpait/almezanSystem.git

# 5. جلب من GitHub
echo "📥 جلب الملفات من GitHub..."
git fetch origin

# 6. سحب من main branch
echo "📥 سحب الملفات من main branch..."
git pull origin main --allow-unrelated-histories

# 7. إعداد Git Config (إذا لزم الأمر)
echo "⚙️  إعداد Git Config..."
git config user.name "Server Deployment"
git config user.email "server@almyzan.baitpait.space"

# 8. التحقق من الملفات
echo "✅ تم جلب الملفات من GitHub"
echo ""
echo "📋 الملفات الموجودة:"
ls -la | head -20

echo ""
echo "✅ تم إعداد Git Repository بنجاح!"
echo ""
echo "⚠️  الخطوات التالية:"
echo "  1. تحقق من ملف .env - إذا لم يكن موجوداً، استعد من النسخة الاحتياطية"
echo "  2. قم بتشغيل: ./deploy_fresh_from_github.sh"
echo "  3. أو قم بتشغيل: composer install && npm install && npm run build"
