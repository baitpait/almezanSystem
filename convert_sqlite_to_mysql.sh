#!/bin/bash
# ============================================================
# تحويل SQLite Export إلى MySQL Format
# Convert SQLite Export to MySQL Format
# ============================================================

cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"

INPUT_FILE="database/local_database_export_20260116_142857.sql"
OUTPUT_FILE="database/mysql_database_export_$(date +%Y%m%d_%H%M%S).sql"

echo "🔄 تحويل SQLite إلى MySQL..."
echo ""

# إنشاء ملف جديد مع تحويلات MySQL
cat "$INPUT_FILE" | \
    sed 's/PRAGMA foreign_keys=OFF;//g' | \
    sed 's/BEGIN TRANSACTION;//g' | \
    sed 's/COMMIT;//g' | \
    sed 's/CREATE TABLE IF NOT EXISTS/CREATE TABLE IF NOT EXISTS/g' | \
    sed 's/integer primary key autoincrement/bigint unsigned NOT NULL AUTO_INCREMENT/g' | \
    sed 's/integer NOT NULL/bigint NOT NULL/g' | \
    sed 's/integer DEFAULT NULL/bigint DEFAULT NULL/g' | \
    sed 's/varchar NOT NULL/varchar(255) NOT NULL/g' | \
    sed 's/varchar DEFAULT NULL/varchar(255) DEFAULT NULL/g' | \
    sed 's/text DEFAULT NULL/text DEFAULT NULL/g' | \
    sed 's/"//g' | \
    sed 's/INSERT INTO/INSERT INTO/g' | \
    sed 's/VALUES(/VALUES(/g' > "$OUTPUT_FILE"

# إضافة MySQL header
cat > "$OUTPUT_FILE" << 'EOF'
-- MySQL Database Export
-- Converted from SQLite
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
START TRANSACTION;
SET NAMES utf8mb4;

EOF

# إضافة محتوى محول
cat "$INPUT_FILE" | \
    sed 's/PRAGMA foreign_keys=OFF;//g' | \
    sed 's/BEGIN TRANSACTION;//g' | \
    sed 's/COMMIT;//g' | \
    sed 's/CREATE TABLE IF NOT EXISTS/CREATE TABLE IF NOT EXISTS/g' | \
    sed 's/integer primary key autoincrement/bigint unsigned NOT NULL AUTO_INCREMENT/g' | \
    sed 's/integer NOT NULL/bigint NOT NULL/g' | \
    sed 's/integer DEFAULT NULL/bigint DEFAULT NULL/g' | \
    sed 's/varchar NOT NULL/varchar(255) NOT NULL/g' | \
    sed 's/varchar DEFAULT NULL/varchar(255) DEFAULT NULL/g' | \
    sed 's/"//g' >> "$OUTPUT_FILE"

# إضافة footer
cat >> "$OUTPUT_FILE" << 'EOF'
COMMIT;
EOF

echo "✅ تم التحويل بنجاح!"
echo "📁 الملف المحول: $OUTPUT_FILE"
echo ""
echo "⚠️  ملاحظة: قد تحتاج إلى مراجعة الملف يدوياً قبل الاستيراد"
