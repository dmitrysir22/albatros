<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name', 'last_name', 'phone', 'location', 'linkedin_url',
        'headline', 'years_experience', 'management_level',
        'skills', 'experience', 'education', 'languages',
        'cv_path', 'cv_parsed_at'
    ];

    // Автоматическое преобразование JSON в массив
    protected $casts = [
        'skills' => 'array',
        'experience' => 'array',
        'education' => 'array',
        'languages' => 'array',
        'cv_parsed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}