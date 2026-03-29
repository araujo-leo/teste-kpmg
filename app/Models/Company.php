<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'cnpj',
        'name',
        'email',
        'corporate_name',
        'user_id'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
