# خطة اختبار النظام - Testing Plan
## Medical Management System - Ophthalmic Surgery

---

## 📋 نظرة عامة - Overview

هذه خطة اختبار شاملة لنظام إدارة العمليات الجراحية للعيون. يتم تنظيم الاختبارات حسب الأولوية والاعتماديات.

---

## 🎯 أهداف الاختبار - Testing Objectives

1. **التحقق من صحة الوظائف الأساسية** - Verify core functionalities
2. **التحقق من الأمان والصلاحيات** - Verify security and permissions
3. **التحقق من سلامة البيانات** - Verify data integrity
4. **التحقق من تجربة المستخدم** - Verify user experience
5. **التحقق من الأداء** - Verify performance

---

## 📊 هيكل الاختبارات - Test Structure

### 1. **Feature Tests** (اختبارات الوظائف)
- اختبارات شاملة للوظائف الكاملة
- اختبارات Livewire Components
- اختبارات Routes والـ Controllers

### 2. **Unit Tests** (اختبارات الوحدات)
- اختبارات Models
- اختبارات Helpers
- اختبارات Business Logic

### 3. **Integration Tests** (اختبارات التكامل)
- اختبارات التكامل بين المكونات
- اختبارات Database Relationships
- اختبارات Workflows الكاملة

---

## 🚀 خطة التنفيذ - Implementation Plan

### المرحلة 1: الأساسيات - Foundation (الأولوية العليا)

#### 1.1 Authentication Tests
**الملفات المطلوبة:**
- `tests/Feature/Auth/LoginTest.php`
- `tests/Feature/Auth/LogoutTest.php`
- `tests/Feature/Auth/MiddlewareTest.php`

**الاختبارات:**
- ✅ تسجيل الدخول بنجاح
- ✅ تسجيل الدخول ببيانات خاطئة
- ✅ تسجيل الخروج
- ✅ حماية Routes (يجب تسجيل الدخول)
- ✅ Redirect بعد تسجيل الدخول
- ✅ CSRF Protection

**الأولوية:** 🔴 عالية جداً

---

#### 1.2 Patient Management Tests
**الملفات المطلوبة:**
- `tests/Feature/Patient/PatientManagerTest.php`
- `tests/Unit/Models/PatientTest.php`

**الاختبارات:**
- ✅ إنشاء مريض جديد
- ✅ تحديث بيانات المريض
- ✅ حذف مريض
- ✅ البحث عن مريض
- ✅ عرض قائمة المرضى
- ✅ التحقق من صحة البيانات (Validation)
- ✅ التحقق من العلاقات (Relationships)

**الأولوية:** 🔴 عالية جداً

---

### المرحلة 2: الوظائف الأساسية - Core Features

#### 2.1 Appointment Management Tests
**الملفات المطلوبة:**
- `tests/Feature/Appointment/AppointmentManagerTest.php`
- `tests/Unit/Models/AppointmentTest.php`

**الاختبارات:**
- ✅ إنشاء موعد جديد
- ✅ تحديث موعد
- ✅ حذف موعد
- ✅ البحث والفلترة
- ✅ ربط موعد بعملية (Operation)
- ✅ تغيير Visit Type
- ✅ حماية البيانات عند تغيير Visit Type
- ✅ القيم الافتراضية للتاريخ والوقت

**الأولوية:** 🟠 عالية

---

#### 2.2 Operation Management Tests
**الملفات المطلوبة:**
- `tests/Feature/Operation/OperationManagerTest.php`
- `tests/Unit/Models/OperationTest.php`
- `tests/Feature/Operation/OperationDataIntegrityTest.php`

**الاختبارات:**
- ✅ إنشاء عملية جديدة
- ✅ تحديث عملية
- ✅ حذف عملية (Soft Delete)
- ✅ حفظ البيانات الطبية (Refractive Profile, Medical History, etc.)
- ✅ التحقق من `hasData()` method
- ✅ التحقق من `isEmpty()` method
- ✅ حماية البيانات عند تغيير Visit Type
- ✅ ربط العملية بالموعد (Appointment)

**الأولوية:** 🟠 عالية

---

#### 2.3 Operation Notes Tests
**الملفات المطلوبة:**
- `tests/Feature/Operation/OperationNoteManagerTest.php`

