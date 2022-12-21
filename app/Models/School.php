<?php

namespace App\Models;

use App\Traits\LogsDefaultOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory, LogsDefaultOptions;

    protected $fillable = [
        'name',
        'address',
    ];

    //relationships
    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Classroom::class);
    }
}
