<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PasswordResetCode;

class CleanupExpiredResetCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-expired-reset-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired password reset codes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $deletedCount = PasswordResetCode::cleanupExpired();
        
        $this->info("Cleaned up {$deletedCount} expired password reset codes.");
        
        return Command::SUCCESS;
    }
}