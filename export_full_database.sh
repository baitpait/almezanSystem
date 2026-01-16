#!/bin/bash
# ============================================================
# تصدير قاعدة البيانات الكاملة (Schema + Data + Users + Permissions)
# Export Full Database (Schema + Data + Users + Permissions)
# ============================================================

cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"

# إعدادات قاعدة البيانات من .env
DB_HOST="127.0.0.1"
DB_PORT="3306"
DB_DATABASE="dralmyzin"
DB_USERNAME="root"
DB_PASSWORD=""

OUTPUT_FILE="database/full_database_complete_$(date +%Y%m%d_%H%M%S).sql"

echo "📥 تصدير قاعدة البيانات الكاملة..."
echo "Database: $DB_DATABASE"
echo "Host: $DB_HOST"
echo ""

# 1. تصدير Schema + Data
echo "📤 تصدير Schema والبيانات..."
if [ -z "$DB_PASSWORD" ]; then
    mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" \
        --single-transaction \
        --routines \
        --triggers \
        --events \
        --add-drop-table \
        --complete-insert \
        --extended-insert \
        "$DB_DATABASE" > "$OUTPUT_FILE"
else
    mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" \
        --single-transaction \
        --routines \
        --triggers \
        --events \
        --add-drop-table \
        --complete-insert \
        --extended-insert \
        "$DB_DATABASE" > "$OUTPUT_FILE"
fi

if [ $? -ne 0 ]; then
    echo "❌ فشل تصدير قاعدة البيانات"
    exit 1
fi

# 2. إضافة معلومات MySQL في بداية الملف
sed -i '' "1i\\
-- MySQL Database Export\\
-- Database: $DB_DATABASE\\
-- Host: $DB_HOST\\
-- Port: $DB_PORT\\
-- Export Date: $(date)\\
--\\
SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\\
SET time_zone = \"+00:00\";\\
SET NAMES utf8mb4;\\
SET FOREIGN_KEY_CHECKS = 0;\\
\\
" "$OUTPUT_FILE"

# 3. إضافة إعادة تفعيل Foreign Keys في نهاية الملف
echo "" >> "$OUTPUT_FILE"
echo "SET FOREIGN_KEY_CHECKS = 1;" >> "$OUTPUT_FILE"

# 4. التحقق من الملف
FILE_SIZE=$(ls -lh "$OUTPUT_FILE" | awk '{print $5}')
LINE_COUNT=$(wc -l < "$OUTPUT_FILE")

echo ""
echo "✅ تم تصدير قاعدة البيانات بنجاح!"
echo "📁 الملف: $OUTPUT_FILE"
echo "📊 الحجم: $FILE_SIZE"
echo "📝 عدد الأسطر: $LINE_COUNT"
echo ""
echo "📋 المحتوى:"
echo "  ✓ Schema (هيكل جميع الجداول)"
echo "  ✓ Data (جميع البيانات)"
echo "  ✓ Users (جميع المستخدمين)"
echo "  ✓ Roles & Permissions (الصلاحيات)"
echo "  ✓ جميع الجداول والعلاقات"
echo ""
echo "📤 الخطوة التالية:"
echo "  scp \"$OUTPUT_FILE\" root@159.198.75.10:/home/sarfesak/public_html/almyzan/database/"
