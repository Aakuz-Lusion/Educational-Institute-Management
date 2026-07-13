<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FeeStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/FeeStructureSeeder.php
    public function run()
    {
        \App\Models\FeeStructure::create(['title' => 'Admission Fee', 'type' => 'admission', 'amount' => 5000]);
        \App\Models\FeeStructure::create(['title' => 'Monthly Tuition', 'type' => 'monthly', 'amount' => 3000]);
        \App\Models\FeeStructure::create(['title' => 'Exam Fee', 'type' => 'exam', 'amount' => 500]);
    }
}
