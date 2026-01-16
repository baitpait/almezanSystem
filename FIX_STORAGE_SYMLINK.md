# إصلاح مشكلة Storage Symlink
## تاريخ: 2026-01-16

### المشكلة
الملفات تُحفظ في قاعدة البيانات بشكل صحيح، لكن عند العرض يظهر خطأ 404.

**السبب:** symlink من `public/storage` إلى `storage/app/public` غير موجود على السيرفر.

---

## الحل

### 1. على السيرفر، نفّذ:

```bash
cd /home/sarfesak/public_html/almyzan

# إنشاء symlink
php artisan storage:link

# التحقق من وجود symlink
ls -la public/storage

# إذا لم يعمل، أنشئ symlink يدوياً:
ln -s ../storage/app/public public/storage

# إصلاح الصلاحيات
chmod -R 755 storage
chown -R sarfesak:sarfesak storage
```

### 2. التغييرات في الكود

تم تغيير `Storage::url()` إلى `asset('storage/' . $file->file_path)` في:
- `resources/views/livewire/operation-manager/tabs/files.blade.php`

**السبب:** `asset()` يعطي URL مطلق يعمل حتى لو لم يكن symlink موجوداً (لكن symlink ضروري لعرض الملفات).

---

## التحقق من الحل

بعد إنشاء symlink، يجب أن يعمل:
- `https://almyzan.baitpait.space/storage/operation_files/1768592166_Snip20260108_1.png`

---

## ملاحظات

- إذا كان symlink موجوداً بالفعل، قد تحتاج إلى حذفه وإنشاؤه مرة أخرى:
  ```bash
  rm public/storage
  php artisan storage:link
  ```

- تأكد من أن مجلد `storage/app/public` موجود:
  ```bash
  ls -la storage/app/public
  ```
