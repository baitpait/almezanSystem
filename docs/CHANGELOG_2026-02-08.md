# سجل التعديلات — 8 فبراير 2026

توثيق احترافي لجميع التعديلات المنجزة في هذه الجلسة على نظام مركز الغد / مستشفى الميزان التخصصي.

---

## 1. التقرير الطبي (Medical Report)

| الملف | التعديل |
|-------|---------|
| `app/Livewire/MedicalReportForm.php` | مكوّن جديد: نموذج التقرير الطبي مع صلاحية `view.medical_report` وشرط زيارة من نوع Operation. |
| `resources/views/livewire/medical-report-form.blade.php` | واجهة التقرير: ترويسة (صورة أو احتياطية)، عنوان "تقرير طبي"، بيانات المريض (الاسم، تاريخ الميلاد، رقم الهوية كل في سطر)، نص التقرير، تحريراً بتاريخ واسم الطبيب. |
| `resources/views/livewire/medical-report-error.blade.php` | صفحة خطأ عند عدم الصلاحية أو عدم تطابق نوع الزيارة. |
| `resources/views/livewire/medical-report-form.blade.php` | تنسيق الطباعة: سطران فوق النص، أربعة أسطر بين النص وقسم التحرير والطبيب، اسم PDF عشوائي عند الحفظ. |
| `config/permissions.php` | إضافة موديول `medical_report` بصلاحيات view و create. |
| `routes/web.php` | مسار `GET /medical-report/{appointmentId}` باسم `medical-report.form`. |
| `public/images/medical-report-header.png` | صورة الترويسة الرسمية للطباعة. |

- **Report Issue Date:** يُعيّن تلقائياً نفس تاريخ العملية (Procedure Date) ويُحدَّث عند تغييره.
- **PDF:** اسم ملف عشوائي عند الحفظ (تجنب الاستبدال).

---

## 2. المرضى وقائمة المواعيد

| الملف | التعديل |
|-------|---------|
| `resources/views/livewire/patient-manager.blade.php` | إضافة خيار **"All Visits"** في القائمة المنسدلة لكل مريض يوجّه إلى `/appointments?filter_patient_id={id}`. |
| `app/Livewire/AppointmentManager.php` | دعم معامل `filter_patient_id`: فلترة المواعيد بالمريض، إلغاء فلتر التاريخ الافتراضي (عرض كل الزيارات)، تمرير `filterPatient` للواجهة. |
| `resources/views/livewire/appointment-manager.blade.php` | شريط تنبيه عند الفلتر: "Showing visits for: [اسم المريض]" مع رابط "Clear filter". |

---

## 3. الفواتير (Invoices)

| الملف | التعديل |
|-------|---------|
| `resources/views/invoices/print.blade.php` | إزالة صناديق التوقيع والختم (Cashier/Clinic Stamp). إضافة جملة "Signature & Stamp:" في أسفل التقرير بشكل احترافي. اسم PDF عشوائي عند الطباعة/الحفظ. |

---

## 4. العمليات (Refractive — R/G)

| الملف | التعديل |
|-------|---------|
| `resources/views/livewire/operation-manager/tabs/refractive.blade.php` | تحويل حقلي R/G (OD و OS) من نص إلى قائمة منسدلة بالقيم: —، R=g، R، G (بالترتيب). |

---

## 5. صلاحيات الأدوار وعرض البيانات حسب المستخدم

### 5.1 الطبيب يرى مواعيده فقط — الأدمن والسكرتير يرون الكل

| الملف | التعديل |
|-------|---------|
| `app/Livewire/AppointmentManager.php` | عند وجود `filter_patient_id`: فلترة بالمريض وإلغاء فلتر التاريخ. فلتر الطبيب: إذا المستخدم له `doctor` وليس أدمن ولا سكرتير → `where('doctor_id', $currentDoctor->id)` في القائمة وفي التقويم. |
| `app/Livewire/OperationManager.php` | نفس منطق فلتر الطبيب لصفحة `/operations`. |
| `app/Livewire/ScheduledOperations.php` | نفس منطق فلتر الطبيب لصفحة `/scheduled-operations`. |
| `app/Livewire/Dashboard.php` | فلتر الفرع: لا يُطبَّق على الأدمن والسكرتير (يرون كل المواعيد والفواتير). |

### 5.2 السكرتير — قائمة المرضى والفواتير

| الملف | التعديل |
|-------|---------|
| `app/Livewire/Admin/UserManager.php` | عند حفظ مستخدم (إنشاء/تعديل): `$user->syncRoles([$this->form['role']])` ومسح كاش الصلاحيات حتى تظهر صلاحيات Spatie (view.patients, view.invoices، إلخ) لجميع الأدوار (admin, doctor, secretary). |
| `app/Livewire/InvoiceManager.php` | فلتر الفرع: يُطبَّق فقط عندما المستخدم ليس أدمن وليس سكرتير؛ الأدمن والسكرتير يرون كل الفواتير. |
| `database/seeders/AssignAdminRolesToExistingUsers.php` | استخدام `syncRoles([$role->name])` لجميع المستخدمين الذين لديهم قيمة في عمود `role` لمزامنة أدوار Spatie (admin, doctor, secretary). |

---

## 6. تشغيل الـ Seeders بعد التعديلات

تم تشغيل:

- `php artisan db:seed --class=PermissionSeeder` — إنشاء/تحديث الأدوار والصلاحيات.
- `php artisan db:seed --class=AssignAdminRolesToExistingUsers` — مزامنة أدوار Spatie لجميع المستخدمين الموجودين.

---

## ملخص الملفات المتأثرة

- **مكوّنات Livewire:** MedicalReportForm (جديد), AppointmentManager, Dashboard, InvoiceManager, OperationManager, PatientManager, ScheduledOperations, UserManager.
- **Views:** medical-report-form, medical-report-error, appointment-manager, patient-manager, invoices/print, operation-manager (form + refractive).
- **Config:** permissions.php.
- **Routes:** web.php.
- **Database:** AssignAdminRolesToExistingUsers seeder.
- **أصول:** public/images/medical-report-header.png.
- **توثيق:** docs (هذا الملف وغيره حسب الجلسة).

---

*آخر تحديث: 8 فبراير 2026*
