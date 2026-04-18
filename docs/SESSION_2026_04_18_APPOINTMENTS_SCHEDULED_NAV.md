# جلسة 18–19 أبريل 2026 — تنقل المواعيد، عمليات مجدولة، توثيق وسيرفر

## الهدف

1. تسهيل الوصول من **قائمة المواعيد** إلى كل زيارات المريض وملف التقييم / Operation Note من القائمة المنسدلة لكل صف.  
2. من **Scheduled Operations** فتح قائمة مواعيد المريض **مقيّدة بنوع Assessment** بضغطة واحدة (أيقونة).  
3. توثيق التغييرات، شرح نسخ `.env` على السيرفر، والرفع إلى GitHub.

---

## 1) قائمة المواعيد (`/appointments`)

**الملف:** `resources/views/livewire/appointment-manager.blade.php`

| بند القائمة | الشرط | السلوك |
|-------------|--------|---------|
| All Visits | `view.appointments` | `route('appointments.index', ['filter_patient_id' => patient_id])` |
| Open assessment | `visit_type === 'Assessment'` و `view.assessment` | `goToAssessment(id)` |
| Operation note | `visit_type === 'Operation'` و `view.operations` وليس سكرتيراً | `goToOperationNote(id)` |

يتوافق مع منطق الشارات في نفس الجدول (نفس الصلاحيات).

---

## 2) فلتر نوع الزيارة من استعلام الرابط

**الملف:** `app/Livewire/AppointmentManager.php` — داخل `mount()` عند وجود `filter_patient_id`:

- قراءة `filter_visit_type` من الطلب.
- إن كانت القيمة واحدة من: `Assessment`, `Operation`, `Follow up`, `New visit` → تعيين `visitTypeFilter`.

**مثال رابط:**  
`/appointments?filter_patient_id=58&filter_visit_type=Assessment`

---

## 3) Scheduled Operations (`/scheduled-operations`)

**الملف:** `resources/views/livewire/scheduled-operations.blade.php`

- عمود الإجراءات: حاوية بحدود رمادية خفيفة.
- زر فتح الملاحظة: **`btn-action btn-add`** (هوية زرقاء من `design-system.css`)، أيقونة فقط، `title` / `aria-label`.
- رابط تقييمات المريض: **`btn-action btn-visit`** (أخضر النظام)، أيقونة فقط، يظهر مع `view.appointments`.

---

## 4) السيرفر و `.env`

قبل `git pull` على السيرفر يُنصح بـ:

```bash
cp .env .env.backup
```

**السبب:** `.env` غير في Git ويحتوي أسرار البيئة؛ النسخ الاحتياطي يقلل خطر فقدان الإعدادات عند أي تعارض أو خطأ. التفصيل في `CHANGELOG_2026-04-18.md` §5.

---

## 5) Git

- **المستودع:** `https://github.com/baitpait/almezanSystem` — `main`  
- **Commit:** `5709c84` — `feat(appointments): dropdown shortcuts and scheduled-ops assessment link`

---

*نهاية توثيق الجلسة*
