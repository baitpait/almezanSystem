<?php

namespace Database\Seeders;

use App\Models\Procedure;
use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $procedures = [
            [
                'name' => 'PRK',
                'description' => 'Photorefractive Keratectomy',
                'default_duration' => 30,
            ],
            [
                'name' => 'Femto-LASIK',
                'description' => 'Femtosecond LASIK',
                'default_duration' => 45,
            ],
            [
                'name' => 'TransPRK',
                'description' => 'Transepithelial PRK',
                'default_duration' => 30,
            ],
            [
                'name' => 'SMILE',
                'description' => 'Small Incision Lenticule Extraction',
                'default_duration' => 40,
            ],
            [
                'name' => 'Consultation',
                'description' => 'General Eye Consultation',
                'default_duration' => 30,
            ],
            [
                'name' => 'Follow-up',
                'description' => 'Post-operative Follow-up',
                'default_duration' => 15,
            ],
        ];

        foreach ($procedures as $procedure) {
            Procedure::firstOrCreate(['name' => $procedure['name']], $procedure);
        }
    }
}
