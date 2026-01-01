# شرح العلاقات: Patient, Appointment, Operation, Assessment

## 📊 نظرة عامة

هذا الملف يشرح العلاقات الحالية بين:
- **Patient** (المريض)
- **Appointment** (الزيارة)
- **Operation** (العملية/التقييم)
- **Assessment** (نوع من أنواع الزيارات)

---

## 🔗 العلاقات الأساسية

### 1. Patient (المريض) - الكيان الرئيسي

```
Patient (المريض)
    ├── hasMany Appointments (له عدة زيارات)
    └── hasMany Operations (له عدة عمليات/تقييمات)
```

**العلاقات في الكود:**
```php
// Patient.php
public function appointments(): HasMany
{
    return $this->hasMany(Appointment::class);
}

public function operations(): HasMany
{
    return $this->hasMany(Operation::class);
}
```

---

### 2. Appointment (الزيارة) - موعد الزيارة

```
Appointment (الزيارة)
    ├── belongsTo Patient (ينتمي لمريض)
    ├── belongsTo Doctor (ينتمي لطبيب)
    ├── belongsTo Branch (ينتمي لفرع)
    ├── belongsTo Operation (اختياري - يمكن أن يكون مرتبط بعملية)
    └── visit_type (نوع الزيارة: Assessment, Operation, Follow up, New visit)
```

**الحقول المهمة:**
- `patient_id` - المريض
- `doctor_id` - الطبيب
- `branch_id` - الفرع
- `appointment_date` - تاريخ الزيارة
- `appointment_time` - وقت الزيارة
- `visit_type` - نوع الزيارة (Assessment, Operation, Follow up, New visit)
- `visit_stage` - مرحلة الزيارة (waiting, in_consultation, completed)
- `operation_id` - **مرتبط بعملية (اختياري)**

**العلاقات في الكود:**
```php
// Appointment.php
public function patient(): BelongsTo
{
    return $this->belongsTo(Patient::class);
}

public function operation(): BelongsTo
{
    return $this->belongsTo(Operation::class);
}
```

---

### 3. Operation (العملية/التقييم) - التقييم أو العملية الجراحية

```
Operation (العملية/التقييم)
    ├── belongsTo Patient (ينتمي لمريض)
    ├── belongsTo Doctor (ينتمي لطبيب)
    ├── belongsTo Branch (ينتمي لفرع)
    ├── belongsTo Appointment (اختياري - يمكن أن يكون مرتبط بزيارة)
    ├── hasMany RefractiveProfile (له ملفات انكسارية)
    ├── hasMany MedicalHistory (له تاريخ طبي)
    ├── hasMany EyeExaminations (له فحوصات عينية)
    ├── hasMany EctasiaRiskAssessment (له تقييم مخاطر)
    ├── hasMany OperationDetails (له تفاصيل العملية)
    ├── hasMany Appointments (له عدة زيارات مرتبطة)
    └── hasMany Invoices (له عدة فواتير)
```

**الحقول المهمة:**
- `patient_id` - المريض
- `doctor_id` - الطبيب
- `branch_id` - الفرع
- `appointment_id` - **مرتبط بزيارة (اختياري)**
- `operation_type` - نوع العملية (Femto-LASIK, PRK, SMILE, etc.)
- `operation_eye` - العين (OD, OS, OU)
- `status` - الحالة (scheduled, in_progress, completed, cancelled)
- `start_date` - تاريخ البدء
- `cost` - التكلفة

**العلاقات في الكود:**
```php
// Operation.php
public function patient(): BelongsTo
{
    return $this->belongsTo(Patient::class);
}

public function appointment(): BelongsTo
{
    return $this->belongsTo(Appointment::class);
}

public function appointments(): HasMany
{
    return $this->hasMany(Appointment::class);
}
```

---

### 4. Assessment - ليس كيان منفصل!

