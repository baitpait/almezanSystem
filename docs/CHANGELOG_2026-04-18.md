# سجل التعديلات — 18 أبريل 2026

توثيق الجلسة: قاعدة بيانات محلية، فواتير Livewire، ظهور مواعيد الطبيب حسب الفرع، GitHub.

---

## 1. قاعدة البيانات المحلية (استيراد من السيرفر)

| الإجراء | التفاصيل |
|---------|-----------|
| **ملف النسخة** | `database_backup_2026-04-18_130730.sql` (MariaDB، قاعدة المصدر على السيرفر: `sarfesak_almyzan`). |
| **الهدف المحلي** | قيم `.env`: `DB_DATABASE=dralmyzin`, `DB_USERNAME=root` (حسب بيئة التطوير). |
| **الأمر** | `mysql -u root dralmyzin < /path/to/database_backup_2026-04-18_130730.sql` |
| **بعد الاستيراد** | `php artisan optimize:clear` |
| **Seeders (حسب `docs/DATABASE_UPDATE_SERVER.md`)** | `php artisan db:seed --class=PermissionSeeder --force` ثم `AssignAdminRolesToExistingUsers --force` |
| **تنبيه** | الاستيراد يستبدل بيانات القاعدة المحلية بالكامل حسب محتوى الملف. |

---

## 2. الفواتير — `wire:model.live` وقيمة المدفوع

| المشكلة | عند الكتابة السريعة على سيرفر بطيء، طلبات Livewire المتعددة قد تسبب عدم تطابق ترتيب الاستجابات أو سلوكاً غير متوقع مع `type="number"`. |
| **الحل** | استبدال `wire:model.live` بـ **`wire:model.blur`** على حقول المبالغ الرقمية (تحديث السيرفر عند مغادرة الحقل). |
| **الملفات** | `resources/views/livewire/invoice-manager.blade.php` (`form.subtotal`, `form.discount`, `form.paid_amount`)؛ `resources/views/livewire/appointment-manager.blade.php` (نافذة الفاتورة: `invoiceForm.subtotal`, `discount`, `tax`, `paid_amount`). |
| **ملاحظة** | حقل **Service** أبقي عليه `wire:model.live` لأن التغيير حدث مرة واحدة ويحدّث السعر فوراً. |

---

## 3. مواعيد الطبيب لا تظهر — `branch_id` فارغ

| **السبب الجذري** | موعد مسجّل بـ `doctor_id` صحيح لكن **`appointments.branch_id = NULL`**، بينما الاستعلام كان يفرض `where('branch_id', فرع_المستخدم)` فيُستبعد السجل عن الطبيب رغم أن الصلاحيات والربط مع `Doctor` صحيحان. |
| **الحل البرمجي** | إضافة نطاق Eloquent **`Appointment::forBranchAccess(int $branchId)`**: `(branch_id = ? OR branch_id IS NULL)` لاستيعاب السجلات القديمة أو غير المكتملة. |
| **الملفات المعدّلة** | `app/Models/Appointment.php`؛ `app/Livewire/AppointmentManager.php`؛ `app/Livewire/Dashboard.php`؛ `app/Livewire/OperationManager.php`؛ `app/Livewire/ScheduledOperations.php`؛ `app/Providers/AppServiceProvider.php` (عداد التقييمات في الشريط الجانبي). |
| **إصلاح جانبي** | إزالة `break` مكرر في `Dashboard.php` داخل `getRoleBasedData`. |

### تعبئة بيانات اختيارية (SQL)

لتوحيد البيانات مستقبلاً (تقليل الاعتماد على `NULL`):

```sql
UPDATE appointments a
JOIN doctors d ON d.id = a.doctor_id
SET a.branch_id = d.branch_id
WHERE a.branch_id IS NULL AND d.branch_id IS NOT NULL;
```

---

## 4. GitHub والمستودع

| **المستودع الحالي** | `https://github.com/baitpait/almezanSystem` — الفرع `main`. |
| **Commits مرجعية** | `336902c` — فرع + blur فواتير؛ `503e369` — توثيق الجلسة؛ `5709c84` — تنقل المواعيد + عمليات مجدولة (انظر §6–7). |
| **أمان** | لا تخزين **Personal Access Token** داخل رابط `git remote`؛ لا نشر التوكن في الدردشة أو الوثائق — إلغاء التوكن من GitHub إن تسرّب. |

---

## 5. تحديث السيرفر بعد الرفع

```bash
cd /home/sarfesak/public_html/almyzan   # أو مسار النشر الفعلي
cp .env .env.backup
git pull origin main
cp .env.backup .env
composer install --no-dev --optimize-autoloader
npm ci || npm install && npm run build
php artisan optimize:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**لماذا `cp .env .env.backup`؟** ملف `.env` غير مرفوع على Git ويحتوي أسرار البيئة؛ النسخ الاحتياطي السريع قبل `git pull` يحمي من أي خطأ دمج/سكربت يمس الملف. غالباً يبقى المحتوى كما هو ثم تُستعاد النسخة فوق نفسها بعد السحب.

راجع أيضاً `DEPLOY_UPDATE_SERVER.md` للتفاصيل الكاملة.

---

## 6. قائمة المواعيد — إجراءات إضافية في القائمة المنسدلة

| البند | التفاصيل |
|-------|----------|
| **All Visits** | رابط `appointments.index?filter_patient_id=…` مع `@can('view.appointments')`. |
| **Open assessment** | `wire:click="goToAssessment"` عند `visit_type === 'Assessment'` و `can('view.assessment')`. |
| **Operation note** | `wire:click="goToOperationNote"` عند `visit_type === 'Operation'` و `view.operations` وعدم السكرتير. |
| **الملف** | `resources/views/livewire/appointment-manager.blade.php` |

---

## 7. عمليات مجدولة + فلتر نوع الزيارة من الرابط

| البند | التفاصيل |
|-------|----------|
| **رابط التقييمات** | من `/scheduled-operations`: أيقونة (بدون نص) تفتح `/appointments?filter_patient_id={patient}&filter_visit_type=Assessment`. |
| **Mount** | في `AppointmentManager::mount`: عند وجود `filter_patient_id` يُقرأ `filter_visit_type` (قيم مسموحة: Assessment, Operation, Follow up, New visit) ويُطبَّق على `visitTypeFilter`. |
| **واجهة الأزرار** | حاوية `rounded-xl` رمادية؛ زر العرض: `btn-action btn-add`؛ رابط التقييمات: `btn-action btn-visit`؛ أيقونات فقط مع `title` و `aria-label`. |
| **الملفات** | `app/Livewire/AppointmentManager.php`؛ `resources/views/livewire/scheduled-operations.blade.php` |

---

## 8. رفع GitHub (تابع)

| **Commit** | `5709c84` — `feat(appointments): dropdown shortcuts and scheduled-ops assessment link` |

---

*18–19 أبريل 2026*
