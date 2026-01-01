<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Operation;
use Illuminate\Database\Seeder;

class CreateOperationsFromAppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all Appointments of type "Assessment" or "Operation" that don't have an operation_id
        $appointments = Appointment::whereIn('visit_type', ['Assessment', 'Operation'])
            ->whereNull('operation_id')
            ->with(['patient', 'doctor', 'branch'])
            ->get();

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($appointments as $appointment) {
            // Skip if patient, doctor, or branch is missing
            if (!$appointment->patient_id || !$appointment->doctor_id || !$appointment->branch_id) {
                $this->command->warn("Skipping appointment {$appointment->id}: Missing patient, doctor, or branch");
                $skippedCount++;
                continue;
            }

            try {
                // Create Operation
                $operation = Operation::create([
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'branch_id' => $appointment->branch_id,
                    'appointment_id' => $appointment->id,
                    'created_by' => 1, // Default to admin user
                    'operation_type' => 'Femto-LASIK', // Default type, can be changed later
                    'operation_eye' => 'OU', // Default to both eyes
                    'cost' => 0.00,
                    'status' => 'scheduled',
                    'start_date' => $appointment->appointment_date ?? now(),
                ]);

                // Link Appointment to Operation
                $appointment->update(['operation_id' => $operation->id]);
                $createdCount++;

                $this->command->info("Created operation {$operation->id} for appointment {$appointment->id}");
            } catch (\Exception $e) {
                $this->command->error("Failed to create operation for appointment {$appointment->id}: " . $e->getMessage());
                $skippedCount++;
            }
        }

        $this->command->info("=== Summary ===");
        $this->command->info("Created: {$createdCount} operations");
        $this->command->info("Skipped: {$skippedCount} appointments");
    }
}
