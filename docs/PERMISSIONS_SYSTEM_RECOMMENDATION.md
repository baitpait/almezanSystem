# نظام الصلاحيات - التوصية الاحترافية

## 📊 تحليل النظام الحالي

### الوضع الحالي:
- ✅ حقل `role` بسيط في جدول `users` (enum: admin, doctor, secretary)
- ✅ Middleware بسيط `admin` للتحقق من الصلاحيات
- ✅ Methods بسيطة: `isAdmin()`, `isDoctor()`, `isSecretary()`

### المشاكل:
- ❌ نظام محدود - لا يدعم صلاحيات ديناميكية
- ❌ صعب التوسع - إضافة دور جديد يحتاج تعديل الكود
- ❌ لا يدعم صلاحيات جزئية (مثلاً: doctor يمكنه إنشاء operations لكن لا يمكنه حذفها)
- ❌ لا يدعم إدارة الصلاحيات من واجهة المستخدم

---

## 🎯 التوصية: **Laravel Spatie Permission Package**

### لماذا Spatie Permission؟

#### ✅ **المميزات:**
1. **معتمد على نطاق واسع**: أكثر من 10,000+ stars على GitHub
2. **موثق جيداً**: توثيق شامل بالعربية والإنجليزية
3. **مرن وقوي**: يدعم Roles و Permissions بشكل منفصل
4. **أداء عالي**: يدعم Cache مدمج
5. **سهل الاستخدام**: API بسيط وواضح
6. **يدعم Multi-tenancy**: مناسب للـ branches
7. **قابل للتوسع**: سهل إضافة صلاحيات جديدة
8. **يدعم Middleware جاهز**: لا حاجة لكتابة middleware مخصص
9. **يدعم Blade Directives**: `@can`, `@role`, `@hasanyrole`
10. **يدعم Livewire**: يمكن استخدامه مباشرة في Livewire components

#### 📦 **البنية:**
- **Roles**: مجموعات من الصلاحيات (مثلاً: Doctor, Secretary, Admin)
- **Permissions**: صلاحيات فردية (مثلاً: create-operations, edit-operations, delete-operations)
- **User → Roles → Permissions**: علاقة Many-to-Many

---

## 🏗️ البنية المقترحة

### 1. **الأدوار (Roles):**
```
- admin          → صلاحيات كاملة
- doctor         → إدارة العمليات والمرضى
- optometrist    → إدخال بيانات الفحص
- secretary      → إدارة المواعيد والفواتير
- receptionist  → إدارة المواعيد فقط
- accountant     → إدارة الفواتير فقط
```

### 2. **الصلاحيات (Permissions):**

#### **Patients:**
- `view-patients`
- `create-patients`
- `edit-patients`
- `delete-patients`

#### **Appointments:**
- `view-appointments`
- `create-appointments`
- `edit-appointments`
- `delete-appointments`
- `manage-appointments` (جميع الصلاحيات)

#### **Operations:**
- `view-operations`
- `create-operations`
- `edit-operations`
- `delete-operations`
- `approve-operations`

#### **Invoices:**
- `view-invoices`
- `create-invoices`
- `edit-invoices`
- `delete-invoices`
- `manage-payments`

#### **Admin:**
- `manage-users`
- `manage-branches`
- `manage-settings`
- `view-reports`

---

## 📋 خطة التنفيذ

### **المرحلة 1: التثبيت والإعداد**
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### **المرحلة 2: تحديث Model User**
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles; // بدلاً من methods بسيطة
}
```

### **المرحلة 3: إنشاء Roles و Permissions**
- Seeder لإنشاء الأدوار والصلاحيات الأساسية
- ربط الأدوار بالصلاحيات

### **المرحلة 4: تحديث Middleware**
- استبدال `EnsureUserIsAdmin` بـ Spatie middleware
- إضافة middleware للصلاحيات المحددة

### **المرحلة 5: تحديث Routes**
- استخدام `role:` و `permission:` middleware
- حماية Routes بالصلاحيات المناسبة

### **المرحلة 6: تحديث Livewire Components**
- استخدام `@can`, `@role` في Blade
- استخدام `$this->authorize()` في Livewire

### **المرحلة 7: واجهة إدارة الصلاحيات**
- صفحة لإدارة الأدوار والصلاحيات
- ربط المستخدمين بالأدوار

---

## 🔄 خطة الهجرة من النظام الحالي

### **الخطوة 1: الحفاظ على التوافق**
- الاحتفاظ بـ `role` column للتوافق مع البيانات القديمة
- إنشاء Roles في Spatie بناءً على `role` column

### **الخطوة 2: Migration Script**
- Script لتحويل `role` column إلى Spatie Roles
- ربط المستخدمين الحاليين بأدوارهم

### **الخطوة 3: التحديث التدريجي**
- تحديث Routes تدريجياً
- تحديث Components تدريجياً
- اختبار شامل في كل مرحلة

---

## 💡 أمثلة الاستخدام

### **في Routes:**
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin only routes
});

Route::middleware(['auth', 'permission:create-operations'])->group(function () {
    // Users with create-operations permission
});
```

### **في Livewire:**
```php
public function save(): void
{
    $this->authorize('create-operations');
    // Save logic
}
```

### **في Blade:**
```blade
@can('edit-operations')
    <button wire:click="edit">Edit</button>
@endcan

@role('admin')
    <a href="/admin">Admin Panel</a>
@endrole
```

---

## 🎯 القرار النهائي

### **التوصية: استخدام Laravel Spatie Permission**

#### **الأسباب:**
1. ✅ **احترافي ومعتمد**: يستخدمه آلاف المشاريع
2. ✅ **مرن**: يدعم أي عدد من الأدوار والصلاحيات
3. ✅ **قابل للتوسع**: سهل إضافة صلاحيات جديدة
4. ✅ **أداء عالي**: Cache مدمج
5. ✅ **سهل الصيانة**: API واضح وموثق
6. ✅ **يدعم Multi-tenancy**: مناسب للـ branches
7. ✅ **يدعم Livewire**: متوافق مع النظام الحالي

#### **البدائل (غير موصى بها):**
- ❌ **Laravel Gate & Policy**: يحتاج عمل يدوي أكثر، أقل مرونة
- ❌ **Custom RBAC**: يحتاج وقت تطوير طويل، صيانة أصعب

---

## 📝 الخطوات التالية

1. **الموافقة على التوصية**
2. **تثبيت Package**
3. **إنشاء Migration Script**
4. **تحديث Models**
5. **تحديث Routes**
6. **تحديث Components**
7. **إنشاء واجهة إدارة الصلاحيات**
8. **اختبار شامل**

---

## ⚠️ ملاحظات مهمة

- **التوافق مع البيانات القديمة**: يجب الحفاظ على `role` column أثناء الانتقال
- **الاختبار**: اختبار شامل قبل النشر
- **النسخ الاحتياطي**: نسخ احتياطي للبيانات قبل التحديث
- **التدريب**: تدريب المستخدمين على النظام الجديد

---

**تاريخ التوصية**: 2025-12-20
**الحالة**: جاهز للتنفيذ
