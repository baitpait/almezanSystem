# إصلاح مشكلة تسجيل الدخول
# Fix Login Issue

---

## 🔍 المشكلة

بعد استيراد قاعدة البيانات، تسجيل الدخول لا يعمل بسبب hash كلمات المرور غير الصحيح.

---

## ✅ الحل

### الطريقة 1: استخدام SQL Script (سريع)

```bash
# على السيرفر
cd /home/sarfesak/public_html/almyzan
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < fix_passwords.sql
```

### الطريقة 2: استخدام Tinker (يدوي)

```bash
# على السيرفر
cd /home/sarfesak/public_html/almyzan
php artisan tinker
```

ثم في Tinker:

```php
// تحديث كلمة مرور Admin
$admin = App\Models\User::where('email', 'admin@gmail.com')->first();
$admin->password = Hash::make('100200300');
$admin->save();

// تحديث كلمة مرور Dr. Alaa
$alaa = App\Models\User::where('email', 'alaa@almyzan.ps')->first();
$alaa->password = Hash::make('password123');
$alaa->save();

// تحديث كلمة مرور Dr. Tariq
$tariq = App\Models\User::where('email', 'tariq@almyzan.ps')->first();
$tariq->password = Hash::make('password123');
$tariq->save();

exit
```

---

## 🔐 معلومات تسجيل الدخول بعد الإصلاح

- **Admin**: `admin@gmail.com` / `100200300`
- **Dr. Alaa**: `alaa@almyzan.ps` / `password123`
- **Dr. Tariq**: `tariq@almyzan.ps` / `password123`

---

## ⚠️ ملاحظات

1. **مسح الكاش بعد التحديث:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

2. **التحقق من المستخدمين:**
```sql
SELECT id, name, email, role, is_active FROM users;
```

3. **إذا استمرت المشكلة:**
   - تأكد من أن `is_active = 1` للمستخدمين
   - تأكد من أن المستخدمين لديهم أدوار (roles) في جدول `model_has_roles`
   - تحقق من logs: `tail -f storage/logs/laravel.log`

---

## 📝 الملفات

- `fix_passwords.sql` - SQL script لتحديث كلمات المرور
- `FULL_DATABASE_README.md` - تم تحديثه أيضاً

---

**تم التجهيز بواسطة:** النظام الآلي  
**التاريخ:** 2026-01-11
