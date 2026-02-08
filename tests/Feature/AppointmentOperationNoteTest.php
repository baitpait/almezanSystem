<?php

namespace Tests\Feature;

use Tests\TestCase;

class AppointmentOperationNoteTest extends TestCase
{

    /**
     * مسار operation-notes.create يجب أن يولد الرابط الصحيح لصفحة Operation Note.
     */
    public function test_operation_notes_route_generates_correct_url(): void
    {
        $url = route('operation-notes.create', ['appointmentId' => 123]);
        $this->assertStringContainsString('operation-notes/appointment/123', $url);
    }

    /**
     * عند استدعاء goToOperationNote لموعد من نوع Operation يتم التوجيه لصفحة Operation Note.
     * (التحقق من أن الراوت موجود وقابل للاستدعاء)
     */
    public function test_operation_notes_route_exists(): void
    {
        $response = $this->get(route('operation-notes.create', ['appointmentId' => 999]));
        // قد يكون 302 (توجيه للتسجيل) أو 200 حسب الصلاحيات
        $this->assertContains($response->status(), [200, 302]);
    }
}
