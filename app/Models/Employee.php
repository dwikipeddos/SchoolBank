<?php

namespace App\Models;

use App\Traits\LogsDefaultOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, LogsDefaultOptions;

    protected $fillable = [
        'nip',
        'user_id',
    ];

    //relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_employees');
    }

    public function schoolEmployees()
    {
        return $this->hasMany(SchoolEmployee::class);
    }
}
