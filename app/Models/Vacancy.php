<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        // System
        'slug', 'is_active', 'company_logo',
        
        // Frontend
        'title', 'company_name', 'location', 'salary_range',
        'contract_type', 'working_mode', 'pqe_range', 'office_attendance',
        'summary', 'description', 'requirements', 'benefits',
        
        // Backend / AI
        'internal_job_type', 'practice_area', 'seniority_level', 
        'pqe_weighting', 'required_skills', 'preferred_skills', 
        'keywords', 'internal_notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'required_skills' => 'array',  // Авто-конвертация JSON <-> Array
        'preferred_skills' => 'array', // Авто-конвертация JSON <-> Array
    ];
}