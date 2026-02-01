<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'location', 'salary_range', 'type', 'description', 'required_skills', 'is_active'
    ];

    protected $casts = [
        'required_skills' => 'array',
        'is_active' => 'boolean',
    ];
}