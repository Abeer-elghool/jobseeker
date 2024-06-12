<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Events\UserRegistered;

class UserObserver
{
    public function creating(User $user)
    {
        $user->uuid = (string) Str::uuid();
    }

    public function created(User $user)
    {
        event(new UserRegistered($user));
    }
}
