<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\CustomJob;

class CustomJobObserver
{
    public function creating(CustomJob $custom_job)
    {
        $custom_job->uuid = (string) Str::uuid();
    }
}