**الاختبارات:**
- ✅ إنشاء ملاحظات عملية
- ✅ تحديث الملاحظات
- ✅ "Same operation type for both eyes" - عدم تكرار الملاحظات
- ✅ نسخ البيانات من OD إلى OS (فقط إذا كانت فارغة)
- ✅ حفظ الملاحظات العامة (notes)

**الأولوية:** 🟡 متوسطة

---

### المرحلة 3: الوظائف الإضافية - Additional Features

#### 3.1 Invoice Management Tests
**الملفات المطلوبة:**
- `tests/Feature/Invoice/InvoiceManagerTest.php`
- `tests/Unit/Models/InvoiceTest.php`

**الاختبارات:**
- ✅ إنشاء فاتورة
- ✅ تحديث فاتورة
- ✅ ربط فاتورة بعملية
- ✅ تحديث حالة الدفع
- ✅ البحث والفلترة

**الأولوية:** 🟡 متوسطة

---

#### 3.2 Scheduled Operations Tests
**الملفات المطلوبة:**
- `tests/Feature/Operation/ScheduledOperationsTest.php`

**الاختبارات:**
- ✅ عرض العمليات المجدولة
- ✅ البحث والفلترة
- ✅ فلترة حسب التاريخ (Upcoming, Today, Past, All)
- ✅ فلترة حسب الحالة
- ✅ Pagination

**الأولوية:** 🟡 متوسطة

---

#### 3.3 Dashboard Tests
**الملفات المطلوبة:**
- `tests/Feature/Dashboard/DashboardTest.php`

**الاختبارات:**
- ✅ عرض الإحصائيات
- ✅ عرض المواعيد القريبة
- ✅ فلترة حسب الفرع (Branch)
- ✅ تحديث البيانات في الوقت الفعلي

**الأولوية:** 🟢 منخفضة

---

### المرحلة 4: لوحة التحكم - Admin Panel

#### 4.1 User Management Tests
**الملفات المطلوبة:**
- `tests/Feature/Admin/UserManagerTest.php`
- `tests/Feature/Admin/AdminMiddlewareTest.php`

**الاختبارات:**
- ✅ إنشاء مستخدم جديد
- ✅ تحديث مستخدم
- ✅ حذف مستخدم
- ✅ تعيين الصلاحيات (Roles)
- ✅ حماية Routes (Admin Only)
- ✅ التحقق من Middleware

**الأولوية:** 🟡 متوسطة

---

#### 4.2 Branch Management Tests
**الملفات المطلوبة:**
- `tests/Feature/Admin/BranchManagerTest.php`

**الاختبارات:**
- ✅ إنشاء فرع جديد
- ✅ تحديث فرع
- ✅ حذف فرع
- ✅ ربط المستخدمين بالفروع

**الأولوية:** 🟢 منخفضة

---

### المرحلة 5: الأمان والصلاحيات - Security & Permissions

#### 5.1 Role-Based Access Control Tests
**الملفات المطلوبة:**
- `tests/Feature/Security/RoleBasedAccessTest.php`

**الاختبارات:**
- ✅ Admin: الوصول الكامل
- ✅ Doctor: الوصول للبيانات الطبية
- ✅ Optometrist: الوصول المحدود
- ✅ Secretary: الوصول للمواعيد فقط
- ✅ التحقق من Middleware لكل Role

**الأولوية:** 🔴 عالية جداً

---

#### 5.2 Data Protection Tests
**الملفات المطلوبة:**
- `tests/Feature/Security/DataProtectionTest.php`

**الاختبارات:**
- ✅ حماية من فقدان البيانات
- ✅ التحقق من Visit Type Safety
- ✅ التحقق من Operation Data Integrity
- ✅ التحقق من Soft Deletes

**الأولوية:** 🔴 عالية جداً

---

## 📝 قائمة المهام - Task List

### المرحلة 1: الأساسيات
- [ ] إنشاء Authentication Tests
- [ ] إنشاء Patient Management Tests
- [ ] إنشاء Unit Tests للـ Models الأساسية

### المرحلة 2: الوظائف الأساسية
- [ ] إنشاء Appointment Management Tests
- [ ] إنشاء Operation Management Tests
- [ ] إنشاء Operation Notes Tests
- [ ] إنشاء Data Integrity Tests

### المرحلة 3: الوظائف الإضافية
- [ ] إنشاء Invoice Management Tests
- [ ] إنشاء Scheduled Operations Tests
- [ ] إنشاء Dashboard Tests

