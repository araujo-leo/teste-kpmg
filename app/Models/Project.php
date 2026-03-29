<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'description',
        'status',
        'priority',
        'starts_at',
        'ends_at',
        'manager_id',
    ];

    protected $casts = [
        'starts_at' => 'date:Y-m-d',
        'ends_at' => 'date:Y-m-d',
    ];

    public static function booted()
    {
        static::deleting(function (Project $project): void {
            $project->tickets()->delete();
        });

        static::restoring(function (Project $project): void {
            $project->tickets()->onlyTrashed()->restore();
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
