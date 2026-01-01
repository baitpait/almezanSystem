# 📋 دليل ترحيل Pagination - Pagination Migration Guide

## 🎨 التصميم والتنسيق الحالي (Current Design & Styling)

### ✅ الصفحة المرجعية (Reference Page)
**الصفحة:** `patient-manager.blade.php`  
**الحالة:** ✅ تم التطبيق بنجاح

### 🎯 مواصفات التصميم (Design Specifications)

#### 1. **الألوان (Colors)**
- **الصفحة النشطة (Active Page):**
  - Background: `#3b82f6` (Blue-500)
  - Text: `#ffffff` (White)
  - Border: `#3b82f6`
  - Shadow: Blue glow effect

- **الصفحات العادية (Normal Pages):**
  - Background: `#ffffff` (White)
  - Text: `#374151` (Gray-700)
  - Border: `#d1d5db` (Gray-300)

- **Hover State:**
  - Background: `#eff6ff` (Blue-50)
  - Text: `#1e40af` (Blue-800)
  - Border: `#3b82f6` (Blue-500)
  - Transform: `translateY(-1px)`
  - Shadow: Light elevation

- **Disabled State:**
  - Background: `#f3f4f6` (Gray-100)
  - Text: `#9ca3af` (Gray-400)
  - Border: `#e5e7eb` (Gray-200)
  - Opacity: `0.6`

#### 2. **التنسيق (Layout)**
- **حجم الأزرار (Button Size):**
  - Desktop: `40px × 40px`
  - Mobile: `36px × 36px`

- **الزوايا (Border Radius):**
  - `0.5rem` (rounded-lg)

- **التباعد (Spacing):**
  - Gap between buttons: `4px`
  - Padding wrapper: `px-6 py-4`

- **الانتقالات (Transitions):**
  - Duration: `200ms`
  - Easing: `ease-in-out`

#### 3. **البنية (Structure)**
```html
<div class="pagination-wrapper">
    <div class="pagination-buttons">
        {{ $collection->links() }}
    </div>
</div>
```

---

## 📝 المتطلبات (Requirements)

### ✅ المتطلبات الأساسية (Basic Requirements)

1. **استخدام Pagination Theme:**
   - يجب إضافة `protected $paginationTheme = 'tailwind';` في كل Livewire Component

2. **HTML Structure:**
   - استخدام `pagination-wrapper` كـ container
   - استخدام `pagination-buttons` كـ wrapper للأزرار

3. **Conditional Rendering:**
   - عرض pagination فقط عند وجود صفحات أو نتائج

4. **Consistency:**
   - نفس التصميم في جميع الصفحات
   - نفس الألوان والتنسيق

---

## 📋 قائمة الصفحات المطلوب ترحيلها (Pages to Migrate)

### ✅ الصفحات المكتملة (Completed)
- [x] **Patient Manager** (`patient-manager.blade.php`)
  - ✅ تم التطبيق
  - ✅ يستخدم `pagination-wrapper`
  - ✅ يستخدم `paginationTheme = 'tailwind'`

### 🔄 الصفحات المطلوب ترحيلها (Pages to Migrate)

#### 1. **Appointment Manager** (`appointment-manager.blade.php`)
- **الملف:** `resources/views/livewire/appointment-manager.blade.php`
- **المكون:** `App\Livewire\AppointmentManager`
- **المتغير:** `$appointments`
- **الحالة الحالية:** يستخدم `{{ $appointments->links() }}` مباشرة
- **المطلوب:** إضافة wrapper و paginationTheme

#### 2. **Scheduled Operations** (`scheduled-operations.blade.php`)
- **الملف:** `resources/views/livewire/scheduled-operations.blade.php`
- **المكون:** `App\Livewire\ScheduledOperations`
- **المتغير:** `$operations`
- **الحالة الحالية:** يستخدم `pagination-container` (يحتاج تغيير)
- **المطلوب:** تغيير إلى `pagination-wrapper` و paginationTheme

#### 3. **User Manager** (`admin/user-manager.blade.php`)
- **الملف:** `resources/views/livewire/admin/user-manager.blade.php`
- **المكون:** `App\Livewire\Admin\UserManager`
- **المتغير:** `$users`
- **الحالة الحالية:** يستخدم `{{ $users->links() }}` مباشرة
- **المطلوب:** إضافة wrapper و paginationTheme

#### 4. **Operation Manager** (`operation-manager.blade.php`)
- **الملف:** `resources/views/livewire/operation-manager.blade.php`
- **المكون:** `App\Livewire\OperationManager`
- **المتغير:** `$operations`
- **الحالة الحالية:** يستخدم `{{ $operations->links() }}` مباشرة
- **المطلوب:** إضافة wrapper و paginationTheme

#### 5. **Invoice Manager** (`invoice-manager.blade.php`)
- **الملف:** `resources/views/livewire/invoice-manager.blade.php`
- **المكون:** `App\Livewire\InvoiceManager`
- **المتغير:** `$invoices`
- **الحالة الحالية:** يستخدم `{{ $invoices->links() }}` مباشرة
- **المطلوب:** إضافة wrapper و paginationTheme

