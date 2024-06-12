<?php

namespace App\Console\Commands;

use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired files from storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Get applications with expired files
        $expiredApplications = Application::where('expires_at', '<', $now)->get();

        foreach ($expiredApplications as $application) {
            // Delete the file from storage
            if ($application->getAttributes()['cv_path'] && Storage::disk('public')->exists($application->getAttributes()['cv_path'])) {
                Storage::disk('public')->delete($application->getAttributes()['cv_path']);
            }

            // Optionally, delete the application record
            $application->delete();
        }

        return Command::SUCCESS;
    }
}
