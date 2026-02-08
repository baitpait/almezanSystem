# تعليمات رفع التعديلات إلى GitHub

## ما تم تنفيذه

1. **توثيق التعديلات:** تم إنشاء الملف `docs/CHANGELOG_2026-02-08.md` ويحتوي على توثيق احترافي لجميع التعديلات (التقرير الطبي، المرضى والمواعيد، الفواتير، الصلاحيات، R/G، إلخ).

2. **Git Commit:** تم تنفيذ commit واحد يشمل كل الملفات المعدّلة والجديدة:
   - **25 ملفاً** (تعديل + إضافة)
   - الرسالة: `feat: Medical report, patient filter, role-based visibility, invoices, R/G dropdown`

## ما يلزمك تنفيذه يدوياً

الرفع إلى GitHub لم يتم من هذه البيئة بسبب عدم توفّر تفويضات الدخول. نفّذ الأمر التالي من جهازك (من داخل مجلد المشروع):

```bash
cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"
git push origin main
```

- إذا طلب منك Git اسم مستخدم وكلمة مرور: استخدم **اسم المستخدم GitHub** وكلمة المرور = **Personal Access Token** (وليس كلمة مرور الحساب).
- لتفعيل الرفع بدون طلب تفاعلي لاحقاً: استخدم SSH بدل HTTPS، أو خزّن التوكن (مثلاً `git config credential.helper store` ثم نفّذ push مرة واحدة وأدخل التوكن عند الطلب).

## التحقق بعد الرفع

- افتح المستودع على GitHub: `https://github.com/baiitpait/almezanSystem`
- تأكد من ظهور الـ commit الأخير على الفرع `main`.
- راجع الملف `docs/CHANGELOG_2026-02-08.md` على GitHub للتأكد من وجود التوثيق.

---
*8 فبراير 2026*
