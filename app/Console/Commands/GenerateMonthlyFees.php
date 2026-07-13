<?php
namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:generate-monthly-fees')]
#[Description('Command description')]
class GenerateMonthlyFees extends Command
{
    /**
     * Execute the console command.
     */
    // app/Console/Commands/GenerateMonthlyFees.php
    public function handle()
    {
        $students   = \App\Models\Student::all();
        $monthlyFee = \App\Models\FeeStructure::where('type', 'monthly')->first();
        if (! $monthlyFee) {
            return;
        }

        foreach ($students as $student) {
            \App\Models\FeeInvoice::create([
                'student_id'       => $student->user_id,
                'fee_structure_id' => $monthlyFee->id,
                'amount'           => $monthlyFee->amount,
                'due_date'         => now()->addDays(15),
                'status'           => 'pending',
            ]);
        }
        $this->info('Monthly invoices generated.');
    }
}
