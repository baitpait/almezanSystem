# إصلاح مشكلة الصلاحيات
# Fix Permissions Issue

---

## 🔍 المشكلة

بعد استيراد قاعدة البيانات، المستخدم Admin لا يملك صلاحيات - تظهر فقط Dashboard في القائمة.

**السبب:** أسماء الصلاحيات في SQL script تستخدم شرطة (`view-patients`) بينما القائمة تستخدم نقطة (`view.patients`).

---

## ✅ الحل

### الطريقة 1: استخدام SQL Script (سريع)

```bash
# على السيرفر
cd /home/sarfesak/public_html/almyzan
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < fix_permissions.sql
```

### الطريقة 2: استخدام Tinker (يدوي)

```bash
# على السيرفر
cd /home/sarfesak/public_html/almyzan
php artisan tinker
```

ثم في Tinker:

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// حذف الصلاحيات القديمة
Permission::where('name', 'like', 'view-%')->orWhere('name', 'like', 'create-%')->delete();

// إنشاء الصلاحيات الصحيحة
$permissions = [
    'view.patients', 'create.patients', 'edit.patients', 'delete.patients',
    'view.appointments', 'create.appointments', 'edit.appointments', 'delete.appointments',
    'view.operations', 'create.operations', 'edit.operations', 'delete.operations',
    'view.assessment', 'create.assessment', 'edit.assessment', 'delete.assessment',
    'view.invoices', 'create.invoices', 'edit.invoices', 'delete.invoices',
    'view.services', 'create.services', 'edit.services', 'delete.services',
    'view.users', 'create.users', 'edit.users', 'delete.users',
    'view.doctors', 'create.doctors', 'edit.doctors', 'delete.doctors',
    'view.branches', 'create.branches', 'edit.branches', 'delete.branches',
    'view.operation-notes', 'create.operation-notes', 'edit.operation-notes', 'delete.operation-notes',
    'manage.users', 'manage.branches', 'manage.settings', 'view.reports',
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission]);
}

// ربط جميع الصلاحيات بدور Admin
$admin = Role::findByName('admin');
$admin->syncPermissions(Permission::all());

// التأكد من أن Admin لديه دور admin
$adminUser = App\Models\User::where('email', 'admin@gmail.com')->first();
$adminUser->assignRole('admin');

exit
```

---

## 🔄 خطوات إضافية (مهم!)

بعد تحديث الصلاحيات، **يجب** مسح الكاش:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan permission:cache-reset
```

---

## ✅ التحقق من الصلاحيات

### في Tinker:

```php
$admin = App\Models\User::where('email', 'admin@gmail.com')->first();
echo "Roles: " . $admin->roles->pluck('name')->implode(', ') . "\n";
echo "Permissions: " . $admin->getAllPermissions()->pluck('name')->count() . "\n";
echo "Can view patients: " . ($admin->can('view.patients') ? 'Yes' : 'No') . "\n";
```

### في SQL:

```sql
-- التحقق من دور Admin
SELECT r.name, COUNT(rhp.permission_id) AS permissions_count
FROM roles r
JOIN model_has_roles mhr ON r.id = mhr.role_id
LEFT JOIN role_has_permissions rhp ON r.id = rhp.role_id
WHERE mhr.model_id = 1 AND mhr.model_type = 'App\\Models\\User'
GROUP BY r.id, r.name;

-- التحقق من الصلاحيات
SELECT name FROM permissions WHERE name LIKE 'view.%' ORDER BY name;
```

---

## 🔐 معلومات تسجيل الدخول

- **Admin**: `admin@gmail.com` / `100200300`
- **Dr. Alaa**: `alaa@almyzan.ps` / `password123`
- **Dr. Tariq**: `tariq@almyzan.ps` / `password123`

---

## ⚠️ ملاحظات

1. **مسح الكاش ضروري:** بعد تحديث الصلاحيات، يجب مسح الكاش أو لن تظهر الصلاحيات الجديدة.

2. **إعادة تسجيل الدخول:** قد تحتاج إلى إعادة تسجيل الدخول بعد تحديث الصلاحيات.

3. **إذا استمرت المشكلة:**
   - تأكد من أن `is_active = 1` للمستخدم
   - تأكد من أن المستخدم لديه دور في `model_has_roles`
   - تحقق من logs: `tail -f storage/logs/laravel.log`

---

## 📝 الملفات

- `fix_permissions.sql` - SQL script لإصلاح الصلاحيات
- `FIX_PERMISSIONS_README.md` - هذا الملف

---

**تم التجهيز بواسطة:** النظام الآلي  
**التاريخ:** 2026-01-11
