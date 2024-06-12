<?php

namespace App\Observers;

use App\Models\Application;
use Illuminate\Support\Str;

class ApplicationObserver
{
    public function creating(Application $application)
    {
        $application->uuid = (string) Str::uuid();
    }
}