#### 6. **Branch Manager** (`admin/branch-manager.blade.php`)
- **الملف:** `resources/views/livewire/admin/branch-manager.blade.php`
- **المكون:** `App\Livewire\Admin\BranchManager`
- **المتغير:** `$branches`
- **الحالة الحالية:** يستخدم `{{ $branches->links() }}` مباشرة
- **المطلوب:** إضافة wrapper و paginationTheme

---

## 🔧 خطوات التطبيق (Implementation Steps)

### الخطوة 1: تحديث Livewire Component
```php
// في كل Livewire Component
class ComponentName extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind'; // إضافة هذا السطر
    
    // ... باقي الكود
}
```

### الخطوة 2: تحديث Blade Template
```blade
{{-- Pagination --}}
@if($collection->hasPages() || $collection->total() > 0)
<div class="pagination-wrapper">
    @if($collection->hasPages())
    <div class="pagination-buttons">
        {{ $collection->links() }}
    </div>
    @endif
</div>
@endif
```

### الخطوة 3: التحقق من النتيجة
- [ ] الأزرار تظهر بنفس التصميم
- [ ] الألوان صحيحة (أزرق للصفحة النشطة)
- [ ] Hover effects تعمل
- [ ] Responsive يعمل على الموبايل
- [ ] النص "Showing X to Y of Z Patient" يظهر (أو النص المناسب للصفحة)

---

## 📊 Checklist لكل صفحة (Per-Page Checklist)

### ✅ Patient Manager
- [x] Livewire Component: `protected $paginationTheme = 'tailwind';`
- [x] Blade Template: `pagination-wrapper` structure
- [x] CSS: Styles applied
- [x] Testing: Verified working

### 🔄 Appointment Manager
- [ ] Livewire Component: Add `protected $paginationTheme = 'tailwind';`
- [ ] Blade Template: Update to `pagination-wrapper` structure
- [ ] Testing: Verify working

### 🔄 Scheduled Operations
- [ ] Livewire Component: Add `protected $paginationTheme = 'tailwind';`
- [ ] Blade Template: Change `pagination-container` to `pagination-wrapper`
- [ ] Testing: Verify working

### 🔄 User Manager
- [ ] Livewire Component: Add `protected $paginationTheme = 'tailwind';`
- [ ] Blade Template: Update to `pagination-wrapper` structure
- [ ] Testing: Verify working

### 🔄 Operation Manager
- [ ] Livewire Component: Add `protected $paginationTheme = 'tailwind';`
- [ ] Blade Template: Update to `pagination-wrapper` structure
- [ ] Testing: Verify working

### 🔄 Invoice Manager
- [ ] Livewire Component: Add `protected $paginationTheme = 'tailwind';`
- [ ] Blade Template: Update to `pagination-wrapper` structure
- [ ] Testing: Verify working

### 🔄 Branch Manager
- [ ] Livewire Component: Add `protected $paginationTheme = 'tailwind';`
- [ ] Blade Template: Update to `pagination-wrapper` structure
- [ ] Testing: Verify working

---

## 🎯 ملاحظات مهمة (Important Notes)

1. **النص في Pagination:**
   - في `tailwind.blade.php` تم تغيير "results" إلى "Patient"
   - قد تحتاج لتغيير النص حسب الصفحة (مثلاً: "Appointments", "Operations", etc.)
   - أو يمكن استخدام نفس النص "Patient" في جميع الصفحات

2. **CSS موجود:**
   - جميع الأنماط موجودة في `resources/css/design-system.css`
   - لا حاجة لإضافة CSS جديد

3. **التنظيف:**
   - بعد كل تعديل، قم بتشغيل:
     ```bash
     php artisan view:clear
     npm run build
     ```

4. **الاختبار:**
   - تأكد من اختبار pagination على:
     - الصفحة الأولى
     - الصفحات الوسطى
     - الصفحة الأخيرة
     - حالة Disabled (عند عدم وجود صفحات سابقة/لاحقة)

---

## 🚀 الترتيب المقترح للتطبيق (Suggested Order)

1. **Appointment Manager** - الأسهل (يستخدم links مباشرة)
2. **Scheduled Operations** - يحتاج تغيير class name
3. **User Manager** - مشابه لـ Appointment
4. **Operation Manager** - مشابه
5. **Invoice Manager** - مشابه
6. **Branch Manager** - آخر واحد

---

## 📞 الدعم (Support)

إذا واجهت أي مشاكل:
1. تحقق من أن `paginationTheme = 'tailwind'` موجود
2. تحقق من أن HTML structure صحيح
3. نظف الكاش: `php artisan view:clear`
4. أعد بناء CSS: `npm run build`

---

**تاريخ الإنشاء:** 2025-01-XX  
**آخر تحديث:** 2025-01-XX  
**الحالة:** قيد التنفيذ (In Progress)

