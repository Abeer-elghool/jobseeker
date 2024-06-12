<?php

namespace App\Policies;

use App\Models\CustomJob;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class JobPolicy
{

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin $admin, CustomJob $customJob): bool
    {
        return $admin->id === $customJob->admin_id;
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin $admin, CustomJob $customJob): bool
    {
        return $admin->id === $customJob->admin_id;
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin $admin, CustomJob $customJob): bool
    {
        return $admin->id === $customJob->admin_id;
    }
}
