<?php

namespace App\Models;

use App\Observers\ApplicationObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'cover_letter',
        'cv_path',
        'expires_at',
    ];

    protected static function booted()
    {
        static::observe(ApplicationObserver::class);
    }

    public function job()
    {
        return $this->belongsTo(CustomJob::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL for the CV path.
     *
     * @return string
     */
    public function getCvPathAttribute($value)
    {
        return $value ? url('/storage/' . $value) : null;
    }
}
