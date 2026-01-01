# نظام الصلاحيات والأدوار - التوثيق الشامل

## 📋 نظرة عامة

تم تنفيذ نظام صلاحيات شامل باستخدام **Spatie Laravel Permission** لإدارة الأدوار والصلاحيات في النظام. النظام يدعم:
- أدوار متعددة (Admin, Doctor, Secretary)
- صلاحيات دقيقة لكل وحدة (View, Create, Update, Delete, Print)
- حماية على مستوى الواجهة (Blade) والخادم (Livewire)
- إدارة ديناميكية للصلاحيات من خلال واجهة المستخدم

---

## 🏗️ البنية المعمارية

### 1. **الحزمة المستخدمة**
- **Spatie Laravel Permission** (`spatie/laravel-permission`)
- قاعدة البيانات: جداول `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`

### 2. **ملف التكوين المركزي**
**الموقع:** `config/permissions.php`

يحتوي على:
- تعريف جميع الوحدات (`modules`)
- تعريف جميع الصلاحيات لكل وحدة (`view`, `create`, `update`, `delete`, `print`)
- تعريف الأدوار الافتراضية (`admin`, `doctor`, `secretary`)
- توزيع الصلاحيات الافتراضية لكل دور

### 3. **Seeder تلقائي**
**الموقع:** `database/seeders/PermissionSeeder.php`

يقوم بـ:
- إنشاء جميع الصلاحيات من ملف التكوين
- إنشاء الأدوار
- ربط الصلاحيات بالأدوار تلقائياً

---

## 👥 الأدوار الحالية

### **Admin (المسؤول)**
- **الصلاحيات:** جميع الصلاحيات (CRUD + Print) لجميع الوحدات
- **الوصول:** كامل التحكم في النظام

### **Doctor (الطبيب)**
- **الصلاحيات:** عرض فقط (View) لجميع الوحدات
- **الوصول:** يمكنه عرض البيانات فقط، لا يمكنه التعديل أو الحذف

### **Secretary (السكرتيرة)**
- **الصلاحيات:** عرض فقط (View) للوحدات التالية:
  - Patients (المرضى)
  - Appointments (المواعيد)
  - Services (الخدمات)
  - Invoices (الفواتير)
  - Assessment (التقييم)
  - Operations (العمليات)
- **الوصول المحدود:** لا يمكنه الوصول إلى:
  - User Management (إدارة المستخدمين)
  - Branch Management (إدارة الفروع)
  - Doctor Management (إدارة الأطباء)

---

## 📊 مصفوفة الصلاحيات الكاملة

| الوحدة (Module) | Admin | Doctor | Secretary |
|-----------------|-------|--------|-----------|
| **User Management** | CRUD | View | ❌ No Access |
| **Branch Management** | CRUD | View | ❌ No Access |
| **Doctor Management** | CRUD | View | ❌ No Access |
| **Patient Management** | CRUD | View | View |
| **Appointment Management** | CRUD | View | View |
| **Service Management** | CRUD | View | View |
| **Invoice Management** | CRUD + Print | View | View + Print |
| **Assessment** | CRUD | View | View |
| **Operations** | CRUD | View | View |

**ملاحظات:**
- **CRUD** = Create, Read, Update, Delete
- **Print** = صلاحية طباعة الفواتير (متاحة فقط في Invoice Management)
- **View** = عرض فقط (Read-only)

---

## 🔐 هيكل الصلاحيات

### تنسيق الصلاحيات
```
{action}.{module}
```

**أمثلة:**
- `view.patients` - عرض المرضى
- `create.appointments` - إنشاء مواعيد
- `update.invoices` - تحديث فواتير
- `delete.services` - حذف خدمات
- `print.invoices` - طباعة فواتير

### الوحدات المتاحة
1. `users` - إدارة المستخدمين
2. `branches` - إدارة الفروع
3. `doctors` - إدارة الأطباء
4. `patients` - إدارة المرضى
5. `appointments` - إدارة المواعيد
6. `services` - إدارة الخدمات
7. `invoices` - إدارة الفواتير
8. `assessment` - التقييم
9. `operations` - العمليات

### الإجراءات المتاحة
- `view` - عرض
- `create` - إنشاء
- `update` - تحديث
- `delete` - حذف
- `print` - طباعة (متاح فقط للفواتير)

---

## 💻 التنفيذ في الكود

### 1. **في Blade Views (الواجهة)**

