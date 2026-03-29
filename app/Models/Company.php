<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'cnpj',
        'name',
        'email',
        'corporate_name',
        'user_id'
    ];

    protected static function booted(): void
    {
        static::deleting(function (Company $company): void {
            $company->projects()->delete();
        });

        static::restoring(function (Company $company): void {
            $company->projects()->onlyTrashed()->restore();
        });
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
