<?php

namespace App\Http\Controllers\Admin\Job;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Job\CreateRequest;
use App\Http\Requests\Admin\Job\UpdateRequest;
use App\Http\Resources\Admin\Job\JobResource;
use App\Models\CustomJob;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = auth('admin')->user()->jobs()->latest()->paginate();
        return JobResource::collection($jobs)->additional(['message' => ''], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $job = auth('admin')->user()->jobs()->create($request->validated());
        return JobResource::make($job)->additional(['message' => 'Job created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = CustomJob::where(['uuid' => $id])->firstOrFail();
        $this->authorize('view', $job);
        return JobResource::make($job)->additional(['message' => ''], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $job = CustomJob::where(['uuid' => $id])->firstOrFail();
        $this->authorize('update', $job);
        $job->update($request->validated());
        return JobResource::make($job)->additional(['message' => 'Job updated successfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = CustomJob::where(['uuid' => $id])->firstOrFail();
        $this->authorize('delete', $job);

        $job->delete();

        return response()->json(['message' => 'Job deleted successfully'], 200);
    }
}
