# 🔧 إصلاح مشاكل الصلاحيات على السيرفر
## Fix Permission Issues on Server

---

## المشكلة: EACCES: permission denied

هذه المشكلة تحدث عندما لا يملك المستخدم صلاحيات الكتابة على المجلدات.

---

## الحل السريع:

```bash
cd /home/sarfesak/public_html/almyzan

# إعطاء صلاحيات للمجلدات
chmod -R 755 public/
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# إنشاء مجلد build إذا لم يكن موجوداً
mkdir -p public/build
chmod -R 755 public/build

# إذا كان السيرفر يستخدم www-data
chown -R www-data:www-data public/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/

# أو إذا كان المستخدم الحالي هو sarfesak
chown -R sarfesak:sarfesak public/
chown -R sarfesak:sarfesak storage/
chown -R sarfesak:sarfesak bootstrap/cache/
```

---

## بعد إصلاح الصلاحيات:

```bash
# حاول تجميع الملفات مرة أخرى
npm run build
```

---

## إذا استمرت المشكلة:

```bash
# إنشاء مجلد build يدوياً
mkdir -p public/build
chmod 777 public/build

# ثم حاول مرة أخرى
npm run build

# بعد النجاح، عدّل الصلاحيات للأمان
chmod 755 public/build
```

---

**ملاحظة:** استخدم `chmod 777` فقط مؤقتاً لحل المشكلة، ثم غيّره إلى `755` للأمان.
