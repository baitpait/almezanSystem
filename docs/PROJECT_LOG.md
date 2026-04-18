# سجل المشروع الرئيسي — PROJECT_LOG

**المشروع:** نظام إدارة طبي — مركز الغد / مستشفى الميزان التخصصي (Dr-system / almezanSystem)  
**آخر تحديث:** 18 أبريل 2026

---

## 1. ملخص المشروع والحالة الحالية

- **الإطار:** Laravel 11 + Livewire، واجهة Tailwind + DaisyUI، قاعدة بيانات MySQL.
- **الأدوار:** Admin، Doctor، Secretary (صلاحيات Spatie من `config/permissions.php`).
- **الوظائف الرئيسية:** المواعيد، المرضى، الفواتير، Assessment، العمليات (Operations)، Operation Notes، التقرير الطبي، لوحة التحكم.
- **المستودع:** GitHub `baitpait/almezanSystem`، الفرع `main`.
- **الحالة:** التعديلات الأخيرة مرفوعة؛ جاهز للسحب على السيرفر عند الحاجة.

---

## 2. أين كل شيء موثق (فهرس الملفات)

| الغرض | الملف | الوصف |
|--------|--------|--------|
| **سجل التعديلات 8 فبراير 2026** | `docs/CHANGELOG_2026-02-08.md` | التقرير الطبي، المرضى/المواعيد، الفواتير، Refractive R/G، صلاحيات الأدوار، Seeders. |
| **سجل التعديلات 18 أبريل 2026** | `docs/CHANGELOG_2026-04-18.md` | استيراد DB محلي، blur للفواتير، `forBranchAccess` للمواعيد، GitHub، أوامر السيرفر. |
| **جلسة 18 أبريل 2026 (DB + فواتير + فرع + Git)** | `docs/SESSION_2026_04_18_DB_INVOICES_BRANCH_GIT.md` | تفصيل الجلسة: نسخة احتياطية، Livewire، ظهور مواعيد الطبيب، رفع `main`. |
| **جلسة زر Operation + السكرتير** | `docs/SESSION_2026_02_08_OPERATION_BUTTON_AND_SECRETARY.md` | زر Operation → Operation Note، تعطيل الزر للسكرتير، الاختبارات، الرفع. |
| **قاعدة البيانات على السيرفر** | `docs/DATABASE_UPDATE_SERVER.md` | نسخة احتياطية، Dry Auto-Ref، PermissionSeeder، AssignAdminRoles — بدون migrate يمس البيانات. |
| **الرفع على GitHub** | `docs/GIT_PUSH_INSTRUCTIONS.md` | أوامر commit و push. |
| **التصميم الموحد** | `docs/DESIGN_SYSTEM_COMPLETE.md` | مرجع CSS والصفحات والجداول والـ Modals. |
| **المحادثة والتاريخ** | `docs/PROJECT_CONVERSATION.md` | تاريخ المشروع، القرارات، الملفات المنشأة/المعدلة، الـ migrations. |
| **فهرس الجلسات** | `docs/SESSION_INDEX.md` | قائمة ملفات الجلسات وملخصاتها. |
| **الصلاحيات** | `docs/PERMISSIONS_SETUP_COMPLETE.md`, `config/permissions.php` | الأدوار والصلاحيات وربط Spatie. |
| **مراجعة الكود** | `CODE_REVIEW_REPORT.md` (في جذر المشروع) | إصلاحات السيرفر، الـ dropdown، operation_notes، إلخ. |
| **تطبيق Laravel AI على النظام** | `docs/LARAVEL_AI_APPLICATION_TO_DR_SYSTEM.md` | قراءة Laravel AI SDK + كيف تطبّق على التطبيق (RAG، صياغة تقارير، تحويل صوت، محادثة، إلخ). |

---

## 3. المسارات والملفات الأساسية (للمتابعة في جلسة جديدة)

| الوظيفة | الملفات الرئيسية |
|---------|-------------------|
| **المواعيد + زر Operation/Assessment** | `app/Livewire/AppointmentManager.php` (مثلاً `goToOperationNote`, `goToAssessment`)، `resources/views/livewire/appointment-manager.blade.php` |
| **فلتر الفرع + مواعيد بدون `branch_id`** | `app/Models/Appointment.php` → `forBranchAccess`؛ استدعاؤه من `AppointmentManager`, `Dashboard`, `OperationManager`, `ScheduledOperations`, `AppServiceProvider`. |
| **فواتير — تحديث عند مغادرة الحقل** | `resources/views/livewire/invoice-manager.blade.php` ونافذة الفاتورة في `appointment-manager.blade.php` — `wire:model.blur` للمبالغ. |
| **تعطيل زر Operation للسكرتير** | نفس الملفين أعلاه: في الـ Blade شرط `@can('view.operations')` + `!auth()->user()->isSecretary()`؛ في الـ PHP تحقق `isSecretary()` في بداية `goToOperationNote()`. |
| **Operation Note (صفحة العملية)** | Route: `operation-notes.create` → `/operation-notes/appointment/{appointmentId}`؛ المكونات ذات صلة: OperationNoteManager، إلخ. |
| **التقرير الطبي** | `app/Livewire/MedicalReportForm.php`، `resources/views/livewire/medical-report-form.blade.php`، route `medical-report.form`. |
| **المرضى + "جميع الزيارات"** | `app/Livewire/AppointmentManager.php` (`filter_patient_id`)، `resources/views/livewire/patient-manager.blade.php` (رابط All Visits). |
| **الفواتير والصلاحيات** | `app/Livewire/InvoiceManager.php` (فلتر الفرع: استثناء Admin و Secretary)، `app/Livewire/Admin/UserManager.php` (syncRoles عند الحفظ). |
| **الأدوار والصلاحيات** | `app/Models/User.php` (`isAdmin()`, `isDoctor()`, `isSecretary()`)، `config/permissions.php`، Seeders: `PermissionSeeder`, `AssignAdminRolesToExistingUsers`. |
| **اختبارات Operation Note** | `tests/Feature/AppointmentOperationNoteTest.php` (الراوت والرابط). |

