<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;

class MedicalReportDayNameTest extends TestCase
{
    private function arabicDayName(string $date): string
    {
        $arabicDays = [
            0 => 'الأحد',
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];

        $procDate = Carbon::parse($date);

        return $arabicDays[$procDate->dayOfWeek] ?? '';
    }

    public function test_13_august_2026_is_thursday(): void
    {
        $this->assertSame('الخميس', $this->arabicDayName('2026-08-13'));
    }

    public function test_day_name_changes_with_date(): void
    {
        $this->assertSame('الأحد', $this->arabicDayName('2026-08-09'));
        $this->assertSame('الجمعة', $this->arabicDayName('2026-08-14'));
        $this->assertSame('السبت', $this->arabicDayName('2026-08-15'));
    }

    public function test_medical_report_route_exists(): void
    {
        $url = route('medical-report.form', ['appointmentId' => 1]);
        $this->assertStringContainsString('medical-report/1', $url);

        $response = $this->get($url);
        $this->assertContains($response->status(), [200, 302]);
    }
}
