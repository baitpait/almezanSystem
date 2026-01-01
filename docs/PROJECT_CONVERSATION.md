# توثيق المشروع - Project Documentation

## شروط المشروع الإلزامية - Mandatory Project Rules

### ⚠️ شرط إلزامي بعد كل تعديل - Mandatory Step After Every Change

**بعد أي تعديل في الكود، يجب تنفيذ الأوامر التالية تلقائياً:**

```bash
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && npm run build
```

**السبب:**
- مسح جميع أنواع الكاش (Application, Config, Route, View)
- إعادة بناء الأصول (CSS و JS) لتطبيق التغييرات
- ضمان ظهور التعديلات فوراً في المتصفح

**ملاحظة:** هذا الشرط يجب تطبيقه بعد **كل تعديل** في:
- ملفات Blade (Views)
- ملفات CSS/JS
- أي تغيير يتطلب إعادة بناء الأصول

---

## تاريخ المشروع - Project History

### المرحلة الأولى - Initial Setup
- تم إنشاء نظام إدارة طبي لمركز الغد لجراحة العيون والليزك
- تم إضافة شعار PNG بدلاً من SVG
- تم تطبيق خط Cairo العربي على كامل المشروع
- تم تصميم صفحة تسجيل الدخول مع الشعار والهوية البصرية
- تم إزالة Google Login و Sign up
- تم إضافة رابط المطور بالعربية

### المرحلة الثانية - Visual Identity
- تم تحديث الألوان لتتناسب مع الهوية الطبية (أبيض وأزرق)
- تم تصميم لوحة التحكم Dashboard
- تم إضافة حقل الصورة الشخصية للأطباء
- تم إنشاء جدول الفواتير (Invoices) وربطه بالمواعيد
- تم حل مشكلة CSRF Token (419 PAGE EXPIRED)
- تم إضافة JavaScript لإعادة تحميل الصفحة عند خطأ 419

### المرحلة الثالثة - Operations System
- تم إنشاء نظام شامل لإدارة العمليات الجراحية
- تم ربط العمليات بالمواعيد (Appointments) والفواتير (Invoices)
- تم إنشاء جداول متخصصة للبيانات الطبية:
  - `operations` - العمليات الجراحية
  - `refractive_profiles` - الملفات الانكسارية
  - `medical_histories` - التاريخ الطبي
  - `eye_examinations` - فحوصات العين
  - `ectasia_risk_assessments` - تقييم مخاطر التوسع
  - `operation_details` - تفاصيل العملية
  - `operation_approvals` - موافقات العمليات

### المرحلة الرابعة - Pre-op Forms Integration
- تم إضافة حقول من ملف "Pre op final" PDF:
  - Target Parameters (معاملات الهدف) في `operation_details`:
    - Nomogram, Pach (Pachymetry), Kaapa, Vertex, WTW, LASIK/PRK, PTA%, Pupil Size
    - Target Add
  - Surgeon Correction في `refractive_profiles`:
    - Sphere, Cylinder, Axis, BSCVA لكل عين
  - Cycloplegic Refraction في `refractive_profiles`:
    - Sphere, Cylinder, Axis, BSCVA لكل عين
- تم إنشاء واجهة Tabbed Interface للبيانات:
  - Basic Info
  - Refractive
  - Target Parameters (جديد)
  - Medical History
  - Eye Exam
  - Ectasia Risk
  - Recommendation

### المرحلة الخامسة - UI/UX Improvements
- تم تحويل Modal إلى صفحة منفصلة لإضافة/تعديل العمليات
- تم تحسين تصميم التابات بشكل احترافي:
  - Gradient background (أزرق إلى سماوي)
  - أيقونات لكل تاب
  - Active tab بخلفية بيضاء
  - Hover effects
- تم إنشاء صفحة منفصلة: `/operations/create` و `/operations/{id}/edit`
- تم تحسين تصميم Target Parameters Tab:
  - بطاقات منفصلة لكل عين (OD/OS)
  - Header gradient لكل بطاقة
  - حقول منظمة مع شروحات
  - Footer notes مهمة

### معلومات تسجيل الدخول - Login Credentials
- **Email**: `admin@example.com`
- **Password**: `password`