---

## 4. آخر التعديلات المنجزة

### حتى 18 أبريل 2026

- **قاعدة بيانات محلية:** استيراد نسخة احتياطية من السيرفر إلى `dralmyzin`، `optimize:clear`، وتشغيل `PermissionSeeder` و`AssignAdminRolesToExistingUsers` (راجع `CHANGELOG_2026-04-18.md`).
- **الفواتير (Livewire):** `wire:model.blur` لحقول المبالغ لتقليل تزاحم الطلبات؛ الخدمة تبقى `live` لتحديث السعر فور الاختيار.
- **ظهور مواعيد الطبيب:** إصلاح استبعاد المواعيد ذات `branch_id` الفارغ عند فلترة فرع المستخدم عبر `Appointment::forBranchAccess` في الواجهات ذات الصلة.
- **GitHub:** المستودع `baitpait/almezanSystem`؛ آخر commit مرجعي للجلسة: `336902c`.
- **التوثيق:** `CHANGELOG_2026-04-18.md`، `SESSION_2026_04_18_DB_INVOICES_BRANCH_GIT.md`، وتحديث هذا الملف وفهرس الجلسات.

### حتى 8 فبراير 2026

- **قائمة المواعيد — نوع Operation:** عند النقر على نوع "Operation" يتم التوجيه إلى صفحة Operation Note (`/operation-notes/appointment/{id}`) عبر زر يستدعي `goToOperationNote($appointmentId)`.
- **دور السكرتير:** زر Operation معطّل للسكرتير: في الواجهة يظهر نص "Operation" فقط (بدون زر)، وفي الـ Backend إن استُدعيت الدالة يُرجع خطأ دون توجيه.
- **اختبارات:** إضافة `tests/Feature/AppointmentOperationNoteTest.php` (التحقق من رابط وراوت Operation Note).

---

## 5. ما قد ينقص أو يُتابع في جلسة جديدة

- **Laravel AI:** إن رغبت في إضافة ميزات ذكاء اصطناعي (صياغة تقارير، تحويل صوت، مساعد بروتوكولات، محادثة)، راجع `docs/LARAVEL_AI_APPLICATION_TO_DR_SYSTEM.md` للاقتراحات وترتيب التنفيذ.
- **السيرفر:** بعد `git pull` تنفيذ مسح الكاش (`config:clear`, `view:clear`, `cache:clear`)؛ إن لزم، تطبيق خطوات `docs/DATABASE_UPDATE_SERVER.md` (نسخة احتياطية، Dry Auto-Ref، Seeders) دون تشغيل migrate يمس البيانات الحالية.
- **التوكن (GitHub):** لا تضمّن PAT في `git remote` أو في الدردشة؛ إن تسرّب توكن فألغِه من GitHub فوراً. يُفضّل SSH (`git@github.com:baitpait/almezanSystem.git`).
- **تعبئة `branch_id`:** لتقليل المواعيد بدون فرع، راجع استعلام `UPDATE` في `CHANGELOG_2026-04-18.md`.
- **اختبار يدوي:** التأكد من أن السكرتير يرى "Operation" كنص فقط، وأن الأدمن/الطبيب يرى الزر ويُوجّه لصفحة Operation Note.
- **ملفات أخرى:** إن وُجدت ميزات أو إصلاحات لم تُوثَّق هنا، إضافتها إلى `CHANGELOG_2026-02-08.md` أو إنشاء `SESSION_YYYY_MM_DD_...md` جديد وتحديث هذا السجل.

---

## 6. أوامر سريعة للمتابعة

```bash
# محلياً — تشغيل الاختبارات
php artisan test tests/Feature/AppointmentOperationNoteTest.php

# محلياً — استيراد نسخة SQL (مثال؛ عدّل المسار واسم القاعدة)
# mysql -u root dralmyzin < ~/Downloads/database_backup_YYYY-MM-DD_HHMMSS.sql
# php artisan optimize:clear

# على السيرفر — بعد السحب
cd /path/to/Dr-system
git pull origin main
php artisan optimize:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

*هذا الملف هو المرجع الرئيسي لحالة المشروع وللبداية في جلسة تعديل جديدة. يُحدَّث مع كل جلسة مهمة.*
