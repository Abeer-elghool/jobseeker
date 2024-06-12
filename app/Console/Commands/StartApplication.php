<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class StartApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the application with necessary setup commands';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Running composer install...');
        exec('composer install');

        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);

        $this->info('Generating application key...');
        Artisan::call('key:generate');

        $this->info('Starting the queue worker...');
        exec('nohup php artisan queue:work > queue.log 2>&1 &');

        $this->info('Creating storage link...');
        Artisan::call('storage:link');

        $this->info('Starting the development server...');
        exec('php artisan serve');

        return 0;
    }
}

