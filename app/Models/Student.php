<?php

namespace App\Models;

use App\Traits\LogsDefaultOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, LogsDefaultOptions;

    protected $fillable = [
        'nis',
        'classroom_id',
        'user_id',
    ];

    public function balance(): Attribute
    {
        return Attribute::make(get: fn () => $this->user->balance);
    }

    //relation 
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function school()
    {
        return $this->hasOneThrough(School::class, Classroom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
