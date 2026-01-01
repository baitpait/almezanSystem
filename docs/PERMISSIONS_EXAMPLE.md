# مثال عملي: نظام الصلاحيات مع Spatie Permission

## 📋 السيناريو: نظام طبي مع 4 أدوار

### الأدوار والصلاحيات:

```
1. Admin (مدير النظام)
   ✅ جميع الصلاحيات

2. Doctor (طبيب)
   ✅ view-patients, create-patients, edit-patients
   ✅ view-appointments, create-appointments, edit-appointments
   ✅ view-operations, create-operations, edit-operations
   ✅ view-invoices
   ❌ delete-operations (لا يمكن حذف العمليات)
   ❌ manage-users (لا يمكن إدارة المستخدمين)

3. Optometrist (أخصائي بصريات)
   ✅ view-patients
   ✅ view-appointments, create-appointments
   ✅ view-operations, edit-operations (إدخال بيانات الفحص فقط)
   ❌ create-operations (لا يمكن إنشاء عمليات جديدة)
   ❌ delete-operations

4. Secretary (سكرتير)
   ✅ view-patients, create-patients, edit-patients
   ✅ view-appointments, create-appointments, edit-appointments, delete-appointments
   ✅ view-invoices, create-invoices, edit-invoices
   ❌ view-operations (لا يمكن رؤية العمليات)
   ❌ create-operations
```

---

## 💻 أمثلة الكود

### 1. في Routes (web.php)

```php
use Illuminate\Support\Facades\Route;

// Routes متاحة لجميع المستخدمين المسجلين
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

// Routes للطبيب فقط
Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::prefix('operations')->name('operations.')->group(function () {
        Route::get('/', OperationManager::class)->name('index');
        Route::get('/create', OperationManager::class)->name('create');
        Route::get('/{id}/edit', OperationManager::class)->name('edit');
    });
});

// Routes للطبيب أو الأخصائي
Route::middleware(['auth', 'role:doctor|optometrist'])->group(function () {
    Route::prefix('operations')->name('operations.')->group(function () {
        Route::get('/{id}/edit', OperationManager::class)->name('edit');
    });
});

// Routes بصلاحية محددة
Route::middleware(['auth', 'permission:create-operations'])->group(function () {
    Route::get('/operations/create', OperationManager::class)->name('operations.create');
});

// Routes للسكرتير فقط
Route::middleware(['auth', 'role:secretary'])->group(function () {
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', AppointmentManager::class)->name('index');
        Route::post('/', [AppointmentManager::class, 'store'])->name('store');
        Route::delete('/{id}', [AppointmentManager::class, 'destroy'])->name('destroy');
    });
});

// Routes للمدير فقط
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', UserManager::class)->name('users.index');
    Route::get('/branches', BranchManager::class)->name('branches.index');
});
```

---

### 2. في Livewire Components

#### مثال 1: OperationManager.php

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class OperationManager extends Component
{
    public function mount($id = null): void
    {
        // التحقق من الصلاحية قبل الدخول
        if ($id) {
            $this->authorize('edit-operations');
        } else {
            $this->authorize('create-operations');
        }
        
        // باقي الكود...
    }

    public function save(): void
    {
        // التحقق من الصلاحية قبل الحفظ
        $this->authorize('create-operations');
        
        // كود الحفظ...
    }

    public function delete($id): void
    {
        // التحقق من الصلاحية قبل الحذف
        $this->authorize('delete-operations');
        
        // كود الحذف...
    }

    public function render()
    {
        return view('livewire.operation-manager');
    }
}
```

#### مثال 2: AppointmentManager.php

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class AppointmentManager extends Component
{
    public function save(): void
    {
        // التحقق من الصلاحية
        $this->authorize('create-appointments');
        
        // كود الحفظ...
    }

    public function delete($id): void
    {
        // التحقق من الصلاحية
        $this->authorize('delete-appointments');
        
        // كود الحذف...
    }
}
```

---

### 3. في Blade Views

#### مثال 1: operation-manager.blade.php

```blade
<div>
    {{-- زر "Create" يظهر فقط لمن لديه صلاحية create-operations --}}
    @can('create-operations')
        <a href="{{ route('operations.create') }}" class="btn btn-primary">
            Create Operation
        </a>
    @endcan

    {{-- زر "Edit" يظهر فقط لمن لديه صلاحية edit-operations --}}
    @can('edit-operations')
        <button wire:click="edit({{ $operation->id }})" class="btn btn-warning">
            Edit
        </button>
    @endcan

    {{-- زر "Delete" يظهر فقط للطبيب أو المدير --}}
    @role('doctor|admin')
        <button wire:click="delete({{ $operation->id }})" class="btn btn-error">
            Delete
        </button>
    @endrole

    {{-- قسم "Admin Panel" يظهر فقط للمدير --}}
    @role('admin')
        <div class="admin-panel">
            <h3>Admin Panel</h3>
            <!-- محتوى خاص بالمدير -->
        </div>
    @endrole
</div>
```

#### مثال 2: appointment-manager.blade.php

```blade
<div>
    {{-- زر "Create Appointment" - متاح للسكرتير والطبيب --}}
    @can('create-appointments')
        <button wire:click="create" class="btn btn-primary">
            Create Appointment
        </button>
    @endcan

    {{-- زر "Delete" - متاح للسكرتير فقط --}}
    @can('delete-appointments')
        <button wire:click="delete({{ $appointment->id }})" class="btn btn-error">
            Delete
        </button>
    @endcan

    {{-- زر "Go to Assessment" - متاح للطبيب والأخصائي --}}
    @role('doctor|optometrist')
        @if($appointment->visit_type === 'Assessment')
            <button wire:click="goToAssessment({{ $appointment->id }})" class="btn btn-success">
                Go
            </button>
        @endif
    @endrole
</div>
```

