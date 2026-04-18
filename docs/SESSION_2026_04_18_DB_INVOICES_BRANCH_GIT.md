# جلسة 18 أبريل 2026 — قاعدة بيانات، فواتير، فرع المواعيد، GitHub

## الهدف

1. مزامنة قاعدة البيانات المحلية مع نسخة احتياطية من السيرفر وتشغيل السيرفر المحلي وتنظيف الكاش.  
2. تحليل مشكلة حقل المدفوع في الفاتورة ثم تقليل طلبات Livewire المباشرة.  
3. إصلاح عدم ظهور موعد للطبيب بسبب `branch_id` فارغ على الموعد.  
4. رفع التعديلات إلى GitHub (`baitpait/almezanSystem`).

---

## 1) استيراد قاعدة البيانات محلياً

- **الملف:** `database_backup_2026-04-18_130730.sql`  
- **القاعدة المحلية:** `dralmyzin` (من `.env`).  
- **بعد الاستيراد:** `php artisan optimize:clear`  
- **Seeders:** `PermissionSeeder` و `AssignAdminRolesToExistingUsers` (متوافقة مع `docs/DATABASE_UPDATE_SERVER.md`).  
- أعمدة Dry Auto-Ref في `refractive_profiles` كانت موجودة في النسخة المستوردة؛ لم يُشغَّل سكربت SQL إضافي لها.

---

## 2) الفواتير — `paid_amount` و Livewire

**التحليل:** مع أرقام إنجليزية، السبب الأرجح لقفز القيمة أثناء الكتابة هو **تزاحم طلبات `wire:model.live`** على سيرفر/شبكة بطيئة، وليس بالضرورة خطأ في `(float)` وحده.

**التنفيذ:** استبدال `wire:model.live` بـ **`wire:model.blur`** على حقول المبالغ في:

- `invoice-manager.blade.php`  
- نافذة إنشاء الفاتورة داخل `appointment-manager.blade.php`  

حقل اختيار الخدمة بقي **`live`** لتحديث المبلغ فور اختيار الخدمة.

---

## 3) موعد Operation لد. طارق لا يظهر في حسابه

**الظاهرة:** الموعد يظهر للأدمن في `/appointments` ولا يظهر عند تسجيل الدخول كد. طارق.

**التشخيص:**  
- `appointments.doctor_id` يطابق طبيب المستخدم (`doctors.user_id`).  
- **`appointments.branch_id` كان `NULL`** بينما المستخدم له `branch_id = 1`.  
- الشرط السابق: `where('branch_id', 1)` يستبعد أي صف `branch_id IS NULL`.

**الحل:** نطاق **`forBranchAccess($branchId)`** على نموذج `Appointment` وتطبيقه في كل استعلامات المواعيد ذات الصلة (قائمة، تقويم، داشبورد، عمليات مجدولة، Operation manager، عداد التقييمات في `AppServiceProvider`).

---

## 4) GitHub

- **المستودع:** `https://github.com/baitpait/almezanSystem`  
- **الفرع:** `main`  
- **Commit:** `336902c` — `fix: branch visibility for appointments and blur invoice amounts`

**أمان:** لا يُوثَّق **GitHub PAT** في ملفات المشروع؛ يُفضّل SSH أو Credential Helper. أي توكن وُضع في محادثة يُنصح بإلغائه من GitHub.

---

## 5) ملفات الكود المرجعية (للمتابعة)

| الموضوع | الملفات |
|---------|---------|
| نطاق الفرع | `app/Models/Appointment.php` → `scopeForBranchAccess` |
| قائمة/تقويم المواعيد | `app/Livewire/AppointmentManager.php` |
| الداشبورد | `app/Livewire/Dashboard.php` |
| العمليات والمجدولة | `app/Livewire/OperationManager.php`, `ScheduledOperations.php` |
| الشريط الجانبي (عدد التقييمات) | `app/Providers/AppServiceProvider.php` |
| blur للفواتير | `resources/views/livewire/invoice-manager.blade.php`, `appointment-manager.blade.php` |

---

## 6) ما يُنصح به لاحقاً

- تشغيل **SQL التعبئة** (في `CHANGELOG_2026-04-18.md`) لتقليل المواعيد ذات `branch_id` الفارغ.  
- التأكد أن من ينشئ المواعيد له **`branch_id`** في حسابه حتى يُملأ الحقل تلقائياً عند الحفظ (`auth()->user()->branch_id`).  
- بعد كل `git pull` على السيرفر: `php artisan optimize:clear` وإعادة بناء الأصول عند الحاجة.

---

*نهاية توثيق الجلسة — 18 أبريل 2026*