#### إخفاء/إظهار الأزرار
```blade
{{-- زر "Add" يظهر فقط لمن لديه صلاحية create --}}
@can('create.patients')
<button class="btn-add" wire:click="create">Add Patient</button>
@endcan

{{-- عناصر Dropdown Menu --}}
@can('update.patients')
<li>
    <button wire:click="edit({{ $patient->id }})">Edit</button>
</li>
@endcan

@can('delete.patients')
<li>
    <button wire:click="delete({{ $patient->id }})">Delete</button>
</li>
@endcan
```

#### إخفاء/إظهار الروابط في Sidebar
```blade
@can('view.users')
<li>
    <a href="{{ route('users.index') }}">User Management</a>
</li>
@endcan
```

### 2. **في Livewire Components**

#### حماية الدوال
```php
public function create(): void
{
    abort_unless(auth()->user()->can('create.patients'), 403);
    // باقي الكود...
}

public function edit(int $id): void
{
    abort_unless(auth()->user()->can('update.patients'), 403);
    // باقي الكود...
}

public function save(): void
{
    abort_unless(
        $this->editingId 
            ? auth()->user()->can('update.patients')
            : auth()->user()->can('create.patients'),
        403
    );
    // باقي الكود...
}

public function delete(int $id): void
{
    abort_unless(auth()->user()->can('delete.patients'), 403);
    // باقي الكود...
}
```

### 3. **في Routes (المسارات)**

```php
Route::middleware(['auth', 'permission:view.patients'])->group(function () {
    Route::get('/patients', PatientManager::class)->name('patients.index');
});

Route::middleware(['auth', 'permission:create.patients'])->group(function () {
    Route::post('/patients', [PatientController::class, 'store']);
});
```

---

## 📁 الملفات المتأثرة

### ملفات التكوين
- `config/permissions.php` - التكوين المركزي للصلاحيات
- `composer.json` - إضافة Spatie Permission package

### Migrations
- `database/migrations/xxxx_create_permission_tables.php` - جداول Spatie
- `database/migrations/xxxx_remove_unused_fields_from_doctors_table.php` - تنظيف الحقول غير المستخدمة

### Seeders
- `database/seeders/PermissionSeeder.php` - Seeder تلقائي للصلاحيات
- `database/seeders/AssignAdminRolesToExistingUsers.php` - تعيين الأدوار للمستخدمين الحاليين

### Models
- `app/Models/User.php` - إضافة `HasRoles` trait

### Livewire Components
جميع مكونات Livewire تم تحديثها:

#### إدارة المستخدمين
- `app/Livewire/Admin/UserManager.php`
- `resources/views/livewire/admin/user-manager.blade.php`

#### إدارة الفروع
- `app/Livewire/Admin/BranchManager.php`
- `resources/views/livewire/admin/branch-manager.blade.php`

#### إدارة الأطباء
- `app/Livewire/Admin/DoctorManager.php`
- `resources/views/livewire/admin/doctor-manager.blade.php`

#### إدارة المرضى
- `app/Livewire/PatientManager.php`
- `resources/views/livewire/patient-manager.blade.php`

#### إدارة المواعيد
- `app/Livewire/AppointmentManager.php`
- `resources/views/livewire/appointment-manager.blade.php`

#### إدارة الخدمات
- `app/Livewire/ServiceManager.php`
- `resources/views/livewire/service-manager.blade.php`

#### إدارة الفواتير
- `app/Livewire/InvoiceManager.php`
- `resources/views/livewire/invoice-manager.blade.php`

### Helper Functions
- `app/Helpers/PermissionHelper.php` - دوال مساعدة للتحقق من الصلاحيات

### Layouts
- `resources/views/components/layouts/app.blade.php` - Sidebar مع فحص الصلاحيات

### Authentication
- `app/Livewire/Auth/Login.php` - مسح cache الصلاحيات عند تسجيل الدخول

---

## 🛠️ Helper Functions

### `hasPermission(string $permission): bool`
التحقق من صلاحية واحدة
```php
if (hasPermission('create.patients')) {
    // المستخدم لديه صلاحية إنشاء مرضى
}
```

### `hasAnyPermission(array $permissions): bool`
التحقق من أي صلاحية من قائمة
```php
if (hasAnyPermission(['create.patients', 'update.patients'])) {
    // المستخدم لديه إحدى الصلاحيات
}
```

### `hasAllPermissions(array $permissions): bool`
التحقق من جميع الصلاحيات
```php
if (hasAllPermissions(['view.patients', 'create.patients'])) {
    // المستخدم لديه جميع الصلاحيات
}
```

---

## 🔄 إدارة الصلاحيات

### صفحة إدارة الأدوار
**المسار:** `/admin/roles`
**المكون:** `App\Livewire\Admin\RoleManager`

