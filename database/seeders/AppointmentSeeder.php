<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        if ($patients->isEmpty() || $doctors->isEmpty()) {
            $this->command->warn('No patients or doctors found. Please seed patients and doctors first.');
            return;
        }

        $visitTypes = ['Assessment', 'Operation', 'Follow up', 'New visit'];
        $visitStages = ['waiting', 'in_consultation', 'completed'];
        $durations = [15, 30, 45, 60];
        $timeSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
        ];

        $appointments = [];
        $patientAppointmentCount = []; // Track appointments per patient

        // Initialize patient appointment counts
        foreach ($patients as $patient) {
            $patientAppointmentCount[$patient->id] = 0;
        }

        // Create 60 appointments
        for ($i = 0; $i < 60; $i++) {
            // Select patient - ensure some patients have multiple appointments
            $patient = $this->selectPatient($patients, $patientAppointmentCount);
            $patientAppointmentCount[$patient->id]++;

            // Select doctor randomly
            $doctor = $doctors->random();

            // Generate date - spread over last 3 months and next 2 months
            $daysOffset = rand(-90, 60); // -90 to +60 days
            $appointmentDate = Carbon::now()->addDays($daysOffset);

            // Select time slot
            $appointmentTime = $timeSlots[array_rand($timeSlots)];

            // Select visit type - ensure variety
            $visitType = $this->selectVisitType($patient->id, $patientAppointmentCount[$patient->id], $visitTypes);

            // Select visit stage based on date
            $visitStage = $this->selectVisitStage($appointmentDate, $visitStages);

            // Select duration
            $duration = $durations[array_rand($durations)];

            // Generate notes (optional)
            $notes = $this->generateNotes($visitType);

            $appointments[] = [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'branch_id' => 1, // Assuming branch_id = 1
                'created_by' => 1, // Assuming admin user
                'appointment_date' => $appointmentDate->format('Y-m-d'),
                'appointment_time' => $appointmentTime,
                'duration' => $duration,
                'visit_type' => $visitType,
                'visit_stage' => $visitStage,
                'status' => 'scheduled',
                'notes' => $notes,
                'notify_patient_sms' => false,
                'notify_doctor_sms' => false,
                'notify_doctor_email' => false,
                'follow_up' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert all appointments
        Appointment::insert($appointments);

        $this->command->info('Successfully created 60 appointments with variety in visit types and patient distribution.');
    }

    /**
     * Select patient ensuring some have multiple appointments
     */
    private function selectPatient($patients, $patientAppointmentCount): Patient
    {
        // 30% chance to select a patient who already has appointments
        if (rand(1, 100) <= 30 && !empty($patientAppointmentCount)) {
            $patientsWithAppointments = array_filter($patientAppointmentCount, fn($count) => $count > 0);
            if (!empty($patientsWithAppointments)) {
                $patientId = array_rand($patientsWithAppointments);
                return $patients->find($patientId);
            }
        }

        // Otherwise select random patient
        return $patients->random();
    }

    /**
     * Select visit type ensuring variety per patient
     */
    private function selectVisitType($patientId, $appointmentNumber, $visitTypes): string
    {
        // Get existing appointments for this patient
        $existingAppointments = Appointment::where('patient_id', $patientId)
            ->pluck('visit_type')
            ->toArray();

        // If patient has multiple appointments, ensure variety
        if ($appointmentNumber > 1 && !empty($existingAppointments)) {
            // 60% chance to use a different visit type
            if (rand(1, 100) <= 60) {
                $availableTypes = array_diff($visitTypes, $existingAppointments);
                if (!empty($availableTypes)) {
                    return $availableTypes[array_rand($availableTypes)];
                }
            }
            // 40% chance to use same visit type (for Follow up or multiple Assessments)
            if (rand(1, 100) <= 40) {
                return $existingAppointments[array_rand($existingAppointments)];
            }
        }

        // Random selection for first appointment or when variety not needed
        return $visitTypes[array_rand($visitTypes)];
    }

    /**
     * Select visit stage based on appointment date
     */
    private function selectVisitStage($appointmentDate, $visitStages): string
    {
        $today = Carbon::today();
        $daysDiff = $today->diffInDays($appointmentDate, false);

        // Past appointments are more likely to be completed
        if ($daysDiff < 0) {
            $weights = [
                'completed' => 70,
                'in_consultation' => 20,
                'waiting' => 10,
            ];
        }
        // Future appointments are more likely to be waiting
        elseif ($daysDiff > 7) {
            $weights = [
                'waiting' => 80,
                'in_consultation' => 15,
                'completed' => 5,
            ];
        }
        // Today or near future
        else {
            $weights = [
                'waiting' => 50,
                'in_consultation' => 40,
                'completed' => 10,
            ];
        }

        return $this->weightedRandom($visitStages, $weights);
    }

    /**
     * Weighted random selection
     */
    private function weightedRandom($items, $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        $currentWeight = 0;

        foreach ($items as $item) {
            $currentWeight += $weights[$item] ?? 0;
            if ($random <= $currentWeight) {
                return $item;
            }
        }

        return $items[0];
    }

    /**
     * Generate notes based on visit type
     */
    private function generateNotes($visitType): ?string
    {
        $notes = [
            'Assessment' => [
                'Initial assessment for refractive surgery evaluation.',
                'Pre-operative assessment and consultation.',
                'Comprehensive eye examination for surgery candidacy.',
            ],
            'Operation' => [
                'Scheduled LASIK procedure.',
                'Femto-LASIK surgery appointment.',
                'PRK operation scheduled.',
            ],
            'Follow up' => [
                'Post-operative follow-up visit.',
                'Routine follow-up after surgery.',
                'Check-up appointment.',
            ],
            'New visit' => [
                'New patient consultation.',
                'General eye examination.',
                'Routine check-up visit.',
            ],
        ];

        if (isset($notes[$visitType]) && rand(1, 100) <= 70) {
            return $notes[$visitType][array_rand($notes[$visitType])];
        }

        return null;
    }
}