**⚠️ مهم جداً:**
- **Assessment** ليس جدول منفصل في قاعدة البيانات
- **Assessment** هو قيمة في حقل `visit_type` في جدول `appointments`
- عندما `visit_type = 'Assessment'` → هذا يعني أن الزيارة هي تقييم

**أنواع Visit Type:**
- `Assessment` - تقييم
- `Operation` - عملية
- `Follow up` - متابعة
- `New visit` - زيارة جديدة

---

## 🔄 العلاقة الثنائية بين Appointment و Operation

### العلاقة الحالية:

```
Appointment                    Operation
    │                              │
    ├── operation_id ──────────────┤ (belongsTo)
    │                              │
    └──────────────────────────────┼── appointment_id (belongsTo)
```

**هذه علاقة ثنائية الاتجاه (Bidirectional):**

1. **Appointment → Operation:**
   - `appointments.operation_id` → يشير إلى `operations.id`
   - Appointment يمكن أن يكون له Operation مرتبط

2. **Operation → Appointment:**
   - `operations.appointment_id` → يشير إلى `appointments.id`
   - Operation يمكن أن يكون له Appointment مرتبط

**⚠️ ملاحظة مهمة:**
- العلاقة **ليست إجبارية** (nullable)
- يمكن أن يكون Appointment بدون Operation
- يمكن أن يكون Operation بدون Appointment
- يمكن أن يكون كلاهما مرتبطين ببعض

---

## 📋 السيناريوهات المختلفة

### السيناريو 1: زيارة Assessment عادية (بدون Operation)

```
Patient
    └── Appointment (visit_type = 'Assessment')
            └── operation_id = NULL
```

**النتيجة:**
- ✅ الزيارة موجودة في جدول `appointments`
- ❌ لا تظهر في صفحة Assessment (لأن صفحة Assessment تعرض فقط Operations)

---

### السيناريو 2: زيارة Assessment مع Operation

```
Patient
    └── Appointment (visit_type = 'Assessment')
            ├── operation_id = 1
            └── Operation (id = 1)
                    └── appointment_id = Appointment.id
```

**النتيجة:**
- ✅ الزيارة موجودة في جدول `appointments`
- ✅ العملية موجودة في جدول `operations`
- ✅ تظهر في صفحة Assessment
- ✅ كلاهما مرتبطين ببعض

---

### السيناريو 3: Operation بدون Appointment

```
Patient
    └── Operation
            └── appointment_id = NULL
```

**النتيجة:**
- ✅ العملية موجودة في جدول `operations`
- ✅ تظهر في صفحة Assessment
- ❌ لا ترتبط بزيارة

---

### السيناريو 4: زيارة Operation (عملية جراحية)

```
Patient
    └── Appointment (visit_type = 'Operation')
            ├── operation_id = 2
            └── Operation (id = 2)
                    └── appointment_id = Appointment.id
```

**النتيجة:**
- ✅ الزيارة موجودة في جدول `appointments`
- ✅ العملية موجودة في جدول `operations`
- ✅ تظهر في صفحة Assessment
- ✅ كلاهما مرتبطين ببعض

---

## 🎯 السلوك الحالي في النظام

### عند إنشاء Appointment جديد:

**الكود في `AppointmentManager.php`:**
```php
if ($data['visit_type'] === 'Assessment') {
    // يتم إنشاء Operation تلقائياً
    $operation = Operation::create([
        'patient_id' => $appointment->patient_id,
        'doctor_id' => $appointment->doctor_id,
        'branch_id' => $appointment->branch_id,
        'appointment_id' => $appointment->id,
        'operation_type' => 'Femto-LASIK', // Default
        'status' => 'scheduled',
    ]);
    
    // ربط Appointment بالOperation
    $appointment->update(['operation_id' => $operation->id]);
}
```

**النتيجة:**
- ✅ عند إنشاء Appointment من نوع "Assessment" → يتم إنشاء Operation تلقائياً
- ✅ يتم ربط كلاهما ببعض
- ✅ تظهر في صفحة Assessment مباشرة