**الميزات:**
- عرض جميع الأدوار (Admin, Doctor, Secretary)
- عرض مصفوفة الصلاحيات لكل دور
- تعديل الصلاحيات مباشرة من الواجهة
- حفظ التغييرات في قاعدة البيانات

### كيفية إضافة صلاحية جديدة

1. **إضافة في ملف التكوين:**
```php
// config/permissions.php
'modules' => [
    'new_module' => ['view', 'create', 'update', 'delete'],
],
```

2. **تشغيل Seeder:**
```bash
php artisan db:seed --class=PermissionSeeder
```

3. **إضافة في Blade:**
```blade
@can('create.new_module')
<button>Add New</button>
@endcan
```

4. **إضافة في Livewire:**
```php
abort_unless(auth()->user()->can('create.new_module'), 403);
```

---

## 🔍 أمثلة عملية

### مثال 1: صفحة إدارة المرضى

**Blade:**
```blade
{{-- زر Add --}}
@can('create.patients')
<button wire:click="create">Add Patient</button>
@endcan

{{-- Dropdown Menu --}}
<ul>
    <li>
        <button wire:click="viewDetails({{ $patient->id }})">View</button>
    </li>
    @can('create.appointments')
    <li>
        <button wire:click="createVisit({{ $patient->id }})">Visit</button>
    </li>
    @endcan
    @can('update.patients')
    <li>
        <button wire:click="edit({{ $patient->id }})">Edit</button>
    </li>
    @endcan
    @can('delete.patients')
    <li>
        <button wire:click="delete({{ $patient->id }})">Delete</button>
    </li>
    @endcan
</ul>
```

**Livewire:**
```php
public function create(): void
{
    abort_unless(auth()->user()->can('create.patients'), 403);
    $this->resetForm();
    $this->showModal = true;
}

public function save(): void
{
    abort_unless(
        $this->editingId 
            ? auth()->user()->can('update.patients')
            : auth()->user()->can('create.patients'),
        403
    );
    // باقي الكود...
}
```

### مثال 2: صفحة إدارة الفواتير

**Blade:**
```blade
@can('create.invoices')
<button wire:click="create">New Invoice</button>
@endcan

{{-- Dropdown Menu --}}
@can('update.invoices')
<li>
    <button wire:click="edit({{ $invoice->id }})">Edit</button>
</li>
@endcan

@can('print.invoices')
<li>
    <a href="{{ route('invoices.print', $invoice) }}">Print Receipt</a>
</li>
@endcan

@can('delete.invoices')
<li>
    <button wire:click="delete({{ $invoice->id }})">Delete</button>
</li>
@endcan
```

---

## 🚀 التثبيت والإعداد

### 1. تثبيت الحزمة
```bash
composer require spatie/laravel-permission
```

### 2. نشر Migrations
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### 3. تشغيل Seeders
```bash
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=AssignAdminRolesToExistingUsers
```

### 4. مسح Cache
```bash
php artisan optimize:clear
php artisan permission:cache-reset
```

---

## 🔐 الأمان

### حماية على مستويين

1. **حماية الواجهة (UI):**
   - استخدام `@can` في Blade لإخفاء الأزرار
   - منع المستخدم من رؤية الإجراءات غير المسموحة

2. **حماية الخادم (Backend):**
   - استخدام `abort_unless()` في Livewire Components
   - منع المستخدم من تنفيذ الإجراءات حتى لو تجاوز الواجهة

### مسح Cache الصلاحيات
عند تسجيل الدخول، يتم مسح cache الصلاحيات تلقائياً:
```php
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
```

---

## 📝 ملاحظات مهمة

1. **Secretary Role:** لا يمكنه الوصول إلى إدارة المستخدمين والفروع والأطباء
2. **Print Permission:** متاح فقط في Invoice Management
3. **Admin Role:** لديه جميع الصلاحيات تلقائياً
4. **Cache:** يجب مسح cache الصلاحيات بعد أي تغيير
5. **Sidebar:** الروابط تظهر/تختفي تلقائياً حسب الصلاحيات

---

## 🔄 التحديثات المستقبلية

- [ ] إضافة صلاحيات مخصصة لكل مستخدم
- [ ] إضافة صلاحيات على مستوى السجلات (Record-level permissions)
- [ ] إضافة سجل لتغييرات الصلاحيات (Audit Log)
- [ ] إضافة صلاحيات للطباعة في وحدات أخرى

---

## 📚 المراجع

- [Spatie Laravel Permission Documentation](https://spatie.be/docs/laravel-permission)
- [Laravel Authorization Documentation](https://laravel.com/docs/authorization)

---

**آخر تحديث:** 2025-01-XX
**الإصدار:** 1.0.0
