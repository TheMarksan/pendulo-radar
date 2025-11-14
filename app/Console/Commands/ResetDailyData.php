<?php

namespace App\Console\Commands;

use App\Models\Passenger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ResetDailyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all passenger data daily at midnight';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily data reset...');

        // Get all passengers
        $passengers = Passenger::all();
        $count = $passengers->count();

        // Delete all receipt files
        foreach ($passengers as $passenger) {
            if ($passenger->receipt_path) {
                Storage::disk('public')->delete($passenger->receipt_path);
            }
        }

        // Delete all passenger records
        Passenger::truncate();

        $this->info("Successfully deleted {$count} passenger records and their receipts.");
        $this->info('Daily reset completed!');

        return Command::SUCCESS;
    }
}
