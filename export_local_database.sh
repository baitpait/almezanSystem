#!/bin/bash
# ============================================================
# تصدير قاعدة البيانات المحلية (SQLite) إلى SQL
# Export Local Database (SQLite) to SQL
# ============================================================

cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"

echo "📥 تصدير قاعدة البيانات المحلية..."
echo ""

# 1. التحقق من وجود قاعدة البيانات
DB_FILE="database/database.sqlite"
if [ ! -f "$DB_FILE" ]; then
    echo "❌ ملف قاعدة البيانات غير موجود: $DB_FILE"
    exit 1
fi

echo "✅ تم العثور على قاعدة البيانات: $DB_FILE"
echo ""

# 2. تصدير قاعدة البيانات
OUTPUT_FILE="database/local_database_export_$(date +%Y%m%d_%H%M%S).sql"
echo "📤 تصدير قاعدة البيانات إلى: $OUTPUT_FILE"
echo ""

# استخدام sqlite3 لتصدير البيانات
sqlite3 "$DB_FILE" <<EOF > "$OUTPUT_FILE"
.mode insert
.output $OUTPUT_FILE
.dump
EOF

if [ $? -eq 0 ]; then
    echo "✅ تم تصدير قاعدة البيانات بنجاح!"
    echo "📁 الملف: $OUTPUT_FILE"
    echo ""
    echo "📋 الخطوات التالية:"
    echo "  1. راجع الملف: $OUTPUT_FILE"
    echo "  2. انسخ الملف إلى السيرفر"
    echo "  3. استورد الملف على السيرفر:"
    echo "     mysql -u [username] -p [database_name] < $OUTPUT_FILE"
else
    echo "❌ فشل تصدير قاعدة البيانات"
    exit 1
fi