---

### عند عرض صفحة Assessment:

**الكود في `OperationManager.php`:**
```php
$query = Operation::with(['patient', 'doctor', 'branch', 'appointment'])
    ->when($branchId, function ($q) use ($branchId) {
        $q->where('branch_id', $branchId);
    })
    // ... filters ...
    ->orderBy('start_date', 'desc');

$operations = $query->paginate($this->perPage);
```

**النتيجة:**
- ✅ تعرض فقط Operations من جدول `operations`
- ❌ لا تعرض Appointments التي ليس لها Operation

---

## 🔍 لماذا لا تظهر Appointments في Assessment؟

### المشكلة:

1. **صفحة Assessment تستعلم فقط عن Operations:**
   ```php
   Operation::with(['patient', 'doctor', 'branch', 'appointment'])
   ```

2. **Appointments الجديدة (60 زيارة) موجودة في جدول `appointments` فقط**

3. **لا توجد Operations مرتبطة بهذه Appointments**

4. **النتيجة:** صفحة Assessment فارغة

---

## 📊 الإحصائيات الحالية

```
Appointments: 59 زيارة
├── Assessment: 18 زيارة
├── Operation: X زيارة
├── Follow up: X زيارة
└── New visit: X زيارة

Operations: 0 عملية
└── مرتبطة بـ Appointments: 0
```

**النتيجة:**
- ❌ لا توجد Operations في قاعدة البيانات
- ❌ صفحة Assessment فارغة
- ✅ Appointments موجودة لكن غير مرتبطة بـ Operations

---

## 💡 الحلول الممكنة

### الحل 1: إنشاء Operations تلقائياً (موجود حالياً)

**الكود موجود في `AppointmentManager.php`:**
- ✅ عند إنشاء Appointment جديد من نوع "Assessment" → ينشئ Operation تلقائياً
- ❌ لكن Appointments القديمة (60 زيارة) لم يتم إنشاء Operations لها

**الحل:** إنشاء Seeder لتحويل Appointments القديمة إلى Operations

---

### الحل 2: عرض Appointments في صفحة Assessment

تعديل صفحة Assessment لعرض:
- Operations الموجودة
- Appointments من نوع "Assessment" التي ليس لها Operation

---

### الحل 3: إنشاء Operations يدوياً

إضافة زر "Create Assessment" في صفحة Appointments لإنشاء Operation من Appointment.

---

## 📝 ملخص العلاقات

```
Patient (1) ──hasMany──> (N) Appointment
Patient (1) ──hasMany──> (N) Operation

Appointment (N) ──belongsTo──> (1) Patient
Appointment (N) ──belongsTo──> (1) Doctor
Appointment (N) ──belongsTo──> (1) Branch
Appointment (N) ──belongsTo──> (1) Operation (nullable)

Operation (N) ──belongsTo──> (1) Patient
Operation (N) ──belongsTo──> (1) Doctor
Operation (N) ──belongsTo──> (1) Branch
Operation (N) ──belongsTo──> (1) Appointment (nullable)
Operation (1) ──hasMany──> (N) Appointment
```

---

## 🎯 الخلاصة

1. **Patient** هو الكيان الرئيسي (له Appointments و Operations)
2. **Appointment** هو موعد الزيارة (يمكن أن يكون مرتبط بـ Operation)
3. **Operation** هو التقييم أو العملية (يمكن أن يكون مرتبط بـ Appointment)
4. **Assessment** هو نوع من أنواع الزيارات (`visit_type = 'Assessment'`)
5. **العلاقة ثنائية:** Appointment ↔ Operation (كلاهما يمكن أن يشير للآخر)
6. **صفحة Assessment تعرض فقط Operations** (وليس Appointments)

---

**تاريخ التوثيق**: 26 ديسمبر 2025