---

### 4. في Model User

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles; // بدلاً من isAdmin(), isDoctor(), etc.

    // يمكنك الاحتفاظ بالـ methods القديمة للتوافق
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isDoctor(): bool
    {
        return $this->hasRole('doctor');
    }

    // أو استخدام Spatie مباشرة
    // $user->hasRole('admin')
    // $user->can('create-operations')
    // $user->hasPermissionTo('edit-operations')
}
```

---

### 5. Seeder لإنشاء الأدوار والصلاحيات

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء الصلاحيات
        $permissions = [
            // Patients
            'view-patients',
            'create-patients',
            'edit-patients',
            'delete-patients',
            
            // Appointments
            'view-appointments',
            'create-appointments',
            'edit-appointments',
            'delete-appointments',
            
            // Operations
            'view-operations',
            'create-operations',
            'edit-operations',
            'delete-operations',
            'approve-operations',
            
            // Invoices
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
            
            // Admin
            'manage-users',
            'manage-branches',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // إنشاء الأدوار
        $admin = Role::create(['name' => 'admin']);
        $doctor = Role::create(['name' => 'doctor']);
        $optometrist = Role::create(['name' => 'optometrist']);
        $secretary = Role::create(['name' => 'secretary']);

        // ربط الصلاحيات بالأدوار
        
        // Admin - جميع الصلاحيات
        $admin->givePermissionTo(Permission::all());

        // Doctor - صلاحيات محددة
        $doctor->givePermissionTo([
            'view-patients',
            'create-patients',
            'edit-patients',
            'view-appointments',
            'create-appointments',
            'edit-appointments',
            'view-operations',
            'create-operations',
            'edit-operations',
            'view-invoices',
        ]);

        // Optometrist - صلاحيات محددة
        $optometrist->givePermissionTo([
            'view-patients',
            'view-appointments',
            'create-appointments',
            'view-operations',
            'edit-operations', // فقط لإدخال بيانات الفحص
        ]);

        // Secretary - صلاحيات محددة
        $secretary->givePermissionTo([
            'view-patients',
            'create-patients',
            'edit-patients',
            'view-appointments',
            'create-appointments',
            'edit-appointments',
            'delete-appointments',
            'view-invoices',
            'create-invoices',
            'edit-invoices',
        ]);
    }
}
```

---

### 6. Migration Script لتحويل البيانات القديمة

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class MigrateOldRolesSeeder extends Seeder
{
    public function run(): void
    {
        // ربط المستخدمين الحاليين بأدوارهم
        $users = User::all();
        
        foreach ($users as $user) {
            // إذا كان لديه role في العمود القديم
            if ($user->role) {
                $role = Role::where('name', $user->role)->first();
                if ($role) {
                    $user->assignRole($role);
                }
            }
        }
    }
}
```

---

### 7. Middleware مخصص (اختياري)

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserCanManageOperations
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->can('manage-operations')) {
            abort(403, 'You do not have permission to manage operations.');
        }

        return $next($request);
    }
}
```

---

## 🎯 مثال عملي: سيناريو كامل

### السيناريو:
1. **سكرتير** ينشئ موعد (Appointment) من نوع "Assessment"
2. **طبيب** يضغط على "Go" → يذهب إلى صفحة Operation (تعديل)
3. **أخصائي بصريات** يضغط على "Go" → يذهب إلى صفحة Operation (تعديل فقط، لا يمكن إنشاء)
4. **مدير** يمكنه رؤية كل شيء وإدارة المستخدمين

### الكود:

```php
// في AppointmentManager.php
public function goToAssessment($appointmentId): void
{
    $appointment = Appointment::findOrFail($appointmentId);
    
    // التحقق من الصلاحية
    if (!auth()->user()->can('view-operations')) {
        abort(403, 'You do not have permission to view operations.');
    }
    
    if ($appointment->operation_id) {
        // التحقق من صلاحية التعديل
        if (!auth()->user()->can('edit-operations')) {
            abort(403, 'You do not have permission to edit operations.');
        }
        
        $this->redirect(route('operations.edit', [
            'id' => $appointment->operation_id,
            'appointment_id' => $appointmentId,
        ]));
    } else {
        // التحقق من صلاحية الإنشاء
        if (!auth()->user()->can('create-operations')) {
            abort(403, 'You do not have permission to create operations.');
        }
        
        $this->redirect(route('operations.create', [
            'appointment_id' => $appointmentId,
        ]));
    }
}
```

---

## 📊 جدول المقارنة

| الميزة | النظام الحالي | مع Spatie Permission |
|--------|--------------|---------------------|
| الأدوار | 3 أدوار ثابتة | عدد غير محدود |
| الصلاحيات | لا يوجد | صلاحيات ديناميكية |
| المرونة | محدودة | عالية جداً |
| إدارة من UI | لا | نعم |
| Cache | لا | نعم (مدمج) |
| التوثيق | محدود | شامل |
| الصيانة | صعبة | سهلة |

---

## ✅ الخلاصة

بعد تطبيق Spatie Permission:
- ✅ يمكن إعطاء صلاحيات محددة لكل دور
- ✅ يمكن إدارة الصلاحيات من واجهة المستخدم
- ✅ يمكن إضافة أدوار وصلاحيات جديدة بسهولة
- ✅ الكود أنظف وأسهل في الصيانة
- ✅ أداء أفضل مع Cache مدمج

---

**هل هذا المثال واضح؟ هل تريد البدء في التنفيذ؟**
