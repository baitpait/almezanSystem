# استراتيجية إنشاء Operations من Appointments

## 📋 المطلوب

### المتطلب الأساسي:
**عند فتح ملف Operation من Appointment → إنشاء Operation تلقائياً إذا لم يكن موجوداً**

**ليس عند إنشاء Appointment، بل عند فتح ملف Operation**

---

## 🔍 التحليل الحالي

### السيناريو الحالي:

#### 1. عند إنشاء Appointment جديد:
```php
// AppointmentManager.php - save()
if ($data['visit_type'] === 'Assessment') {
    // يتم إنشاء Operation تلقائياً
    $operation = Operation::create([...]);
    $appointment->update(['operation_id' => $operation->id]);
}
```
**المشكلة:** هذا يحدث فقط عند إنشاء Appointment جديد، وليس عند فتح الملف.

#### 2. عند فتح Operation من Appointment:
```php
// AppointmentManager.php - goToAssessment()
if ($appointment->operation_id && $appointment->operation) {
    // يذهب إلى edit page
    redirect()->route('operations.edit', ['id' => $appointment->operation_id]);
} else {
    // يذهب إلى create page
    redirect()->route('operations.create', ['appointment_id' => $appointmentId]);
}
```
**المشكلة:** يذهب إلى create page لكن لا يتم إنشاء Operation تلقائياً.

#### 3. في OperationManager - mount():
```php
// OperationManager.php - mount()
$appointmentId = request()->query('appointment_id');
if ($appointmentId) {
    $appointment = Appointment::find($appointmentId);
    if ($appointment) {
        $this->operationForm['appointment_id'] = $appointmentId;
        // Auto-fill doctor_id, patient_id
    }
}
```
**المشكلة:** فقط يملأ البيانات، لكن لا ينشئ Operation.

---

## 💡 الحل المقترح

### الحل: إنشاء Operation تلقائياً عند فتح ملف Operation

#### الخطوة 1: تعديل `goToAssessment()` في AppointmentManager

**السلوك الجديد:**
- إذا كان `operation_id` موجود → يذهب إلى edit page
- إذا لم يكن موجود → **ينشئ Operation تلقائياً** ثم يذهب إلى edit page

```php
public function goToAssessment($appointmentId): void
{
    $appointment = Appointment::with('operation')->findOrFail($appointmentId);
    
    // Check if visit_type is Assessment or Operation
    if (!in_array($appointment->visit_type, ['Assessment', 'Operation'])) {
        session()->flash('error', 'This appointment type does not support assessment.');
        return;
    }
    
    // If operation exists, go to edit
    if ($appointment->operation_id && $appointment->operation) {
        return redirect()->route('operations.edit', [
            'id' => $appointment->operation_id,
            'appointment_id' => $appointmentId,
        ]);
    }
    
    // If no operation, create it automatically
    $operation = Operation::create([
        'patient_id' => $appointment->patient_id,
        'doctor_id' => $appointment->doctor_id,
        'branch_id' => $appointment->branch_id,
        'appointment_id' => $appointment->id,
        'created_by' => auth()->id(),
        'operation_type' => 'Femto-LASIK', // Default
        'operation_eye' => 'OU',
        'cost' => 0.00,
        'status' => 'scheduled',
        'start_date' => $appointment->appointment_date,
    ]);
    
    // Link appointment to operation
    $appointment->update(['operation_id' => $operation->id]);
    
    // Redirect to edit page
    return redirect()->route('operations.edit', [
        'id' => $operation->id,
        'appointment_id' => $appointmentId,
    ]);
}
```

---

#### الخطوة 2: التعامل مع تغيير visit_type

**السيناريوهات:**

##### أ) تغيير من "Assessment" أو "Operation" إلى نوع آخر:
```php
if ($oldVisitType === 'Assessment' || $oldVisitType === 'Operation') {
    if ($newVisitType !== 'Assessment' && $newVisitType !== 'Operation') {
        // إلغاء ربط Operation
        if ($appointment->operation_id) {
            $operation = Operation::find($appointment->operation_id);
            if ($operation && $operation->isEmpty()) {
                // إذا Operation فارغ → حذفه
                $operation->delete();
            } else {
                // إذا Operation له بيانات → فقط إلغاء الربط
                $operation->update(['appointment_id' => null]);
            }
            $data['operation_id'] = null;
        }
    }
}
```

##### ب) تغيير إلى "Assessment" أو "Operation":
```php
if (in_array($newVisitType, ['Assessment', 'Operation'])) {
    if (!$appointment->operation_id) {
        // إنشاء Operation تلقائياً
        $operation = Operation::create([...]);
        $data['operation_id'] = $operation->id;
    }
}
```

---

## 🎯 السيناريوهات المطلوبة

### السيناريو 1: فتح Assessment من Appointment
```
المستخدم يضغط "Go" في Appointment من نوع "Assessment"
    ↓
إذا Operation موجود → يذهب إلى edit
إذا Operation غير موجود → ينشئ Operation تلقائياً → يذهب إلى edit
```

### السيناريو 2: فتح Operation من Appointment
```
المستخدم يضغط "Note" في Appointment من نوع "Operation"
    ↓
إذا Operation موجود → يذهب إلى operation notes
إذا Operation غير موجود → ينشئ Operation تلقائياً → يذهب إلى operation notes
```

### السيناريو 3: تغيير visit_type من "Assessment" إلى "New visit"
```
المستخدم يغير visit_type من "Assessment" إلى "New visit"
    ↓
إذا Operation فارغ → حذفه
إذا Operation له بيانات → إلغاء الربط فقط
```

### السيناريو 4: تغيير visit_type من "New visit" إلى "Assessment"
```
المستخدم يغير visit_type من "New visit" إلى "Assessment"
    ↓
إنشاء Operation تلقائياً وربطه
```

---

## ⚠️ اعتبارات مهمة

### 1. حماية البيانات:
- لا نحذف Operation إذا كان له بيانات
- فقط نلغي الربط إذا كان فارغاً

### 2. التزامن:
- التأكد من عدم إنشاء Operation مكرر
- التحقق من وجود Operation قبل الإنشاء

### 3. الأداء:
- استخدام transactions عند إنشاء Operation وربطه
- التأكد من تحديث Appointment بشكل صحيح

---

## 📝 الخلاصة

**الحل المقترح:**
1. ✅ تعديل `goToAssessment()` لإنشاء Operation تلقائياً عند فتح الملف
2. ✅ تعديل `save()` في AppointmentManager للتعامل مع تغيير visit_type
3. ✅ إضافة نفس المنطق لـ "Operation" visit_type
4. ✅ حماية البيانات الموجودة في Operations

**المزايا:**
- ✅ لا يحتاج المستخدم لإنشاء Operation يدوياً
- ✅ تلقائي عند فتح الملف
- ✅ آمن للبيانات الموجودة
- ✅ يتعامل مع جميع السيناريوهات

---

**تاريخ التوثيق**: 26 ديسمبر 2025

