# Design System Documentation - Complete Guide

## نظرة عامة
هذا الملف يحتوي على التوثيق الكامل للتصميم الموحد لجميع صفحات النظام.

---

## 1. Page Header (رأس الصفحة)

### البنية الموحدة:
```blade
<div class="page-header">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1>Page Title</h1>
            <p>Page description</p>
        </div>
        <button class="btn-add btn-action flex items-center gap-2" wire:click="create">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Button Text
        </button>
    </div>
</div>
```

### CSS Classes:
- `.page-header`: Container مع border-left blue و background gray-100
- `h1`: Text-2xl, font-bold, text-gray-900
- `p`: Text-sm, text-gray-700, mt-1

---

## 2. Search Container (حاوية البحث)

### البنية الموحدة:
```blade
<div class="search-container">
    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div class="search-input-wrapper flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" 
                class="form-input" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search...">
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-700 font-medium">Per Page:</label>
            <select class="form-select form-select-sm" wire:model.live="perPage">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
</div>
```

### CSS Classes:
- `.search-container`: mb-6, p-4, bg-white, rounded-lg, shadow-sm, border, border-gray-200
- `.search-input-wrapper`: relative, flex-1
- `.form-input`: px-4 py-2.5, border, rounded-lg, focus:ring-2, focus:ring-blue-500
- `.form-select-sm`: px-3 py-1.5, text-sm, min-height: 38px

---

## 3. Data Table (جدول البيانات)

### البنية الموحدة:
```blade
<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th class="sticky left-0 z-10 bg-white">Column 1</th>
                <th>Column 2</th>
                <th class="text-right sticky right-0 z-10 bg-white">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td class="sticky left-0 z-10 bg-white">{{ $item->field1 }}</td>
                <td>{{ $item->field2 }}</td>
                <td class="sticky right-0 z-10 bg-white text-right">
                    <!-- Dropdown Menu -->
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="X" class="empty-state" style="grid-column: 1 / -1;">
                    <svg>...</svg>
                    <h3>No items found</h3>
                    <p>Start by adding your first item</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
```

### CSS Classes:
- `.data-table-container`: overflow-x-auto, mb-6, border, border-gray-200, rounded-lg, bg-white, shadow-sm
- `.data-table`: w-full, border-collapse
- `.data-table thead th`: bg-gray-50, px-6, py-4, text-left, font-semibold, text-gray-900, border-b, border-gray-200
- `.data-table thead th.text-right`: text-align: right !important
- `.data-table tbody td`: px-6, py-4, border-b, border-gray-100, text-gray-800
- `.empty-state`: py-12, text-center, flex flex-col items-center gap-3

---

## 4. Action Dropdown Menu (قائمة الإجراءات)

### البنية الموحدة:
```blade
<div class="relative inline-block" data-dropdown-container="{{ $item->id }}">
    <button type="button" 
            class="btn btn-sm btn-ghost" 
            onclick="toggleSimpleDropdown({{ $item->id }}, event)"
            data-dropdown-trigger="{{ $item->id }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </button>
    <div class="simple-dropdown-menu" 
         id="dropdown-menu-{{ $item->id }}"
         data-dropdown-menu="{{ $item->id }}"
         data-original-parent="{{ $item->id }}"
         style="display: none;">
        <ul class="dropdown-menu-list">
            <li>
                <button type="button" class="dropdown-menu-item dropdown-menu-item-edit" wire:click="edit({{ $item->id }})" onclick="closeSimpleDropdown({{ $item->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit</span>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-menu-item dropdown-menu-item-delete" wire:click="delete({{ $item->id }})" wire:confirm="Are you sure?" onclick="closeSimpleDropdown({{ $item->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Delete</span>
                </button>
            </li>
        </ul>
    </div>
</div>
```

### CSS Classes:
- `.simple-dropdown-menu`: absolute, z-50, mt-2, w-48, bg-white, rounded-lg, shadow-lg, border, border-gray-200
- `.dropdown-menu-list`: py-1
- `.dropdown-menu-item`: w-full, px-4, py-2, text-left, flex items-center gap-3, hover:bg-gray-50
- `.dropdown-menu-item-edit`: text-blue-700
- `.dropdown-menu-item-delete`: text-red-700
- `.dropdown-menu-icon`: h-4 w-4

