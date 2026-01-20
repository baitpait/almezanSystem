# إضافة حقول Planning إلى جدول Operations

## الهدف
إضافة حقول جديدة لتخزين قيم Planning (Sphere, Cylinder, Axis) لكل عين (OD و OS) في جدول `operations`.

## الحقول المضافة
- `planning_sphere_od` - Sphere للعين اليمنى
- `planning_cylinder_od` - Cylinder للعين اليمنى
- `planning_axis_od` - Axis للعين اليمنى
- `planning_sphere_os` - Sphere للعين اليسرى
- `planning_cylinder_os` - Cylinder للعين اليسرى
- `planning_axis_os` - Axis للعين اليسرى

## خطوات التطبيق

### على السيرفر:
```bash
cd /home/sarfesak/public_html/almyzan
mysql -u sarfesak_sarfesak_almyzan -p sarfesak_almyzan < database/fix_add_planning_fields_to_operations.sql
```

### على الجهاز المحلي:
```bash
cd Dr-system
mysql -u root -p your_database_name < database/fix_add_planning_fields_to_operations.sql
```

## الميزات المضافة

### 1. قسم Planning في Recommendation Tab
- يظهر في كل قسم (OD و OS) أو في القسم المشترك
- يحتوي على زر "Get Refraction" لجلب القيم من Manifest Refraction
- المربعات تظهر بعد الضغط على الزر

### 2. وظيفة Get Refraction
- يجلب القيم من `RefractiveProfile` → `manifest_refraction_*`
- يملأ الحقول: Sphere, Cylinder, Axis
- يمكن تعديل القيم بعد الجلب

### 3. الحفظ
- القيم تُحفظ في أعمدة منفصلة (planning_*)
- لا تعدل القيم الأصلية (manifest_refraction_*)

## الملفات المعدلة
1. `database/fix_add_planning_fields_to_operations.sql` - SQL script
2. `app/Models/Operation.php` - إضافة الحقول في $fillable
3. `app/Livewire/OperationManager.php` - إضافة الحقول و method getRefraction()
4. `resources/views/livewire/operation-manager/tabs/recommendation.blade.php` - إضافة قسم Planning

## ملاحظات
- الحقول من نوع VARCHAR(50) ويمكن أن تكون NULL
- القيم تُجلب من RefractiveProfile عند الضغط على "Get Refraction"
- يمكن تعديل القيم بعد الجلب قبل الحفظ
