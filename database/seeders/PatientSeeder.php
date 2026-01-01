<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $arabicNames = [
            'أحمد محمد العلي', 'فاطمة علي السالم', 'خالد عبدالله النور', 'سارة حسن المالكي',
            'محمد يوسف الشمري', 'نورا عبدالرحمن القحطاني', 'عبدالله سعد الدوسري', 'ليلى عمر العتيبي',
            'يوسف فهد الحربي', 'مريم خالد الزهراني', 'علي حمد المطيري', 'هند صالح الغامدي',
            'سعد ناصر العنزي', 'ريم عبدالعزيز الشهري', 'فهد بندر العسيري', 'لينا طارق البقمي',
            'طارق وليد الجهني', 'سلمى راشد الدوسري', 'وليد خالد القحطاني', 'نور خالد العتيبي'
        ];

        $palestinianNames = [
            'محمد أحمد النابلسي', 'فاطمة خليل القدسي', 'علي محمود الخليل', 'سارة يوسف رام الله',
            'خالد إبراهيم نابلس', 'مريم حسن بيت لحم', 'أحمد سعد غزة', 'ليلى عمر يافا',
            'يوسف فهد حيفا', 'نورا عبدالرحمن عكا', 'عبدالله حمد طوباس', 'هند صالح جنين',
            'سعد ناصر طولكرم', 'ريم عبدالعزيز قلقيلية', 'فهد بندر سلفيت', 'لينا طارق رام الله',
            'طارق وليد الخليل', 'سلمى راشد بيت جالا', 'وليد خالد بيت ساحور', 'نور خالد أريحا',
            'حسن علي نابلس', 'عائشة محمد القدس', 'محمود يوسف رام الله', 'زينب أحمد الخليل'
        ];

        $cities = ['الرياض', 'جدة', 'الدمام', 'المدينة المنورة', 'مكة المكرمة', 'الطائف', 'بريدة', 'خميس مشيط', 'حائل', 'الجبيل'];
        $occupations = ['طبيب', 'مهندس', 'معلم', 'موظف', 'طالب', 'ربة منزل', 'تاجر', 'محاسب', 'محامي', 'ممرض'];

        // Add 10 Saudi patients
        for ($i = 0; $i < 10; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $dateOfBirth = $faker->dateTimeBetween('-70 years', '-18 years');
            
            // Generate unique ID number (10 digits starting with 1)
            $idNumber = '1' . str_pad((string)($faker->unique()->numberBetween(100000000, 999999999)), 9, '0', STR_PAD_LEFT);
            
            // Generate phone number (Saudi format: 05XXXXXXXX)
            $phone = '05' . str_pad((string)($faker->unique()->numberBetween(1000000, 9999999)), 7, '0', STR_PAD_LEFT);
            
            // Generate secondary phone (optional, 30% chance)
            $phoneSecondary = $faker->boolean(30) ? '05' . str_pad((string)($faker->unique()->numberBetween(1000000, 9999999)), 7, '0', STR_PAD_LEFT) : null;

            Patient::create([
                'full_name' => $faker->randomElement($arabicNames),
                'id_number' => $idNumber,
                'date_of_birth' => $dateOfBirth->format('Y-m-d'),
                'gender' => $gender,
                'phone' => $phone,
                'phone_secondary' => $phoneSecondary,
                'city' => $faker->randomElement($cities),
                'occupation' => $faker->randomElement($occupations),
                'notes' => $faker->boolean(40) ? $faker->sentence() : null,
            ]);
        }

        // Add 20 Palestinian patients
        $palestinianCities = ['رام الله', 'نابلس', 'القدس', 'الخليل', 'بيت لحم', 'جنين', 'طولكرم', 'قلقيلية', 'سلفيت', 'طوباس'];
        
        for ($i = 0; $i < 20; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $dateOfBirth = $faker->dateTimeBetween('-70 years', '-18 years');
            
            // Generate unique ID number (10 digits starting with 2 for Palestinian)
            $idNumber = '2' . str_pad((string)($faker->unique()->numberBetween(100000000, 999999999)), 9, '0', STR_PAD_LEFT);
            
            // Generate phone number (Palestinian format: 059XXXXXXXX)
            $phone = '059' . str_pad((string)($faker->unique()->numberBetween(1000000, 9999999)), 7, '0', STR_PAD_LEFT);
            
            // Generate secondary phone (optional, 30% chance)
            $phoneSecondary = $faker->boolean(30) ? '059' . str_pad((string)($faker->unique()->numberBetween(1000000, 9999999)), 7, '0', STR_PAD_LEFT) : null;

            Patient::create([
                'full_name' => $faker->randomElement($palestinianNames),
                'id_number' => $idNumber,
                'date_of_birth' => $dateOfBirth->format('Y-m-d'),
                'gender' => $gender,
                'phone' => $phone,
                'phone_secondary' => $phoneSecondary,
                'city' => $faker->randomElement($palestinianCities),
                'occupation' => $faker->randomElement($occupations),
                'notes' => $faker->boolean(40) ? $faker->sentence() : null,
            ]);
        }
    }
}

