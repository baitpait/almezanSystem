# مراجعة قسم Current Eyeglasses
## Current Eyeglasses Section – Code & DB Review

---

## 1. موقع القسم في النظام

- **الصفحة:** Operation Manager (تعديل/إنشاء عملية)
- **التبويب:** Refractive (ملف الانكسار)
- **العنوان:** "Current Eyeglasses" داخل `<div class="divider">`

---

## 2. الواجهة (View)

**الملف:** `resources/views/livewire/operation-manager/tabs/refractive.blade.php`

- **العنوان:** `Current Eyeglasses` (سطر 52)
- **التخطيط:** شبكة عمودين (OD و OS)، كل عين في بطاقة `card bg-base-200 p-4`
- **OD (Right Eye):**
  - Sphere → `refractiveForm.current_eyeglasses_od_sphere`
  - Cylinder → `refractiveForm.current_eyeglasses_od_cylinder`
  - Axis → `refractiveForm.current_eyeglasses_od_axis`
  - Vision → `refractiveForm.current_eyeglasses_od_vision`
- **OS (Left Eye):**
  - Sphere → `refractiveForm.current_eyeglasses_os_sphere`
  - Cylinder → `refractiveForm.current_eyeglasses_os_cylinder`
  - Axis → `refractiveForm.current_eyeglasses_os_axis`
  - Vision → `refractiveForm.current_eyeglasses_os_vision`
- **نوع الحقول:** كلها `<input type="text">` مع `wire:model="refractiveForm...."`

---

## 3. النموذج في Livewire (refractiveForm)

**الملف:** `app/Livewire/OperationManager.php`

المفاتيح الخاصة بـ Current Eyeglasses في `$refractiveForm` (حوالي 72–81):

```php
'current_eyeglasses_od_sphere'   => '',
'current_eyeglasses_od_cylinder' => '',
'current_eyeglasses_od_axis'     => '',
'current_eyeglasses_od_vision'   => '',
'current_eyeglasses_os_sphere'   => '',
'current_eyeglasses_os_cylinder' => '',
'current_eyeglasses_os_axis'     => '',
'current_eyeglasses_os_vision'   => '',
```

- **التعبئة عند التعديل:** في `edit()` يتم تحميل `$operation->refractiveProfile` إلى `$this->refractiveForm` عبر `toArray()`، ثم تحويل `null` إلى `''`.
- **الحفظ:** في `save()` يتم أخذ `$this->refractiveForm` وتحويل القيم الفارغة إلى `null` ثم استدعاء `RefractiveProfile::updateOrCreate(['operation_id' => $operation->id], $refractiveData)`.

---

## 4. الموديل وقاعدة البيانات

**الموديل:** `app/Models/RefractiveProfile.php`

- الجدول: `refractive_profiles`
- الحقول الثمانية مذكورة في `$fillable`:
  - `current_eyeglasses_od_sphere`, `current_eyeglasses_od_cylinder`, `current_eyeglasses_od_axis`, `current_eyeglasses_od_vision`
  - `current_eyeglasses_os_sphere`, `current_eyeglasses_os_cylinder`, `current_eyeglasses_os_axis`, `current_eyeglasses_os_vision`
- لا يوجد `$casts` خاصة بهذه الحقول (تخزين كـ string).

**قاعدة البيانات:**

- الجدول: `refractive_profiles`
- الأعمدة (من `full_database_fresh.sql` و `fix_refractive_profiles_table.sql` و `schema/mysql-schema.sql`):

| العمود | النوع |
|--------|--------|
| `current_eyeglasses_od_sphere`   | varchar(255) NULL |
| `current_eyeglasses_od_cylinder` | varchar(255) NULL |
| `current_eyeglasses_od_axis`    | varchar(255) NULL |
| `current_eyeglasses_od_vision`  | varchar(255) NULL |
| `current_eyeglasses_os_sphere`  | varchar(255) NULL |
| `current_eyeglasses_os_cylinder` | varchar(255) NULL |
| `current_eyeglasses_os_axis`    | varchar(255) NULL |
| `current_eyeglasses_os_vision`  | varchar(255) NULL |

- العلاقة: `refractive_profiles.operation_id` → `operations.id` (سجل واحد RefractiveProfile لكل عملية).

---

## 5. مسار البيانات (تحميل وحفظ)

1. **تحميل:** عند فتح عملية للتعديل → `edit($id)` → تحميل `Operation` و `refractiveProfile` → تعبئة `$this->refractiveForm` من `$refractive->toArray()` مع استبدال `null` بـ `''`.
2. **عرض:** الحقول في تبويب Refractive مربوطة بـ `wire:model="refractiveForm.current_eyeglasses_*"`.
3. **حفظ:** عند الضغط على Save → `save()` → تحديث/إنشاء `Operation` ثم `RefractiveProfile::updateOrCreate(..., $refractiveData)` حيث `$refractiveData` مشتق من `$this->refractiveForm` مع تحويل `''` → `null`.

---

## 6. ملاحظات وتوصيات

- **اتجاه النص والأرقام:** إذا ظهرت أرقام عربية (مثل ٣، ٤، ٦) أو رموز غير متوقعة في الحقول، يمكن إضافة `dir="ltr"` و `inputmode="decimal"` (للمقاييس العددية) أو `inputmode="text"` (لـ Vision مثل 20/20) للحقول لضمان إدخال وعرض بالإنجليزية/أرقام لاتينية.
- **التحقق:** لا يوجد validation خاص بـ Current Eyeglasses في الكود الحالي؛ يمكن لاحقاً إضافة قواعد (مثلاً أرقام فقط لـ Sphere/Cylinder/Axis، وتنسيق مقبول لـ Vision).
- **الترجمة:** النصوص الحالية في القسم بالإنجليزية فقط (Sphere, Cylinder, Axis, Vision, OD, OS).

---

تمت المراجعة: فبراير 2026
