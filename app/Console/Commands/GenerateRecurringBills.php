<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateRecurringBills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bills:generate-recurring {--dry-run : Show what would be generated without actually creating bills}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate recurring bills for companies based on their billing schedule';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('Starting recurring bills generation...');
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No bills will be created');
        }
        
        // Get all recurring bills that are due for generation
        $recurringBills = Bill::where('is_recurring', true)
            ->whereNotNull('next_billing_date')
            ->where('next_billing_date', '<=', Carbon::today())
            ->get();
        
        $generatedCount = 0;
        $errorCount = 0;
        
        foreach ($recurringBills as $bill) {
            try {
                $this->info("Processing recurring bill: {$bill->bill_number} for {$bill->company->name}");
                
                if ($isDryRun) {
                    $this->line("  Would generate bill for period: {$bill->next_billing_date->format('Y-m')}");
                    $this->line("  Amount: Rp " . number_format($bill->amount, 0, ',', '.'));
                    $this->line("  Due date: {$bill->next_billing_date->format('d/m/Y')}");
                    $generatedCount++;
                } else {
                    $newBill = $bill->generateNextBill();
                    
                    if ($newBill) {
                        $this->info("  ✓ Generated new bill: {$newBill->bill_number}");
                        $generatedCount++;
                        
                        Log::info('Recurring bill generated', [
                            'parent_bill_id' => $bill->id,
                            'new_bill_id' => $newBill->id,
                            'bill_number' => $newBill->bill_number,
                            'company_id' => $newBill->company_id,
                            'amount' => $newBill->amount,
                        ]);
                    } else {
                        $this->warn("  ! Bill already exists for this period");
                    }
                }
                
            } catch (\Exception $e) {
                $this->error("  ✗ Error generating bill for {$bill->bill_number}: {$e->getMessage()}");
                $errorCount++;
                
                Log::error('Error generating recurring bill', [
                    'bill_id' => $bill->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("- Processed: {$recurringBills->count()} recurring bills");
        $this->info("- Generated: {$generatedCount} new bills");
        
        if ($errorCount > 0) {
            $this->error("- Errors: {$errorCount}");
        }
        
        if ($isDryRun) {
            $this->warn("This was a dry run. Use the command without --dry-run to actually generate bills.");
        }
        
        return Command::SUCCESS;
    }
}
