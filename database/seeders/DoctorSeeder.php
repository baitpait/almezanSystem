<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'name' => "Ala'a Al-Talbishi",
                'email' => 'alaa@example.com',
                'phone' => '0501234567',
                'specialization' => 'Ophthalmology',
                'notify_via_sms' => true,
                'notify_via_email' => true,
                'follow_up' => true,
            ],
            [
                'name' => 'Dr. Ahmed Ali',
                'email' => 'ahmed@example.com',
                'phone' => '0507654321',
                'specialization' => 'Refractive Surgery',
                'notify_via_sms' => true,
                'notify_via_email' => false,
                'follow_up' => true,
            ],
        ];

        foreach ($doctors as $doctor) {
            Doctor::firstOrCreate(['email' => $doctor['email']], $doctor);
        }
    }
}