---

## 5. Badges (الشارات)

### البنية الموحدة:
```blade
<span class="badge-status badge-{{ $status }}">{{ $statusText }}</span>
```

### CSS Classes:
- `.badge-status`: px-3 py-1, rounded-full, text-xs, font-semibold
- `.badge-waiting`: bg-yellow-100, text-yellow-800
- `.badge-in_consultation`: bg-blue-100, text-blue-800
- `.badge-completed`: bg-green-100, text-green-800
- `.badge-cancelled`: bg-red-100, text-red-800
- `.badge-assessment`: bg-purple-100, text-purple-800
- `.badge-operation`: bg-red-100, text-red-800
- `.badge-follow_up`: bg-blue-100, text-blue-800
- `.badge-new_visit`: bg-green-100, text-green-800

---

## 6. Pagination (ترقيم الصفحات)

### البنية الموحدة:
```blade
@if($items->hasPages() || $items->total() > 0)
<div class="pagination-wrapper">
    @if($items->hasPages())
    <div class="pagination-buttons">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endif
```

### CSS Classes:
- `.pagination-wrapper`: flex, items-center, justify-end, px-6, py-4, border-t, border-gray-200, bg-white
- `.pagination-wrapper .pagination a, .pagination-wrapper .pagination span`: min-w-[36px], h-9, flex, items-center, justify-center, text-sm, font-semibold, rounded-lg, transition-all, duration-200, border, border-gray-300, shadow-sm
- `.pagination-wrapper .pagination a`: text-gray-700, bg-white, hover:bg-blue-50, hover:text-blue-700, hover:border-blue-300
- `.pagination-wrapper .pagination span.active`: bg-blue-600, text-white, border-blue-600, shadow-md, font-weight: 700
- `.pagination-wrapper .pagination span.disabled`: text-gray-400, bg-gray-50, border-gray-200, cursor-not-allowed, opacity-60

### Pagination Text:
- Right-aligned: "Showing X to Y of Z Patient"
- Uses `pagination-info` class

---

## 7. Modal Structure (بنية النافذة المنبثقة)

### البنية الموحدة:
```blade
@if($showModal)
<div class="modal-overlay" wire:click.self="resetForm">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">Modal Title</h2>
            <button class="btn-cancel btn-action flex items-center justify-center" wire:click="resetForm" title="Close">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form wire:submit.prevent="save">
                <!-- Form Content -->
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancel btn-action" wire:click="resetForm">Cancel</button>
                    <button type="submit" class="btn-add btn-action flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Save Button Text
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
```

### CSS Classes:
- `.modal-overlay`: fixed, inset-0, bg-black, bg-opacity-50, z-50, flex, items-center, justify-center, p-4
- `.modal-container`: bg-white, rounded-lg, shadow-xl, max-w-2xl, w-full, max-h-[90vh], overflow-y-auto
- `.modal-header`: flex, items-center, justify-between, px-6, py-4, border-b, border-gray-200
- `.modal-title`: text-xl, font-bold, text-gray-900
- `.modal-body`: px-6, py-4
- `.modal-footer`: flex, items-center, justify-end, gap-3, px-6, py-4, border-t, border-gray-200

---

## 8. Form Elements (عناصر النموذج)

### Form Groups:
```blade
<div class="form-group">
    <label class="form-label">Label Text *</label>
    <input type="text" class="form-input" wire:model.defer="form.field" required>
    @error('form.field') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
</div>
```

### CSS Classes:
- `.form-group`: mb-4
- `.form-label`: block, text-sm, font-semibold, text-gray-700, mb-2
- `.form-input`: w-full, px-4, py-2.5, border, border-gray-300, rounded-lg, bg-white, focus:ring-2, focus:ring-blue-500, focus:border-blue-500, transition-all, duration-200
- `.form-select`: w-full, px-4, py-2.5, border, border-gray-300, rounded-lg, bg-white, focus:ring-2, focus:ring-blue-500, focus:border-blue-500, transition-all, duration-200, min-width: 100%

