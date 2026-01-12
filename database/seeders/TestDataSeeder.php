<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Branch;
use App\Models\Appointment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * Seeder لتجهيز بيانات تجريبية للنظام
 * 
 * الهدف: إنشاء 10 مرضى بأسماء فلسطينية، طبيبين (علاء وطارق)، و10 زيارات (Assessment/Operation)
 * 
 * الاستخدام: php artisan db:seed --class=TestDataSeeder
 */
class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء فرع رئيسي إذا لم يكن موجوداً
        $branch = Branch::firstOrCreate(
            ['name' => 'Main Branch'],
            [
                'address' => 'رام الله - فلسطين',
                'phone' => '0599999999',
                'email' => 'main@almyzan.ps',
                'is_active' => true,
            ]
        );

        $this->command->info('✓ Branch created/verified');

        // إنشاء الأطباء مع حسابات User
        $doctors = $this->createDoctors($branch);
        $this->command->info('✓ Doctors created');

        // إنشاء 10 مرضى بأسماء فلسطينية
        $patients = $this->createPalestinianPatients();
        $this->command->info('✓ 10 Palestinian patients created');

        // إنشاء 10 زيارات (5 Assessment و 5 Operation)
        $this->createAppointments($patients, $doctors, $branch);
        $this->command->info('✓ 10 Appointments created (5 Assessment, 5 Operation)');

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('✅ Test Data Seeded Successfully!');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('');
        $this->command->info('Doctors Login:');
        $this->command->info('  - Dr. Alaa: alaa@almyzan.ps / password123');
        $this->command->info('  - Dr. Tariq: tariq@almyzan.ps / password123');
        $this->command->info('');
    }

    /**
     * إنشاء الأطباء مع حسابات User
     */
    private function createDoctors(Branch $branch): array
    {
        $doctors = [];

        // الدكتور علاء
        $alaaUser = User::firstOrCreate(
            ['email' => 'alaa@almyzan.ps'],
            [
                'name' => 'Dr. Alaa Al-Talbishi',
                'password' => Hash::make('password123'),
                'role' => 'doctor',
                'branch_id' => $branch->id,
                'phone' => '0591234567',
                'is_active' => true,
            ]
        );
        $alaaUser->assignRole('doctor');

        $alaaDoctor = Doctor::firstOrCreate(
            ['user_id' => $alaaUser->id],
            [
                'name' => 'Dr. Alaa Al-Talbishi',
                'phone' => '0591234567',
                'branch_id' => $branch->id,
                'specialization' => 'Ophthalmology',
                'notify_via_sms' => true,
                'notify_via_email' => true,
                'follow_up' => true,
            ]
        );

        $doctors[] = $alaaDoctor;

        // الدكتور طارق
        $tariqUser = User::firstOrCreate(
            ['email' => 'tariq@almyzan.ps'],
            [
                'name' => 'Dr. Tariq Al-Husseini',
                'password' => Hash::make('password123'),
                'role' => 'doctor',
                'branch_id' => $branch->id,
                'phone' => '0597654321',
                'is_active' => true,
            ]
        );
        $tariqUser->assignRole('doctor');

        $tariqDoctor = Doctor::firstOrCreate(
            ['user_id' => $tariqUser->id],
            [
                'name' => 'Dr. Tariq Al-Husseini',
                'phone' => '0597654321',
                'branch_id' => $branch->id,
                'specialization' => 'Refractive Surgery',
                'notify_via_sms' => true,
                'notify_via_email' => true,
                'follow_up' => true,
            ]
        );

        $doctors[] = $tariqDoctor;

        return $doctors;
    }

    /**
     * إنشاء 10 مرضى بأسماء فلسطينية
     */
    private function createPalestinianPatients(): array
    {
        $palestinianNames = [
            'محمد أحمد النابلسي',
            'فاطمة خليل القدسي',
            'علي محمود الخليل',
            'سارة يوسف رام الله',
            'خالد إبراهيم نابلس',
            'مريم حسن بيت لحم',
            'أحمد سعد غزة',
            'ليلى عمر يافا',
            'يوسف فهد حيفا',
            'نورا عبدالرحمن عكا',
        ];

        $palestinianCities = [
            'رام الله',
            'نابلس',
            'القدس',
            'الخليل',
            'بيت لحم',
            'جنين',
            'طولكرم',
            'قلقيلية',
            'سلفيت',
            'طوباس',
        ];

        $genders = ['male', 'female'];
        $patients = [];

        for ($i = 0; $i < 10; $i++) {
            $gender = $genders[$i % 2]; // تناوب بين ذكر وأنثى
            $dateOfBirth = Carbon::now()->subYears(rand(25, 65))->subMonths(rand(0, 11))->subDays(rand(0, 30));
            
            // رقم هوية فلسطيني (9 أرقام)
            $idNumber = '2' . str_pad((string)(100000000 + $i), 9, '0', STR_PAD_LEFT);
            
            // رقم هاتف فلسطيني (059XXXXXXXX)
            $phone = '059' . str_pad((string)(1000000 + $i * 100), 7, '0', STR_PAD_LEFT);

            $patient = Patient::create([
                'full_name' => $palestinianNames[$i],
                'date_of_birth' => $dateOfBirth->format('Y-m-d'),
                'gender' => $gender,
                'phone' => $phone,
                'phone_secondary' => rand(0, 100) > 70 ? '059' . str_pad((string)(2000000 + $i * 100), 7, '0', STR_PAD_LEFT) : null,
                'city' => $palestinianCities[$i],
                'country' => 'فلسطين',
                'notes' => $i % 3 === 0 ? 'مريض جديد - يحتاج تقييم شامل' : null,
            ]);

            $patients[] = $patient;
        }

        return $patients;
    }

    /**
     * إنشاء 10 زيارات (5 Assessment و 5 Operation)
     */
    private function createAppointments(array $patients, array $doctors, Branch $branch): void
    {
        $timeSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00',
            '11:30', '12:00', '12:30', '13:00', '13:30',
        ];

        // إنشاء 5 زيارات Assessment
        for ($i = 0; $i < 5; $i++) {
            $appointmentDate = Carbon::now()->addDays(rand(-30, 30));
            $doctor = $doctors[rand(0, 1)]; // اختيار طبيب عشوائي

            Appointment::create([
                'patient_id' => $patients[$i]->id,
                'doctor_id' => $doctor->id,
                'branch_id' => $branch->id,
                'created_by' => 1, // Admin user
                'appointment_date' => $appointmentDate->format('Y-m-d'),
                'appointment_time' => $timeSlots[$i],
                'duration' => 30,
                'visit_type' => 'Assessment',
                'visit_stage' => $appointmentDate->isPast() ? 'completed' : 'waiting',
                'status' => $appointmentDate->isPast() ? 'completed' : 'scheduled',
                'notes' => 'تقييم أولي للمريض - فحص شامل للعين',
                'notify_patient_sms' => false,
                'notify_doctor_sms' => false,
                'notify_doctor_email' => false,
                'follow_up' => false,
            ]);
        }

        // إنشاء 5 زيارات Operation
        for ($i = 5; $i < 10; $i++) {
            $appointmentDate = Carbon::now()->addDays(rand(-30, 30));
            $doctor = $doctors[rand(0, 1)]; // اختيار طبيب عشوائي

            Appointment::create([
                'patient_id' => $patients[$i]->id,
                'doctor_id' => $doctor->id,
                'branch_id' => $branch->id,
                'created_by' => 1, // Admin user
                'appointment_date' => $appointmentDate->format('Y-m-d'),
                'appointment_time' => $timeSlots[$i],
                'duration' => 60,
                'visit_type' => 'Operation',
                'visit_stage' => $appointmentDate->isPast() ? 'completed' : 'waiting',
                'status' => $appointmentDate->isPast() ? 'completed' : 'scheduled',
                'notes' => 'عملية جراحية - LASIK / Femto-LASIK',
                'notify_patient_sms' => false,
                'notify_doctor_sms' => false,
                'notify_doctor_email' => false,
                'follow_up' => false,
            ]);
        }
    }
}
