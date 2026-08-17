<div>
{{-- Print-only: ترويسة (الصورة الثانية) + محتوى التقرير --}}
@php
    $patient = $appointment->patient ?? null;
    $procDate = $procedure_date ? \Carbon\Carbon::parse($procedure_date) : null;
    $procDateFormatted = $procDate ? $procDate->format('d.m.Y') : '';
    $arabicDays = [
        0 => 'الأحد',
        1 => 'الاثنين',
        2 => 'الثلاثاء',
        3 => 'الأربعاء',
        4 => 'الخميس',
        5 => 'الجمعة',
        6 => 'السبت',
    ];
    $procDayName = $procDate ? ($arabicDays[$procDate->dayOfWeek] ?? '') : '';
    $reportDateFormatted = $report_date ? \Carbon\Carbon::parse($report_date)->format('d.m.Y') : '';
    $leaveLabel = $leave_duration === '2_weeks' ? 'أسبوعين' : 'أسبوع';
@endphp
<div class="report-print-wrapper hidden print:block print:fixed print:inset-0 print:z-[99999] print:bg-white print:overflow-hidden print:p-6 print:flex print:flex-col">
    {{-- المحتوى الرئيسي --}}
    <div>
    {{-- الترويسة الرسمية: صورة الشعار كما هي (يُفضّل وضع الصورة في public/images/medical-report-header.png) --}}
    @php $headerImagePath = 'images/medical-report-header.png'; $headerImageExists = file_exists(public_path($headerImagePath)); @endphp
    @if($headerImageExists)
        <div class="report-header mb-4">
            <img src="{{ asset($headerImagePath) }}" alt="ترويسة التقرير الرسمية - مركز الغد لجراحة العيون والليزك - مستشفى الميزان التخصصي" class="w-full max-h-44 object-contain object-center" />
        </div>
    @else
        {{-- ترويسة احتياطية إذا لم تُضف الصورة بعد --}}
        <div class="report-header border-b-2 border-sky-200 pb-3 mb-4">
            <div class="flex flex-row justify-between items-start gap-4 w-full" dir="rtl">
                <div class="flex flex-col items-center flex-1 text-center">
                    <div class="text-2xl font-bold text-blue-600 mb-1">مركز الغد لجراحة العيون والليزك</div>
                    <div class="text-lg font-semibold text-green-600 border-b-2 border-green-500 pb-0.5">مستشفى الميزان</div>
                </div>
                <div class="flex-shrink-0 flex items-center justify-center">
                    <div class="w-28 h-28 rounded-full border-4 border-red-600 flex items-center justify-center bg-white text-center">
                        <div class="text-[10px] font-bold text-red-600 px-1 leading-tight">مستشفى الميزان التخصصي<br/>الغد للخدمات الطبية</div>
                    </div>
                </div>
                <div class="flex flex-col items-end flex-1 text-right">
                    <div class="text-xl font-bold text-blue-600">مركز الغد لجراحة العيون والليزك</div>
                    <div class="text-base font-semibold text-green-600 border-b-2 border-green-500 pb-0.5 mb-2">مستشفى الميزان التخصصي</div>
                    <div class="text-sm text-gray-700 space-y-1">
                        <p class="flex items-center gap-2 justify-end"><span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span> فلسطين، الخليل، رأس الجورة - دخلة حرم الرامة</p>
                        <p class="flex items-center gap-2 justify-end"><span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span> 0595500580</p>
                        <p class="flex items-center gap-2 justify-end"><span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span> mezan.eyes@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- عنوان التقرير --}}
    <h2 class="text-xl font-bold text-center my-4" dir="rtl">تقرير طبي</h2>
    {{-- فراغ بين تقرير طبي والاسم --}}
    <div class="h-6"></div>
    {{-- بيانات المريض: كل حقل في سطر --}}
    <div class="flex flex-col gap-2 mb-6" dir="rtl">
        <p><strong>الاسم :</strong> {{ $patient ? $patient->full_name : '—' }}</p>
        <p><strong>تاريخ الميلاد :</strong> {{ $patient && $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d.m.Y') : '—' }}</p>
        <p><strong>رقم الهوية :</strong> {{ $patient ? $patient->id_number : '—' }}</p>
    </div>
    {{-- سطران فوق نص التقرير --}}
    <div class="h-12 print:h-12" aria-hidden="true"></div>
    {{-- نص التقرير --}}
    <p class="text-justify leading-relaxed mb-4" dir="rtl">
        حضر المريض المذكور أعلاه إلى مستشفى الميزان التخصصي لقسم العيون يوم {{ $procDayName }} بتاريخ <strong>{{ $procDateFormatted }}</strong> لإجراء عملية تصحيح النظر بالليزر في كلتا العينين، وخرج المريض وهو بصحة جيدة مرفقاً بالعلاج اللازم. وهو بحاجة للراحة والإجازة <strong>لمدة {{ $leaveLabel }} من تاريخ إجراء العملية</strong>.
    </p>
    <p class="text-center font-medium my-4" dir="rtl">أعطي هذا التقرير بناءاً على طلبه</p>
    </div>
    {{-- أربعة أسطر بين النص والتحرير والطبيب --}}
    <div class="h-24" aria-hidden="true"></div>
    {{-- تحريراً بتاريخ واسم الطبيب --}}
    <div class="text-left space-y-2" dir="rtl">
        <p><strong>تحريراً بتاريخ :</strong> {{ $reportDateFormatted }}</p>
        <p><strong>الطبيب الأخصائي :</strong> {{ $doctor_name }}</p>
    </div>
</div>

<div class="container mx-auto p-4 print:hidden">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                @php
                    $operation = $appointment->operation ?? null;
                    $backUrl = $operation
                        ? route('operations.edit', ['id' => $operation->id])
                        : route('operations.index');
                @endphp
                <a href="{{ $backUrl }}" class="btn btn-sm btn-ghost mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <h1>Medical Report</h1>
                <p>Issue a medical report for the operation visit</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card-modern">
        <div class="card-body">
            <div class="space-y-6">
                {{-- Patient data (read-only) --}}
                @php $patient = $appointment->patient; @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Patient Name</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-medium">
                            {{ $patient ? $patient->full_name : 'N/A' }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                            @if($patient && $patient->date_of_birth)
                                {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d.m.Y') }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ID Number</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                            {{ $patient ? $patient->id_number : 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Editable fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Procedure Date</label>
                        <input type="date" wire:model.live="procedure_date" class="form-input" dir="ltr">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Leave Duration</label>
                        <select wire:model="leave_duration" class="form-select">
                            <option value="1_week">أسبوع</option>
                            <option value="2_weeks">أسبوعين</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Report Issue Date</label>
                        <input type="date" wire:model="report_date" class="form-input" dir="ltr">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Doctor Name</label>
                        <input type="text" wire:model="doctor_name" class="form-input" placeholder="Enter doctor name">
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                    <button type="button" class="btn-add btn-action flex items-center gap-2" onclick="printReportWithUniqueName();">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2h-2m-4-1v8m0 0l-4-4m4 4l4-4" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
function printReportWithUniqueName() {
    var now = new Date();
    var dateStr = now.getFullYear() + '' + String(now.getMonth() + 1).padStart(2, '0') + '' + String(now.getDate()).padStart(2, '0');
    var timeStr = String(now.getHours()).padStart(2, '0') + '' + String(now.getMinutes()).padStart(2, '0') + '' + String(now.getSeconds()).padStart(2, '0');
    var random = Math.random().toString(36).substring(2, 10);
    var uniqueName = 'tqreer-tbee-' + dateStr + '-' + timeStr + '-' + random + '.pdf';
    var oldTitle = document.title;
    document.title = uniqueName;
    if (window.onafterprint !== undefined) {
        var restore = function() { document.title = oldTitle; window.onafterprint = null; };
        window.onafterprint = restore;
    } else {
        setTimeout(function() { document.title = oldTitle; }, 1000);
    }
    window.print();
}
</script>
<style>
@media print {
    .report-print-wrapper { display: block !important; position: fixed !important; left: 0 !important; top: 0 !important; right: 0 !important; bottom: 0 !important; z-index: 99999 !important; background: #fff !important; color: #111 !important; overflow: auto !important; padding: 1.5rem !important; }
    .print\:hidden { display: none !important; }
    .drawer-side, .drawer-overlay, label[for="drawer-toggle"], [data-drawer] .drawer-side { display: none !important; }
    main { padding: 0 !important; }
}
</style>
</div>