---

## 9. Card Sections (أقسام البطاقات)

### البنية الموحدة:
```blade
<div class="card-modern mb-6">
    <div class="card-header">
        <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <!-- Icon Path -->
            </svg>
            Section Title
        </h3>
    </div>
    <div class="card-body">
        <!-- Content -->
    </div>
</div>
```

### CSS Classes:
- `.card-modern`: bg-white, rounded-lg, shadow-sm, border, border-gray-200, transition-all, duration-200, hover:shadow-md
- `.card-header`: px-6, py-4, border-b, border-gray-200
- `.card-body`: px-6, py-4

---

## 10. Buttons (الأزرار)

### Add Button:
```blade
<button class="btn-add btn-action flex items-center gap-2" wire:click="create">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>
    Add Text
</button>
```

### CSS Classes:
- `.btn-action`: px-4, py-2, rounded-lg, font-semibold, text-sm, transition-all, duration-200, focus:outline-none, focus:ring-2, focus:ring-offset-2, font-weight: 600, letter-spacing: 0.01em
- `.btn-add`: bg-blue-50, text-blue-700, border-2, border-blue-300, hover:bg-blue-100, focus:ring-blue-500, shadow-sm, hover:shadow-md, font-weight: 600
- `.btn-cancel`: bg-gray-50, text-gray-700, border-2, border-gray-300, hover:bg-gray-100, focus:ring-gray-500

---

## 11. Success Message (رسالة النجاح)

### البنية الموحدة:
```blade
@if(session()->has('message'))
<div class="alert alert-success mb-6 shadow-lg animate-fade-in">
    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span>{{ session('message') }}</span>
</div>
@endif
```

---

## 12. Pages Structure (بنية الصفحات)

### Patient Management Page:
1. Page Header: "Patient Management" + "Add Patient" button
2. Success Message (if exists)
3. Search Container: Search input + Per Page dropdown
4. Data Table: Name, Phone, Age, Actions (sticky columns)
5. Pagination: Right-aligned with "Patient" text

### Appointment Management Page:
1. Page Header: "Appointment Management" + "Add Appointment" button
2. Success Message (if exists)
3. Search Container: Search input + Per Page dropdown
4. Data Table: Date, Time, Patient, Doctor, Visit Type, Visit Stage, Duration, Actions
5. Pagination: Right-aligned with "Patient" text

---

## 13. Modal Forms (نماذج النوافذ المنبثقة)

### Add/Edit Patient Modal:
- Structure: modal-overlay → modal-container → modal-header → modal-body → modal-footer
- Form Fields:
  - Full Name (full width)
  - ID Number & Date of Birth (2 columns)
  - Gender & City (2 columns)
  - Occupation & Phone (2 columns)
  - Phone (2) (full width)
  - Notes (full width, textarea)
- Buttons: Cancel (btn-cancel) + Add/Update Patient (btn-add)

### Add/Edit Appointment Modal:
- Structure: modal-overlay → modal-container → modal-header → modal-body → modal-footer
- Sections:
  1. Patient Information Card:
     - Search Patient (with Add Patient button)
     - Patient Name (read-only)
     - Last Visit Date (read-only)
  2. Appointment Details Card:
     - Schedule Date, Time, Duration (3 columns)
  3. Doctor & Visit Details Card:
     - Doctor, Visit Stage, Visit Type (3 columns)
  4. Notes (full width)
- Buttons: Cancel (btn-cancel) + Create/Update Appointment (btn-add)

---

## 14. Important Notes (ملاحظات مهمة)

### Validation Rules:
- **Phone Number**: NO unique validation (can be repeated)
- **ID Number**: Unique validation (cannot be repeated)
- **Phone Secondary**: Must be different from primary phone

### Sticky Columns:
- First column (Name/Patient): `sticky left-0 z-10 bg-white`
- Last column (Actions): `sticky right-0 z-10 bg-white text-right`

### Grid Layouts:
- 2 columns: `grid grid-cols-1 md:grid-cols-2 gap-4`
- 3 columns: `grid grid-cols-1 md:grid-cols-3 gap-4`
- Full width: `md:col-span-2` or `md:col-span-3`