### البنية التحتية - Infrastructure
- **Framework**: Laravel 11 with Livewire
- **Frontend**: Tailwind CSS + DaisyUI
- **Database**: MySQL/MariaDB (database: `dralmyzin`)
- **Font**: Cairo (Google Fonts)
- **Server**: `php artisan serve` (http://127.0.0.1:8000)

### الميزات الرئيسية - Main Features
1. إدارة الأطباء مع الصور الشخصية
2. إدارة المواعيد والزيارات
3. إدارة الفواتير والحسابات
4. إدارة العمليات الجراحية الكاملة مع:
   - Pre-operative assessment
   - Target parameters planning
   - Refractive profiles
   - Medical history
   - Eye examinations
   - Ectasia risk assessment
   - Recommendations
5. نظام الموافقات للعمليات
6. تخزين البيانات الطبية في جداول منفصلة (ليس JSON)
7. Default values للبيانات الطبية لتسريع الإدخال

### القرارات التقنية - Technical Decisions
- جميع البيانات الطبية محفوظة في قاعدة البيانات (ليس JSON)
- الزيارات مرتبطة بجدول `appointments` الحالي
- العمليات مرتبطة مباشرة بالفواتير
- نظام موافقات قبل إجراء العمليات
- تم حذف `operation_files` table بناءً على طلب المستخدم
- تم إضافة Target Parameters من ملف "Pre op final" PDF
- تم إنشاء صفحة منفصلة بدلاً من Modal للعمليات

---

## Project Conversation Log

### Initial Requirements
- Replace default logo with PNG logo
- Translate specific text elements to Arabic
- Keep system language in English except for specified Arabic phrases
- Apply Cairo Arabic font across entire project
- Remove Google login and sign-up options
- Add Arabic developer link

### Design Iterations

#### 1. Login Page Redesign
- Multiple iterations to match provided images
- Two-column layout with logo on right side
- Single-column simplified layout
- Professional medical design with white and blue color scheme
- Gradient background: `bg-gradient-to-br from-blue-50 via-white to-blue-50`
- Fixed CSRF token issues with page reload mechanism

#### 2. Dashboard Redesign
- New statistics cards
- Appointment tables
- Invoice management integration
- Branch filtering

#### 3. Operations Management UI
- Initial Modal-based interface
- Converted to separate page (`/operations/create`, `/operations/{id}/edit`)
- Professional tabbed interface with:
  - Gradient header (blue to cyan)
  - Icons for each tab
  - Active tab highlighting
  - Smooth transitions

### Database Schema Evolution

#### Phase 1: Basic Tables
- `users`, `branches`, `doctors`, `patients`, `appointments`

#### Phase 2: Invoice Integration
- `invoices` table linked to appointments
- Added `visit_stage` to `appointments` table

#### Phase 3: Operations System
- `operations` - Main operations table
- `refractive_profiles` - Refractive data
- `medical_histories` - Medical history
- `eye_examinations` - Eye examination data
- `ectasia_risk_assessments` - Risk assessment
- `operation_details` - Operation details
- `operation_approvals` - Approval workflow

#### Phase 4: Pre-op Integration
- Added Target Parameters to `operation_details`:
  - `target_nomogram_od/os`
  - `target_pach_od/os` (Pachymetry)
  - `target_kaapa_od/os`
  - `target_vertex_od/os`
  - `target_wtw_od/os` (White to White)
  - `target_procedure_od/os` (LASIK/PRK)
  - `target_pta_od/os` (Percent Tissue Altered)
  - `target_pupil_size_od/os`
  - `target_add`
- Added Surgeon Correction to `refractive_profiles`:
  - `surgeon_correction_od_sphere/cylinder/axis/bscva`
  - `surgeon_correction_os_sphere/cylinder/axis/bscva`
- Added Cycloplegic Refraction to `refractive_profiles`:
  - `cycloplegic_refraction_od_sphere/cylinder/axis/bscva`
  - `cycloplegic_refraction_os_sphere/cylinder/axis/bscva`

#### Schema Changes
- Removed `operation_files` table (user request)
- Added missing fields to various tables based on PDF requirements
- Added `dominant_eye`, `simulation_for_monovision` to `refractive_profiles`
- Added `od_iop`, `os_iop` to `eye_examinations`
- Added `tomography_status` to `ectasia_risk_assessments`
- Added `topographic_guided_profile`, `mv_eye`, `target`, `comments`, `patient_teaching_details`, `increased_risk_for` to `operations`

### Key User Decisions
- **Visits**: Integrated into `appointments` table, not separate table
- **Medical Data Storage**: All data in database tables, not JSON
- **Operation-Invoice Link**: Direct relationship between operations and invoices
- **Approval System**: Required before operations
- **File Uploads**: Initially planned, then removed for operations (user request)
- **Pre-op Forms**: Based on "Pre op final" and "refractive patient file" PDFs
- **Data Entry Speed**: Target 10 seconds per file with default values
- **UI Design**: Separate page instead of Modal for better UX

### Files Created/Modified

#### Migrations
1. `2025_12_08_132848_add_photo_to_doctors_table.php`
2. `2025_12_08_132850_create_invoices_table.php`
3. `2025_12_08_135318_add_visit_stage_to_appointments_table.php`
4. `2025_12_08_155010_create_operations_table.php`
5. `2025_12_08_155813_create_refractive_profiles_table.php`
6. `2025_12_08_155814_create_medical_histories_table.php`
7. `2025_12_08_155815_create_eye_examinations_table.php`
8. `2025_12_08_155816_create_ectasia_risk_assessments_table.php`
9. `2025_12_08_155817_create_operation_details_table.php`
10. `2025_12_08_155818_create_operation_approvals_table.php`
11. `2025_12_08_155820_add_operation_id_to_appointments_table.php`
12. `2025_12_08_155821_add_operation_id_to_invoices_table.php`
13. `2025_12_10_175913_drop_operation_files_table.php`
14. `2025_12_10_180434_add_missing_fields_to_refractive_profiles_table.php`
15. `2025_12_10_180436_add_missing_fields_to_eye_examinations_table.php`
16. `2025_12_10_180438_add_missing_fields_to_ectasia_risk_assessments_table.php`
17. `2025_12_10_180440_add_missing_fields_to_operations_table.php`
18. `2025_12_10_182629_add_pre_op_target_fields_to_operation_details_table.php`
19. `2025_12_10_182631_add_surgeon_correction_fields_to_refractive_profiles_table.php`

#### Models
- `Operation.php` - Main operation model with soft deletes
- `RefractiveProfile.php` - Refractive profile data
- `MedicalHistory.php` - Medical history
- `EyeExamination.php` - Eye examination data
- `EctasiaRiskAssessment.php` - Risk assessment
- `OperationDetail.php` - Operation details with target parameters
- `OperationApproval.php` - Approval workflow
- `Invoice.php` - Invoice management
- `Doctor.php` - Updated with photo field

#### Livewire Components
- `Auth/Login.php` - Authentication with Arabic error messages
- `Dashboard.php` - Dashboard with statistics
- `AppointmentManager.php` - Appointment management
- `InvoiceManager.php` - Invoice management
- `OperationManager.php` - Comprehensive operation management with:
  - Tabbed interface (7 tabs)
  - Patient search
  - Form validation
  - Default values for quick entry
  - Separate create/edit pages

#### Views
- `livewire/auth/login.blade.php` - Login page with logo
- `livewire/dashboard.blade.php` - Dashboard with statistics
- `livewire/appointment-manager.blade.php` - Appointment management
- `livewire/invoice-manager.blade.php` - Invoice management
- `livewire/operation-manager.blade.php` - Operation list and modal
- `livewire/operation-manager/form.blade.php` - Separate create/edit page
- `livewire/operation-manager/tabs/basic.blade.php` - Basic info tab
- `livewire/operation-manager/tabs/refractive.blade.php` - Refractive profile tab
- `livewire/operation-manager/tabs/target.blade.php` - Target parameters tab (professional design)
- `livewire/operation-manager/tabs/medical.blade.php` - Medical history tab
- `livewire/operation-manager/tabs/exam.blade.php` - Eye examination tab
- `livewire/operation-manager/tabs/ectasia.blade.php` - Ectasia risk tab
- `livewire/operation-manager/tabs/recommendation.blade.php` - Recommendation tab

#### Routes
- `/operations` - Operations list
- `/operations/create` - Create new operation (separate page)
- `/operations/{id}/edit` - Edit operation (separate page)

### Default Values for Quick Entry

#### Eye Examination Defaults
- IOP: 16 mmHg (normal range: 10-21)
- TBUT: 12 seconds (normal: >10)
- Schirmer: 15 mm (normal: >10)
- Lids, Conjunctiva, Cornea: "Normal"
- Anterior Chamber: "Deep and quiet"
- Iris/Pupil, Lens, Vitreous: "Normal"
- Optic Disc, Retina, Macula: "Normal"

#### Ectasia Risk Assessment Defaults
- Pachymetry: 550 μm (normal: 540-560)
- Tomography Status: "normal"
- Tomography Normal Pattern: true

### Issues Fixed

1. **CSRF Token Error (419 PAGE EXPIRED)**
   - Added `data-csrf="{{ csrf_token() }}"` to HTML tag
   - Added JavaScript to reload page on 419 error
   - Cleared all caches

2. **Database Connection**
   - Created `create_database.sql` for database setup
   - Database name: `dralmyzin`

3. **Server Issues**
   - Created `start-server.bat` for easy server startup
   - Fixed cache clearing issues

4. **Syntax Errors**
   - Fixed duplicate `@endif` in operation-manager.blade.php
   - Fixed migration duplicate column errors

5. **Property Not Found Errors**
   - Added `$targetForm` property to OperationManager
   - Added `$isCreatePage` and `$isEditPage` properties

### Current System Status

#### Database Tables
- `users` - System users
- `branches` - Clinic branches
- `doctors` - Doctors with photos
- `patients` - Patients
- `appointments` - Appointments/Visits with visit_stage
- `invoices` - Invoices linked to appointments and operations
- `operations` - Surgical operations (with soft deletes)
- `refractive_profiles` - Refractive profiles (with surgeon correction and cycloplegic)
- `medical_histories` - Medical histories
- `eye_examinations` - Eye examinations (pre-op/post-op)
- `ectasia_risk_assessments` - Ectasia risk assessments
- `operation_details` - Operation details (with target parameters)
- `operation_approvals` - Operation approvals

#### Models Created
- User, Branch, Doctor, Patient, Appointment, Invoice
- Operation, RefractiveProfile, MedicalHistory, EyeExamination
- EctasiaRiskAssessment, OperationDetail, OperationApproval

#### Livewire Components
- Auth/Login
- Dashboard
- AppointmentManager
- InvoiceManager
- OperationManager (with 7 tabs and separate create/edit pages)

### Design Features

#### Color Scheme
- Primary: Blue (#2563eb to #1d4ed8)
- Secondary: Cyan (#0891b2 to #0e7490)
- Background: Gradient from blue-50 to cyan-50
- Medical white and blue palette

#### Typography
- Font: Cairo (Google Fonts)
- Applied across entire project
- Supports Arabic text

#### UI Components
- Professional tabbed interface
- Gradient headers
- Card-based layouts
- Responsive design
- Smooth transitions
- Icon integration

### Next Steps / Future Enhancements
- [ ] Post-operative follow-up tracking
- [ ] Print-friendly operation reports
- [ ] Advanced search and filtering
- [ ] Operation statistics and analytics
- [ ] Integration with medical devices
- [ ] Multi-language support (full Arabic)
- [ ] Mobile app support

---

## Technical Notes

### OperationManager Component Structure
- **Properties**: 
  - Form arrays for each section (operationForm, refractiveForm, targetForm, medicalForm, examForm, ectasiaForm, recommendationForm)
  - Search and filter properties
  - Tab management
  - Page state (isCreatePage, isEditPage)

- **Methods**:
  - `mount()` - Initialize component based on route
  - `create()` - Initialize new operation
  - `edit($id)` - Load operation for editing
  - `save()` - Save operation and related data
  - `delete($id)` - Delete operation
  - `setTab($tab)` - Switch between tabs
  - `selectPatient($id)` - Select patient from search
  - `resetAllForms()` - Reset all form data

### Target Parameters Tab Design
- Professional medical system design
- Two-column layout (OD/OS)
- Gradient card headers
- Organized fields with descriptions
- Default values for quick entry
- Important notes footer

### Routes Structure
```php
Route::prefix('operations')->name('operations.')->group(function () {
    Route::get('/', OperationManager::class)->name('index');
    Route::get('/create', OperationManager::class)->name('create');
    Route::get('/{id}/edit', OperationManager::class)->name('edit');
});
```

---

---

## Complete Project Structure

### Database Schema - Complete Overview

#### Core Tables

**1. users**
- `id`, `name`, `email`, `password`, `role`, `branch_id`, `remember_token`, `email_verified_at`, `created_at`, `updated_at`
- Roles: `admin`, `doctor`, `optometrist`, `secretary`

**2. branches**
- `id`, `name`, `address`, `phone`, `email`, `created_at`, `updated_at`

**3. doctors**
- `id`, `user_id`, `branch_id`, `name`, `specialization`, `phone`, `email`, `photo`, `created_at`, `updated_at`

**4. patients**
- `id`, `name`, `id_number`, `phone`, `email`, `date_of_birth`, `gender`, `address`, `created_at`, `updated_at`

**5. appointments**
- `id`, `patient_id`, `doctor_id`, `branch_id`, `created_by`, `appointment_date`, `appointment_time`, `status`, `visit_stage`, `operation_id`, `notes`, `created_at`, `updated_at`
- Visit stages: `scheduled`, `checked_in`, `in_progress`, `completed`, `cancelled`

**6. invoices**
- `id`, `appointment_id`, `operation_id`, `patient_id`, `amount`, `status`, `invoice_date`, `due_date`, `paid_date`, `notes`, `created_at`, `updated_at`

#### Operations System Tables

**7. operations**
- `id`, `patient_id`, `doctor_id`, `branch_id`, `created_by`
- `operation_type` (enum: LASIK, Femto-LASIK, PRK, Trans-PRK, SMILE, PTK, Topography Guided, Presbyopia, Other)
- `operation_eye` (enum: OD, OS, OU)
- `cost`, `start_date`, `end_date`, `status` (enum: scheduled, in_progress, completed, cancelled, postponed)
- `pre_op_assessment_date`, `post_op_notes`
- `dry_eye_treatment`, `dry_eye_follow_up`, `corneal_warpage`, `corneal_warpage_follow_up`
- `presbyopia_profile`, `recommendation_notes`
- `topographic_guided_profile`, `mv_eye`, `target`, `comments`
- `patient_teaching_completed`, `patient_teaching_details`, `increased_risk_for`
- `diagnosis`, `plan`, `notes`
- `created_at`, `updated_at`, `deleted_at` (soft deletes)

**8. refractive_profiles**
- `id`, `operation_id`
- `optometrist`, `eyeglasses_age`, `time_with_current_rx`, `contact_lenses`, `time_without_lenses`
- `dominant_eye`, `simulation_for_monovision`
- Current Eyeglasses (OD/OS): `sphere`, `cylinder`, `axis`, `vision`
- Manifest Refraction (OD/OS): `udva`, `sphere`, `cylinder`, `axis`, `bscva`, `rg`, `dcnva_40cm`, `add_j1`
- Refraction After Dilatation (OD/OS): `sphere`, `cylinder`, `axis`, `vision`
- Surgeon Correction (OD/OS): `sphere`, `cylinder`, `axis`, `bscva`
- Cycloplegic Refraction (OD/OS): `sphere`, `cylinder`, `axis`, `bscva`
- `created_at`, `updated_at`

**9. medical_histories**
- `id`, `operation_id`
- `past_medical_history`, `current_medications`, `allergies`, `family_history`
- `vision_stability`, `vision_stability_duration`, `dry_eye_symptoms`, `dry_eye_severity`
- `corneal_conditions`, `previous_eye_surgery`, `previous_eye_surgery_details`
- `systemic_diseases`, `pregnancy_status`, `pregnancy_details`
- `created_at`, `updated_at`

**10. eye_examinations**
- `id`, `operation_id`, `examination_type` (enum: pre_op, post_op)
- `examination_date`
- OD/OS fields: `iop`, `tbut`, `schirmer`, `lids`, `conjunctiva`, `cornea`
- `anterior_chamber`, `iris_pupil`, `lens`, `vitreous`
- `optic_disc`, `retina`, `macula`
- `additional_notes`, `created_at`, `updated_at`

**11. ectasia_risk_assessments**
- `id`, `operation_id`
- OD/OS fields: `pachymetry`, `keratometry_k1`, `keratometry_k2`, `keratometry_axis`
- `topography_pattern`, `topography_abnormalities`, `tomography_status`
- `tomography_normal_pattern`, `belin_ambrosio_display_score`
- `risk_level` (enum: low, moderate, high), `risk_factors`, `recommendations`
- `created_at`, `updated_at`

**12. operation_details**
- `id`, `operation_id`
- Target Parameters (OD/OS): `target_nomogram`, `target_pach`, `target_kaapa`, `target_vertex`
- `target_wtw`, `target_procedure`, `target_pta`, `target_pupil_size`
- `target_add`
- `optical_zone`, `ablation_zone`, `flap_thickness`, `flap_diameter`
- `residual_bed_thickness`, `nomogram_adjustment`, `laser_settings`
- `created_at`, `updated_at`

**13. operation_approvals**
- `id`, `operation_id`, `approved_by`, `approval_date`, `approval_status` (enum: pending, approved, rejected)
- `approval_notes`, `created_at`, `updated_at`

### Complete File Structure

#### Models (app/Models/)
- `User.php` - User authentication and authorization
- `Branch.php` - Clinic branches
- `Doctor.php` - Doctors with photos and relationships
- `Patient.php` - Patient information
- `Appointment.php` - Appointments/visits management
- `Invoice.php` - Invoice management
- `Operation.php` - Main operation model with all relationships
- `RefractiveProfile.php` - Refractive data model
- `MedicalHistory.php` - Medical history model
- `EyeExamination.php` - Eye examination model
- `EctasiaRiskAssessment.php` - Risk assessment model
- `OperationDetail.php` - Operation details with target parameters
- `OperationApproval.php` - Approval workflow model
- `Category.php` - Categories
- `Procedure.php` - Procedures

#### Livewire Components (app/Livewire/)

**Authentication:**
- `Auth/Login.php` - Login component with Arabic error messages

**Main Components:**
- `Dashboard.php` - Main dashboard with statistics
- `PatientManager.php` - Patient management
- `AppointmentManager.php` - Appointment/visit management
- `InvoiceManager.php` - Invoice management
- `OperationManager.php` - Comprehensive operation management

**Admin Components:**
- `Admin/UserManager.php` - User management (admin only)
- `Admin/BranchManager.php` - Branch management (admin only)

#### Views (resources/views/livewire/)

**Main Views:**
- `auth/login.blade.php` - Login page with logo and gradient design
- `dashboard.blade.php` - Dashboard with statistics cards
- `patient-manager.blade.php` - Patient list and management
- `appointment-manager.blade.php` - Appointment list and management
- `invoice-manager.blade.php` - Invoice list and management

**Operation Views:**
- `operation-manager.blade.php` - Operation list page
- `operation-manager/form.blade.php` - Create/edit operation page with tabs
- `operation-manager/tabs/basic.blade.php` - Basic information tab
- `operation-manager/tabs/refractive.blade.php` - Refractive profile tab
- `operation-manager/tabs/target.blade.php` - Target parameters tab (professional design)
- `operation-manager/tabs/medical.blade.php` - Medical history tab
- `operation-manager/tabs/exam.blade.php` - Eye examination tab
- `operation-manager/tabs/ectasia.blade.php` - Ectasia risk assessment tab
- `operation-manager/tabs/recommendation.blade.php` - Recommendation tab

**Admin Views:**
- `admin/user-manager.blade.php` - User management interface
- `admin/branch-manager.blade.php` - Branch management interface

### Routes Structure (Complete)

```php
// Authentication
Route::get('/login', Login::class)->name('login');
Route::post('/logout', ...)->name('logout');

// Main Routes (Authenticated)
Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/dashboard', Dashboard::class)->name('dashboard');

// Patients
Route::prefix('patients')->name('patients.')->group(function () {
    Route::get('/', PatientManager::class)->name('index');
});

// Appointments
Route::prefix('appointments')->name('appointments.')->group(function () {
    Route::get('/', AppointmentManager::class)->name('index');
});

// Invoices
Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', InvoiceManager::class)->name('index');
});

// Operations
Route::prefix('operations')->name('operations.')->group(function () {
    Route::get('/', OperationManager::class)->name('index');
    Route::get('/create', OperationManager::class)->name('create');
    Route::get('/{id}/edit', OperationManager::class)->name('edit');
});

// Admin Routes (Admin Only)
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', UserManager::class)->name('index');
    });
    Route::prefix('branches')->name('branches.')->group(function () {
        Route::get('/', BranchManager::class)->name('index');
    });
});
```

### Complete Migration List (Chronological)

1. `0001_01_01_000000_create_users_table.php` - Laravel default users
2. `0001_01_01_000001_create_cache_table.php` - Cache table
3. `0001_01_01_000002_create_jobs_table.php` - Jobs queue table
4. `2025_12_08_085741_create_patients_table.php` - Patients table
5. `2025_12_08_104139_create_categories_table.php` - Categories
6. `2025_12_08_104145_create_doctors_table.php` - Doctors table
7. `2025_12_08_104149_create_procedures_table.php` - Procedures
8. `2025_12_08_104150_create_appointments_table.php` - Appointments
9. `2025_12_08_105608_add_id_number_to_patients_table.php` - Add ID number to patients
10. `2025_12_08_111647_add_role_and_branch_to_users_table.php` - Add role and branch to users
11. `2025_12_08_111650_create_branches_table.php` - Branches table
12. `2025_12_08_111654_add_user_id_and_branch_id_to_doctors_table.php` - Link doctors to users and branches
13. `2025_12_08_111656_add_created_by_and_branch_id_to_appointments_table.php` - Add creator and branch to appointments
14. `2025_12_08_132848_add_photo_to_doctors_table.php` - Add photo field to doctors
15. `2025_12_08_132850_create_invoices_table.php` - Invoices table
16. `2025_12_08_135318_add_visit_stage_to_appointments_table.php` - Add visit stage
17. `2025_12_08_155010_create_operations_table.php` - Main operations table
18. `2025_12_08_155813_create_refractive_profiles_table.php` - Refractive profiles
19. `2025_12_08_155814_create_medical_histories_table.php` - Medical histories
20. `2025_12_08_155815_create_eye_examinations_table.php` - Eye examinations
21. `2025_12_08_155816_create_ectasia_risk_assessments_table.php` - Ectasia risk assessments
22. `2025_12_08_155817_create_operation_details_table.php` - Operation details
23. `2025_12_08_155818_create_operation_approvals_table.php` - Operation approvals
24. `2025_12_08_155820_add_operation_id_to_appointments_table.php` - Link operations to appointments
25. `2025_12_08_155821_add_operation_id_to_invoices_table.php` - Link operations to invoices
26. `2025_12_10_175913_drop_operation_files_table.php` - Remove operation files table (user request)
27. `2025_12_10_180434_add_missing_fields_to_refractive_profiles_table.php` - Add missing refractive fields
28. `2025_12_10_180436_add_missing_fields_to_eye_examinations_table.php` - Add IOP fields
29. `2025_12_10_180438_add_missing_fields_to_ectasia_risk_assessments_table.php` - Add tomography status
30. `2025_12_10_180440_add_missing_fields_to_operations_table.php` - Add missing operation fields
31. `2025_12_10_182629_add_pre_op_target_fields_to_operation_details_table.php` - Add target parameters
32. `2025_12_10_182631_add_surgeon_correction_fields_to_refractive_profiles_table.php` - Add surgeon correction

### Technology Stack

**Backend:**
- PHP 8.2+
- Laravel 11.0
- Livewire 3.7
- MySQL/MariaDB

**Frontend:**
- Tailwind CSS
- DaisyUI
- Alpine.js (minimal use)
- Google Fonts (Cairo)

**Development Tools:**
- Laravel Pint (code formatting)
- PHPUnit (testing)
- Composer (dependency management)

### Key Features Implementation

#### 1. Authentication System
- Custom login page with Arabic support
- Role-based access control (RBAC)
- Admin middleware for protected routes
- CSRF protection with auto-reload on 419 errors

#### 2. Dashboard
- Statistics cards (patients, appointments, operations, invoices)
- Recent appointments table
- Branch filtering
- Real-time data updates

#### 3. Patient Management
- Patient registration
- Search and filter
- Patient profile view
- ID number tracking

#### 4. Appointment Management
- Appointment scheduling
- Visit stage tracking
- Operation linking
- Status management

#### 5. Invoice Management
- Invoice creation
- Payment tracking
- Operation linking
- Status management

#### 6. Operation Management (Comprehensive)
- **7-Tab Interface:**
  1. Basic Info - Operation details, patient, doctor, dates
  2. Refractive - Complete refractive profile (OD/OS)
  3. Target Parameters - Pre-op planning parameters
  4. Medical History - Patient medical history
  5. Eye Exam - Pre-op/post-op examinations
  6. Ectasia Risk - Risk assessment
  7. Recommendation - Doctor recommendations

- **Features:**
  - Patient search and selection
  - Default values for quick entry
  - Form validation
  - Separate create/edit pages
  - Professional UI design
  - Gradient headers
  - Icon integration

#### 7. Admin Panel
- User management
- Branch management
- Role assignment
- Access control

### Default Values System

The system includes default values for quick data entry:

**Eye Examination Defaults:**
- IOP: 16 mmHg (normal: 10-21)
- TBUT: 12 seconds (normal: >10)
- Schirmer: 15 mm (normal: >10)
- Lids, Conjunctiva, Cornea: "Normal"
- Anterior Chamber: "Deep and quiet"
- Iris/Pupil, Lens, Vitreous: "Normal"
- Optic Disc, Retina, Macula: "Normal"

**Ectasia Risk Assessment Defaults:**
- Pachymetry: 550 μm (normal: 540-560)
- Tomography Status: "normal"
- Tomography Normal Pattern: true

### Design System

**Color Palette:**
- Primary Blue: #2563eb to #1d4ed8
- Secondary Cyan: #0891b2 to #0e7490
- Background: Gradient from blue-50 to cyan-50
- Medical white and blue theme

**Typography:**
- Font Family: Cairo (Google Fonts)
- Applied globally across the project
- Full Arabic text support

**UI Components:**
- Professional tabbed interface
- Gradient card headers
- Card-based layouts
- Responsive design
- Smooth transitions
- Icon integration (Heroicons)
- DaisyUI components

### Workflow & Business Logic

#### Patient Journey
1. **Patient Registration** - Secretary creates patient record
2. **Appointment Scheduling** - Appointment created
3. **Check-in** - Appointment converted to visit
4. **Assessment** - Doctor/optometrist records data
5. **Operation Creation** - Operation case created
6. **Pre-op Assessment** - Complete pre-operative data
7. **Approval** - Operation approval workflow
8. **Surgery** - Operation performed
9. **Post-op Follow-up** - Post-operative tracking

#### Data Flow
- All medical data stored in database tables (not JSON)
- Relationships maintained via foreign keys
- Soft deletes for operations
- Audit trail via timestamps

### Security Features

- CSRF protection
- Authentication required for all routes
- Role-based access control
- Admin middleware
- Password hashing
- Session management
- Input validation

### Performance Optimizations

- Database indexes on foreign keys
- Database indexes on frequently queried fields
- Pagination for large datasets
- Efficient Eloquent relationships
- Query optimization

### Known Issues & Solutions

1. **CSRF Token Error (419)**
   - Solution: Added auto-reload JavaScript on 419 errors
   - Added CSRF token to HTML data attribute

2. **Database Connection**
   - Solution: Created database setup script
   - Database name: `dralmyzin`

3. **Migration Errors**
   - Solution: Fixed duplicate column errors
   - Proper migration ordering

4. **Property Not Found Errors**
   - Solution: Added all required properties to Livewire components
   - Proper initialization in mount() method

### Testing & Quality

- Code follows PSR-12 standards
- Type declarations (strict_types=1)
- Clean, readable code
- Descriptive variable names
- Proper error handling
- Form validation

### Deployment Notes

**Environment Setup:**
- PHP >= 8.2.12
- Composer dependencies
- Database: MySQL/MariaDB
- Web server: Apache/Nginx or `php artisan serve`

**Configuration:**
- `.env` file configuration
- Database connection settings
- Application key generation
- Cache clearing

**Server Commands:**
```bash
php artisan serve
php artisan migrate
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Future Enhancements (Planned)

- [ ] Post-operative follow-up tracking
- [ ] Print-friendly operation reports
- [ ] Advanced search and filtering
- [ ] Operation statistics and analytics
- [ ] Integration with medical devices
- [ ] Multi-language support (full Arabic)
- [ ] Mobile app support
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Document management
- [ ] Image upload and management
- [ ] Audit logs
- [ ] Backup and restore
- [ ] Export to PDF/Excel
- [ ] Calendar integration

---

---

## جلسة الإعداد على macOS - macOS Setup Session (2025-12-12)

### ما تم إنجازه:

1. **تثبيت Homebrew 5.0.5** - مدير الحزم لـ macOS
2. **تثبيت PHP 8.2.29** - مع ربطه في PATH
3. **تثبيت Composer 2.9.2** - مدير تبعيات PHP
4. **تثبيت MySQL 9.5.0** - قاعدة البيانات مع بدء الخدمة
5. **تثبيت Node.js v25.2.1 و npm 11.6.2** - لإدارة ملفات JavaScript/CSS
6. **إنشاء قاعدة البيانات** - `dralmyzin` مع UTF8MB4
7. **تثبيت تبعيات المشروع** - Composer و npm dependencies
8. **تشغيل Migrations** - جميع الجداول (32 migration)
9. **تشغيل Seeders** - البيانات الافتراضية (admin user, categories, doctors, procedures)
10. **إنشاء Application Key** - مفتاح التطبيق
11. **تشغيل المشروع** - يعمل على http://localhost:8000

### معلومات تسجيل الدخول:
- **Email:** `admin@example.com`
- **Password:** `password`

### ملفات التوثيق التي تم إنشاؤها:
1. `INSTALLATION_GUIDE_MAC.md` - دليل التثبيت الكامل
2. `QUICK_START_MAC.md` - دليل البدء السريع
3. `REQUIREMENTS.md` - قائمة البرمجيات المطلوبة
4. `HOW_TO_USE_TERMINAL.md` - كيفية استخدام Terminal
5. `INSTALL_HOMEBREW.md` - تثبيت Homebrew
6. `INSTALL_XAMPP_MAC.md` - تثبيت XAMPP (بديل)
7. `HOMEBREW_VS_XAMPP.md` - مقارنة بين Homebrew و XAMPP
8. `DATABASE_GUI_TOOLS.md` - أدوات واجهة رسومية لقاعدة البيانات
9. `BEST_PERFORMANCE_MAC.md` - أفضل طريقة لتشغيل Laravel
10. `SETUP_COMPLETE.md` - ملخص الإعداد الكامل

### القرارات المهمة:
- ✅ استخدام Homebrew بدلاً من XAMPP
- ✅ استخدام Laravel Serve للتطوير
- ✅ قاعدة البيانات: `dralmyzin` بدون كلمة مرور (للتطوير)
- ✅ جميع البرمجيات مثبتة وتعمل بشكل صحيح

### حالة المشروع:
- ✅ **جاهز للاستخدام**
- ✅ **يعمل على:** http://localhost:8000
- ✅ **قاعدة البيانات:** جاهزة ومليئة بالجداول
- ✅ **البيانات الافتراضية:** موجودة

---

## جلسة التطوير - Development Session (2025-12-14)

### التعديلات التي تمت:

#### 1. تحسينات CSS
- ✅ إضافة `box-shadow` لعناوين `h1.text-2xl` في جميع أنحاء النظام
- ✅ التعديل: `box-shadow: 0px 4px 12px 0px rgba(0, 0, 0, 0.15)`

#### 2. تحسينات صفحة إدارة المرضى (Patient Management)
- ✅ إضافة زر "Visit" في صفحة إدارة المرضى
- ✅ الزر ينقل المستخدم إلى صفحة إنشاء موعد جديد مع تحديد المريض تلقائياً
- ✅ إخفاء حقل البحث وزر إضافة مريض جديد عند فتح النافذة من صفحة المرضى

#### 3. تحسينات صفحة المواعيد (Appointment Management)
- ✅ إزالة زر "Visit" من صفحة المواعيد
- ✅ إزالة زر "Invoice" من صفحة المواعيد
- ✅ إضافة زر "Go" في عمود "Visit Type" عندما يكون النوع "Assessment"
- ✅ الزر ينقل المستخدم إلى صفحة Assessment/Operation مع تحديد المريض تلقائياً

#### 4. تعديلات قسم Refractive Profile
- ✅ تعديل تخطيط قسم Manifest Refraction:
  - UDVA في سطر منفصل
  - Sphere, Cylinder, Axis, BSCVA, R/G في سطر واحد أفقي (5 أعمدة)
  - DCNVA 40cm و Add J1 في سطر منفصل جنب بعض (عمودين)
- ✅ تعديل تخطيط قسم Refraction After Dilatation:
  - جميع الحقول (Sphere, Cylinder, Axis, Vision) في سطر واحد أفقي (4 أعمدة)
  - إضافة خيار اختيار نوع الدواء (Mydramide أو CYCLO) مع Radio Buttons
- ✅ حذف قسم Cycloplegic Refraction بالكامل (الكود وقاعدة البيانات)
- ✅ حذف قسم Surgeon Correction بالكامل (الكود وقاعدة البيانات)
- ✅ إضافة قسم جديد "Pupil Diameter":
  - لكل عين (OD/OS): Mesopic و Scotopic
  - الحقول في عمود واحد جنب بعض (grid-cols-2)

#### 5. قاعدة البيانات
- ✅ Migration: إزالة أعمدة Cycloplegic Refraction من `refractive_profiles`
- ✅ Migration: إزالة أعمدة Surgeon Correction من `refractive_profiles`
- ✅ Migration: إضافة حقل `refraction_after_dilation_type` (enum: Mydramide, CYCLO)
- ✅ Migration: إضافة حقول Pupil Diameter (mesopic, scotopic لكل عين)

#### 6. شروط المشروع الإلزامية
- ✅ إضافة شرط إلزامي: مسح الكاش وإعادة بناء الأصول بعد كل تعديل
- ✅ الشرط موثق في بداية ملف التوثيق
- ✅ يتم تطبيقه تلقائياً بعد كل تعديل

### حالة المشروع الحالية:
- ✅ جميع التعديلات تمت بنجاح
- ✅ قاعدة البيانات محدثة
- ✅ الكاش تم مسحه والأصول تم إعادة بنائها
- ✅ النظام جاهز للاستخدام

---

*Last Updated: 2025-12-14*
*Project: Medical Management System for Al-Ghad Eye Surgery Center*
*Developer: AI Assistant (Claude Sonnet 4.5)*
*Documentation Version: 2.1 - Complete Project Overview + macOS Setup*

---

## جلسة التطوير - Development Session (2025-01-XX)

### التعديلات التي تمت:

#### 1. القيم الافتراضية للتاريخ والوقت في Appointment
- ✅ إضافة القيم الافتراضية في `resetForm()`:
  - `appointment_date` = التاريخ الحالي (`now()->format('Y-m-d')`)
  - `appointment_time` = الوقت الحالي (`now()->format('H:i')`)
- ✅ إضافة القيم الافتراضية في `create()`:
  - تعيين التاريخ والوقت الحاليين عند إنشاء appointment جديد

**الملفات المحدثة:**
- `app/Livewire/AppointmentManager.php`

---

#### 2. تحليل شامل لسلوك Visit Type
- ✅ إنشاء ملف تحليل: `docs/VISIT_TYPE_ANALYSIS.md`
- ✅ تحليل سلوك Visit Type عند التغيير
- ✅ تحديد المشاكل المحتملة
- ✅ توثيق جميع السيناريوهات

**الملفات الجديدة:**
- `docs/VISIT_TYPE_ANALYSIS.md`

---

#### 3. حماية البيانات عند تغيير Visit Type
- ✅ إضافة دالة `hasData()` في `Operation` model للتحقق من وجود بيانات
- ✅ إضافة دالة `isEmpty()` في `Operation` model
- ✅ منطق الحماية في `AppointmentManager`:
  - لا يتم حذف Operation أبداً - فقط إلغاء الربط
  - التحقق من وجود البيانات قبل أي عملية
  - رسائل واضحة للمستخدم
- ✅ تحذير في الواجهة عند تغيير Visit Type إذا كان هناك بيانات

**الملفات المحدثة:**
- `app/Models/Operation.php` - إضافة `hasData()` و `isEmpty()`
- `app/Livewire/AppointmentManager.php` - منطق الحماية
- `resources/views/livewire/appointment-manager.blade.php` - تحذير في الواجهة

**الملفات الجديدة:**
- `docs/VISIT_TYPE_SAFETY.md` - توثيق شامل للحماية

**السلوك:**
- عند تغيير Visit Type إلى "Assessment" → يتم إنشاء Operation تلقائياً
- عند تغيير Visit Type من "Assessment" → يتم إلغاء ربط Operation فقط (البيانات محفوظة)
- تحذير يظهر في الواجهة إذا كان هناك بيانات

---

#### 4. إصلاح مشكلة تكرار الملاحظات
- ✅ إصلاح منطق النسخ في `OperationManager.php`:
  - النسخ فقط إذا كانت حقول OS فارغة
  - عدم نسخ إذا كان المستخدم أدخل بيانات في OS
- ✅ إصلاح منطق النسخ في `OperationNoteManager.php`:
  - النسخ فقط إذا كانت حقول OS فارغة
  - حقل `notes` لا يُنسخ - يبقى مشتركاً

**الملفات المحدثة:**
- `app/Livewire/OperationManager.php`
- `app/Livewire/OperationNoteManager.php`

**المشكلة:**
- عند تفعيل "Same operation type for both eyes"، كانت الملاحظات تُنسخ تلقائياً من OD إلى OS

**الحل:**
- النسخ فقط إذا كانت حقول OS فارغة
- كل عين تحتفظ ببياناتها المنفصلة
- حقل `notes` لا يُنسخ - يبقى مشتركاً

---

#### 5. صفحة العمليات المجدولة (Scheduled Operations)
- ✅ إنشاء Component جديد: `ScheduledOperations.php`
- ✅ إنشاء View جديد: `scheduled-operations.blade.php`
- ✅ إضافة Route: `/scheduled-operations`

**المميزات:**
- ✅ البحث بالاسم، رقم الهوية، أو الهاتف
- ✅ فلترة حسب الحالة (scheduled, in_progress, completed, cancelled, postponed)
- ✅ فلترة حسب التاريخ:
  - Upcoming: العمليات القادمة
  - Today: عمليات اليوم
  - Past: العمليات السابقة
  - All: جميع العمليات
- ✅ عرض شامل للمعلومات:
  - تاريخ العملية
  - بيانات المريض
  - الطبيب
  - نوع العملية (OD/OS منفصل)
  - العين (OD/OS/OU)
  - الحالة
  - التكلفة
- ✅ أزرار:
  - View: عرض تفاصيل العملية
  - Appointment: الانتقال إلى الموعد المرتبط

**الملفات الجديدة:**
- `app/Livewire/ScheduledOperations.php`
- `resources/views/livewire/scheduled-operations.blade.php`

**الملفات المحدثة:**
- `routes/web.php` - إضافة route

---

#### 6. إضافة رابط في القائمة الجانبية
- ✅ إضافة رابط "Scheduled Operations" في القائمة الجانبية
- ✅ بعد "Assessment" وقبل "Administration"
- ✅ أيقونة تقويم مناسبة
- ✅ تفعيل تلقائي عند زيارة الصفحة

**الملفات المحدثة:**
- `resources/views/components/layouts/app.blade.php`

---

### الملفات المحدثة/المضافة في هذه الجلسة:

#### ملفات جديدة:
1. ✅ `app/Livewire/ScheduledOperations.php`
2. ✅ `resources/views/livewire/scheduled-operations.blade.php`
3. ✅ `docs/VISIT_TYPE_ANALYSIS.md`
4. ✅ `docs/VISIT_TYPE_SAFETY.md`
5. ✅ `docs/SESSION_2025_01_XX.md`

#### ملفات محدثة:
1. ✅ `app/Livewire/AppointmentManager.php`
   - القيم الافتراضية للتاريخ والوقت
   - منطق الحماية عند تغيير Visit Type
   - تحذير في الواجهة

2. ✅ `app/Models/Operation.php`
   - إضافة `hasData()` method
   - إضافة `isEmpty()` method

3. ✅ `app/Livewire/OperationManager.php`
   - إصلاح منطق نسخ الملاحظات

4. ✅ `app/Livewire/OperationNoteManager.php`
   - إصلاح منطق نسخ الملاحظات عند تفعيل "Same operation type"

5. ✅ `resources/views/livewire/appointment-manager.blade.php`
   - تحذير عند تغيير Visit Type

6. ✅ `resources/views/components/layouts/app.blade.php`
   - إضافة رابط Scheduled Operations في القائمة

7. ✅ `routes/web.php`
   - إضافة route للعمليات المجدولة

---

### المشاكل التي تم حلها:

1. ✅ **عدم وجود قيم افتراضية للتاريخ والوقت**
   - الحل: تم إضافة القيم الافتراضية (التاريخ والوقت الحاليين)

2. ✅ **فقدان البيانات عند تغيير Visit Type**
   - الحل: تم إضافة حماية شاملة - لا يتم حذف Operation أبداً

3. ✅ **تكرار الملاحظات تلقائياً**
   - الحل: تم إصلاح منطق النسخ - لا يتم النسخ إلا إذا كانت الحقول فارغة

4. ✅ **عدم وجود صفحة للعمليات المجدولة**
   - الحل: تم إنشاء صفحة جديدة مع فلترة وبحث متقدم

5. ✅ **عدم ظهور الرابط في القائمة**
   - الحل: تم إضافة الرابط في القائمة الجانبية

---

### حالة المشروع الحالية:
- ✅ جميع التعديلات تمت بنجاح
- ✅ الكاش تم مسحه والأصول تم إعادة بنائها
- ✅ النظام جاهز للاستخدام
- ✅ التوثيق محدث

---

*Last Updated: 2025-01-XX*
*Project: Medical Management System for Al-Ghad Eye Surgery Center*
*Developer: AI Assistant (Auto - Cursor)*
*Documentation Version: 2.2 - Visit Type Safety + Scheduled Operations*