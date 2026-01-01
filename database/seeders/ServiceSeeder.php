<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'جراحة الساد',
                'description' => 'Cataract Surgery',
                'category' => 'عمليات',
                'base_price' => 5000.00,
                'is_active' => true,
            ],
            [
                'name' => 'جراحة الليزر',
                'description' => 'Lasik Surgery',
                'category' => 'عمليات',
                'base_price' => 8000.00,
                'is_active' => true,
            ],
            [
                'name' => 'استشارة طبية',
                'description' => 'Medical Consultation',
                'category' => 'استشارات',
                'base_price' => 200.00,
                'is_active' => true,
            ],
            [
                'name' => 'أشعة سينية',
                'description' => 'X-Ray',
                'category' => 'أشعة',
                'base_price' => 150.00,
                'is_active' => true,
            ],
            [
                'name' => 'تحاليل دم',
                'description' => 'Blood Tests',
                'category' => 'تحاليل',
                'base_price' => 100.00,
                'is_active' => true,
            ],
            [
                'name' => 'فحص عيون أساسي',
                'description' => 'Basic Eye Exam',
                'category' => 'فحوصات',
                'base_price' => 50.00,
                'is_active' => true,
            ],
            [
                'name' => 'زيارة متابعة',
                'description' => 'Follow-up Visit',
                'category' => 'استشارات',
                'base_price' => 150.00,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('Services seeded successfully!');
        $this->command->info('Created: ' . count($services) . ' services');
    }
}
