<?php

namespace App\Models;

use App\Observers\CustomJobObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'status',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function applications()
    {
        return $this->belongsTo(Application::class);
    }

    protected static function booted()
    {
        static::observe(CustomJobObserver::class);
    }
}