### المرحلة 4: لوحة التحكم
- [ ] إنشاء User Management Tests
- [ ] إنشاء Branch Management Tests
- [ ] إنشاء Admin Middleware Tests

### المرحلة 5: الأمان
- [ ] إنشاء Role-Based Access Tests
- [ ] إنشاء Data Protection Tests
- [ ] إنشاء Security Tests

---

## 🛠️ الأدوات المطلوبة - Required Tools

1. **PHPUnit** - موجود بالفعل في المشروع
2. **Laravel Testing Helpers** - موجودة بالفعل
3. **Database Testing** - استخدام SQLite في الذاكرة للاختبارات
4. **Livewire Testing** - استخدام `Livewire::test()`

---

## 📦 هيكل الملفات - File Structure

```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php
│   │   ├── LogoutTest.php
│   │   └── MiddlewareTest.php
│   ├── Patient/
│   │   └── PatientManagerTest.php
│   ├── Appointment/
│   │   └── AppointmentManagerTest.php
│   ├── Operation/
│   │   ├── OperationManagerTest.php
│   │   ├── OperationNoteManagerTest.php
│   │   ├── ScheduledOperationsTest.php
│   │   └── OperationDataIntegrityTest.php
│   ├── Invoice/
│   │   └── InvoiceManagerTest.php
│   ├── Dashboard/
│   │   └── DashboardTest.php
│   ├── Admin/
│   │   ├── UserManagerTest.php
│   │   └── BranchManagerTest.php
│   └── Security/
│       ├── RoleBasedAccessTest.php
│       └── DataProtectionTest.php
├── Unit/
│   ├── Models/
│   │   ├── PatientTest.php
│   │   ├── AppointmentTest.php
│   │   ├── OperationTest.php
│   │   ├── InvoiceTest.php
│   │   └── UserTest.php
│   └── Helpers/
│       └── (if needed)
└── TestCase.php
```

---

## 🎯 الأولويات - Priorities

### 🔴 عالية جداً (يجب البدء بها)
1. Authentication Tests
2. Patient Management Tests
3. Role-Based Access Tests
4. Data Protection Tests

### 🟠 عالية
5. Appointment Management Tests
6. Operation Management Tests

### 🟡 متوسطة
7. Operation Notes Tests
8. Invoice Management Tests
9. Scheduled Operations Tests
10. User Management Tests

### 🟢 منخفضة
11. Dashboard Tests
12. Branch Management Tests

---

## 📊 معايير النجاح - Success Criteria

### Coverage Goals (أهداف التغطية)
- **Minimum:** 60% code coverage
- **Target:** 80% code coverage
- **Ideal:** 90%+ code coverage

### Critical Paths (المسارات الحرجة)
- ✅ جميع مسارات Authentication
- ✅ جميع مسارات Patient Management
- ✅ جميع مسارات Operation Management
- ✅ جميع مسارات Security & Permissions

---

## 🚦 خطة التنفيذ - Execution Plan

### الأسبوع 1: الأساسيات
- يوم 1-2: Authentication Tests
- يوم 3-4: Patient Management Tests
- يوم 5: Unit Tests للـ Models

### الأسبوع 2: الوظائف الأساسية
- يوم 1-2: Appointment Management Tests
- يوم 3-4: Operation Management Tests
- يوم 5: Operation Notes Tests

### الأسبوع 3: الوظائف الإضافية والأمان
- يوم 1-2: Invoice & Scheduled Operations Tests
- يوم 3-4: Security & Permissions Tests
- يوم 5: Admin Panel Tests

---

## 📝 ملاحظات مهمة - Important Notes

1. **استخدام SQLite في الذاكرة** للاختبارات السريعة
2. **استخدام Factories** لإنشاء بيانات الاختبار
3. **استخدام Database Transactions** لتنظيف البيانات بعد كل اختبار
4. **اختبار Livewire Components** باستخدام `Livewire::test()`
5. **اختبار Validation Rules** بشكل منفصل
6. **اختبار Relationships** بين Models

---

## 🔄 الصيانة المستمرة - Continuous Maintenance

- تشغيل الاختبارات قبل كل commit
- تحديث الاختبارات عند إضافة ميزات جديدة
- مراجعة Coverage Reports بانتظام
- إصلاح الاختبارات الفاشلة فوراً

---

**تاريخ الإنشاء:** 2025-01-XX  
**آخر تحديث:** 2025-01-XX  
**الحالة:** 📋 جاهز للتنفيذ

