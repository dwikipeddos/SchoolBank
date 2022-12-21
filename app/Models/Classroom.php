<?php

namespace App\Models;

use App\Traits\LogsDefaultOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory, LogsDefaultOptions;

    protected $fillable = [
        'class',
        'name',
        'year',
        'school_id',
    ];

    //relations
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
