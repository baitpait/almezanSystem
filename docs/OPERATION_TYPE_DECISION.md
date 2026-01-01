# قرار: تعديل Operation Type لدعم أنواع مختلفة لكل عين

## 📋 المطلوب:

**Operation Type** يجب أن يدعم:
1. ✅ **نوع مختلف لكل عين**: العين اليمنى (OD) يمكن أن تكون نوع مختلف عن العين اليسرى (OS)
2. ✅ **نفس النوع للعينين**: يمكن اختيار نفس النوع للعينين
3. ✅ **عند `operation_eye = OU`**: يجب إظهار dropdown منفصل لكل عين
4. ✅ **عند `operation_eye = OD` أو `OS`**: يجب إظهار dropdown واحد

---

## 🎯 القرار المقترح:

### **البنية المقترحة:**

#### **1. قاعدة البيانات:**
- ✅ **إضافة حقول جديدة**: `operation_type_od` و `operation_type_os` (string nullable)
- ✅ **الاحتفاظ بـ `operation_type`**: للتوافق مع البيانات القديمة
- ✅ **Migration**: إضافة الحقول الجديدة

#### **2. Model (Operation.php):**
- ✅ إضافة `operation_type_od` و `operation_type_os` إلى `$fillable`

#### **3. Livewire Component (OperationManager.php):**
- ✅ إضافة `operation_type_od` و `operation_type_os` إلى `operationForm`
- ✅ إضافة `same_operation_type_both_eyes` checkbox
- ✅ إضافة method `updatedOperationFormOperationEye()` لمزامنة `operation_type` عند تغيير `operation_eye`
- ✅ إضافة method `updatedOperationFormSameOperationTypeBothEyes()` لمزامنة OD إلى OS
- ✅ تحديث `save()` لحفظ الحقول الجديدة
- ✅ تحديث `edit()` لتحميل الحقول الجديدة

#### **4. الواجهة (basic.blade.php أو form.blade.php):**

**عند `operation_eye = OU`:**
```
┌─────────────────────────────────────┐
│ Operation Eye: OU (Both)            │
├─────────────────────────────────────┤
│ ☑ Same operation type for both eyes │
├─────────────────────────────────────┤
│ Right Eye (OD):                      │
│ [Operation Type OD ▼]               │
│                                     │
│ Left Eye (OS):                      │
│ [Operation Type OS ▼] (disabled if checked)
└─────────────────────────────────────┘
```

**عند `operation_eye = OD` أو `OS`:**
```
┌─────────────────────────────────────┐
│ Operation Eye: OD (Right)           │
├─────────────────────────────────────┤
│ Operation Type:                     │
│ [Operation Type ▼]                  │
└─────────────────────────────────────┘
```

---

## 📊 القيم الممكنة لـ Operation Type:

من الكود الحالي:
- LASIK
- Femto-LASIK
- PRK
- Trans-PRK
- SMILE
- PTK
- Topography Guided
- Presbyopia
- Other

---

## 🔄 منطق المزامنة:

### **عند تفعيل "Same operation type for both eyes":**
1. نسخ `operation_type_od` إلى `operation_type_os`
2. تعطيل dropdown OS
3. عند تغيير OD، يتم نسخ القيمة تلقائياً إلى OS

### **عند إلغاء التفعيل:**
1. تفعيل dropdown OS
2. السماح باختيار قيم مختلفة

### **عند تغيير `operation_eye` من OU إلى OD/OS:**
1. نسخ `operation_type_od` إلى `operation_type` (إذا كان OD)
2. نسخ `operation_type_os` إلى `operation_type` (إذا كان OS)

### **عند تغيير `operation_eye` من OD/OS إلى OU:**
1. نسخ `operation_type` إلى `operation_type_od` و `operation_type_os`

---

## ✅ المميزات:

1. **مرونة**: يمكن اختيار نوع مختلف لكل عين
2. **سهولة الاستخدام**: checkbox لتطبيق نفس النوع على العينين
3. **التوافق**: الحفاظ على `operation_type` للبيانات القديمة
4. **الاتساق**: نفس منطق Decision (decision_od, decision_os)

---

## ⚠️ ملاحظات مهمة:

1. **البيانات القديمة**: يجب تحويل `operation_type` إلى `operation_type_od` و `operation_type_os` عند `operation_eye = OU`
2. **التحقق**: التأكد من أن `operation_type_od` و `operation_type_os` ليسا فارغين عند `operation_eye = OU`
3. **التوافق**: الحفاظ على `operation_type` للتوافق مع الكود القديم

---

## 📝 خطوات التنفيذ:

1. ✅ Migration: إضافة `operation_type_od` و `operation_type_os`
2. ✅ Model: تحديث `$fillable`
3. ✅ Livewire: إضافة الحقول والمنطق
4. ✅ الواجهة: تحديث form.blade.php
5. ✅ Migration Script: تحويل البيانات القديمة

---

## ❓ هل توافق على هذا القرار؟

**إذا وافقت، سأبدأ التنفيذ فوراً.**
