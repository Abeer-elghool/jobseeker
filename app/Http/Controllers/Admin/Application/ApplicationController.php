<?php

namespace App\Http\Controllers\Admin\Application;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Application\ApplicationResource;
use App\Models\Application;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = auth('admin')->user()->jobs()->pluck('id');
        $applications = Application::whereIn('job_id', $jobs)->latest()->paginate();
        return ApplicationResource::collection($applications)->additional(['message' => ''], 200);
    }
}
