<?php

namespace App\Http\Controllers\User\Application;

use App\Events\JobApplicationSubmitted;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Application\ApplicationRequest;
use App\Models\CustomJob;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    public function __invoke(ApplicationRequest $request)
    {
        $cv_path = $this->uploadCv($request);

        $job = CustomJob::where(['uuid' => $request->job_id])->firstOrFail();

        $expires_at = $request->expires_at ? Carbon::parse($request->expires_at) : Carbon::now()->addMonth();

        $application = auth('user')->user()->applications()->create(
            $request->safe()->except('cv', 'job_id') +
            ['cv_path' => $cv_path, 'job_id' => $job->id, 'expires_at' => $expires_at]
        );

        // using pusher to Implement real-time WebSocket broadcasting to admins only upon successful job application.
        event(new JobApplicationSubmitted($application));

        return response()->json(['message' => 'Application submitted successfully'], 201);
    }

    private function uploadCv($request)
    {
        $cv_path = null;

        if ($request->hasFile('cv')) {
            $cv_path = $request->file('cv')->store('cvs', 'public');
        }

        return $cv_path;
    }
}
