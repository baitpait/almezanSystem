# ترحيل تعديلات قاعدة البيانات من اللوكل إلى السيرفر

بدون فقدان بيانات العميل على السيرفر. نفّذ بالترتيب.

---

## 1) نسخ احتياطي لقاعدة البيانات على السيرفر (إلزامي)

قبل أي تعديل، اصدر نسخة احتياطية:

```bash
cd /home/sarfesak/public_html/almyzan
php artisan db:show
```

دَوّن اسم القاعدة واسم المستخدم. ثم من الشيل (أو من cPanel → Backup):

```bash
# مثال إذا كان اسم القاعدة: almyzan_db والمستخدم: almyzan_user
mysqldump -u almyzan_user -p almyzan_db > ~/backup_before_update_$(date +%Y%m%d_%H%M).sql
```

أو استخدم "Backup" من cPanel إن وُجد. احفظ الملف في مكان آمن.

---

## 2) إضافة أعمدة Dry Auto-Ref (جدول refractive_profiles)

هذا يضيف أعمدة فقط ولا يحذف أي بيانات.

على السيرفر:

```bash
cd /home/sarfesak/public_html/almyzan
mysql -u USER -p DATABASE_NAME < database/fix_add_dry_auto_ref_to_refractive_profiles.sql
```

استبدل `USER` و `DATABASE_NAME` بقيمك من `.env` (DB_USERNAME و DB_DATABASE).  
إذا ظهر خطأ أن العمود موجود مسبقاً، تجاهله (معناه أن التعديل مُطبَّق فعلاً).

**محتوى الملف للمراجعة (نفس الملف في المشروع):**

```sql
ALTER TABLE `refractive_profiles`
  ADD COLUMN `dry_auto_ref_od_sphere`   VARCHAR(255) NULL DEFAULT NULL AFTER `current_eyeglasses_os_vision`,
  ADD COLUMN `dry_auto_ref_od_cylinder` VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_od_sphere`,
  ADD COLUMN `dry_auto_ref_od_axis`    VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_od_cylinder`,
  ADD COLUMN `dry_auto_ref_os_sphere`  VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_od_axis`,
  ADD COLUMN `dry_auto_ref_os_cylinder` VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_os_sphere`,
  ADD COLUMN `dry_auto_ref_os_axis`    VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_os_cylinder`;
```

---

## 3) تحديث الأدوار والصلاحيات (Spatie + medical_report)

يُنشئ صلاحية التقرير الطبي ويربطها بالأدوار. لا يحذف مرضى ولا مواعيد ولا فواتير.

```bash
cd /home/sarfesak/public_html/almyzan
php artisan db:seed --class=PermissionSeeder --force
```

---

## 4) مزامنة أدوار المستخدمين مع عمود role

يقرأ عمود `role` من جدول المستخدمين ويربط كل مستخدم بدور Spatie المناسب (admin / doctor / secretary). لا يمسّ الجداول الأخرى.

```bash
cd /home/sarfesak/public_html/almyzan
php artisan db:seed --class=AssignAdminRolesToExistingUsers --force
```

---

## ملخص التأثير على البيانات

| الخطوة | التأثير | خطر على بيانات العميل |
|--------|---------|-------------------------|
| النسخ الاحتياطي | نسخ فقط | لا |
| Dry Auto-Ref SQL | إضافة أعمدة فقط | لا |
| PermissionSeeder | إضافة/تحديث صلاحيات وأدوار | لا |
| AssignAdminRolesToExistingUsers | ربط المستخدمين بأدوار Spatie | لا |

لا يوجد في هذه الخطوات أي حذف لجدول أو truncate أو حذف سجلات مرضى/مواعيد/فواتير.

---
*آخر تحديث: 2026-02-08*