### Colors:
- Primary Blue: `blue-600`, `blue-700`, `blue-50`, `blue-100`
- Gray: `gray-50`, `gray-100`, `gray-200`, `gray-300`, `gray-700`, `gray-800`, `gray-900`
- Success Green: `green-100`, `green-800`
- Warning Yellow: `yellow-100`, `yellow-800`
- Error Red: `red-100`, `red-500`, `red-700`, `red-800`

---

## 15. Files Modified (الملفات المعدلة)

### CSS Files:
- `resources/css/design-system.css`: All design system styles

### View Files:
- `resources/views/livewire/patient-manager.blade.php`: Patient Management page
- `resources/views/livewire/appointment-manager.blade.php`: Appointment Management page
- `resources/views/vendor/pagination/tailwind.blade.php`: Pagination view

### Component Files:
- `app/Livewire/PatientManager.php`: Patient management logic
- `app/Livewire/AppointmentManager.php`: Appointment management logic

---

## 16. JavaScript Functions (وظائف JavaScript)

### Dropdown Menu:
```javascript
function toggleSimpleDropdown(id, event) {
    // Toggle dropdown menu
}

function closeSimpleDropdown(id) {
    // Close dropdown menu
}
```

---

## 17. Livewire Properties (خصائص Livewire)

### Common Properties:
- `$search`: Search query
- `$perPage`: Items per page (default: 10)
- `$showModal`: Show/hide modal
- `$editingId`: ID of item being edited
- `$paginationTheme`: 'tailwind'

### Patient Manager:
- `$form`: Array of patient form fields
- `$showDetailsModal`: Show patient details modal
- `$selectedPatient`: Selected patient data

### Appointment Manager:
- `$form`: Array of appointment form fields
- `$patientSearch`: Patient search query
- `$selectedPatientId`: Selected patient ID
- `$selectedPatientData`: Selected patient data
- `$patientForm`: Quick add patient form
- `$showPatientModal`: Show quick add patient modal

---

## 18. Complete Example (مثال كامل)

### Complete Page Structure:
```blade
<div class="container mx-auto p-4">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1>Page Title</h1>
                <p>Page description</p>
            </div>
            <button class="btn-add btn-action flex items-center gap-2" wire:click="create">
                <svg>...</svg>
                Add Item
            </button>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session()->has('message'))
    <div class="alert alert-success mb-6 shadow-lg animate-fade-in">
        <svg>...</svg>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    {{-- Search Container --}}
    <div class="search-container">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
            <div class="search-input-wrapper flex-1">
                <svg>...</svg>
                <input type="text" class="form-input" wire:model.live.debounce.300ms="search" placeholder="Search...">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-700 font-medium">Per Page:</label>
                <select class="form-select form-select-sm" wire:model.live="perPage">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="data-table-container">
        <table class="data-table">
            <!-- Table content -->
        </table>
    </div>

    {{-- Pagination --}}
    @if($items->hasPages() || $items->total() > 0)
    <div class="pagination-wrapper">
        @if($items->hasPages())
        <div class="pagination-buttons">
            {{ $items->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- Modals --}}
    <!-- Modal content -->
</div>
```

---

## 19. Key Design Principles (مبادئ التصميم الرئيسية)

1. **Consistency**: جميع الصفحات تستخدم نفس التصميم والبنية
2. **Responsive**: تصميم متجاوب يعمل على جميع الأجهزة
3. **Accessibility**: استخدام semantic HTML و ARIA attributes
4. **Performance**: استخدام lazy loading و debounce للبحث
5. **User Experience**: تصميم واضح وسهل الاستخدام

---

## 20. Maintenance Notes (ملاحظات الصيانة)

- جميع التغييرات يجب أن تتم في `design-system.css`
- استخدام نفس CSS classes في جميع الصفحات
- الحفاظ على نفس البنية والترتيب
- اختبار التصميم على جميع الصفحات بعد أي تغيير

---

**آخر تحديث**: {{ date('Y-m-d H:i:s') }}

