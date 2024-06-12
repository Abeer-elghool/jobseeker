<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Admin;

class AdminObserver
{
    public function creating(Admin $admin)
    {
        $admin->uuid = (string) Str::uuid();
    }
}
