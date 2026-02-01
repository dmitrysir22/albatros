<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cv_path',
        'headline',
        'experience_years',
        'location',
        'skills',
        'bio',
        'ai_parsed_data',
    ];
    
    // Касты превращают JSON из базы сразу в массив PHP
    protected $casts = [
        'ai_parsed_data' => 'array',
        'skills' => 'array', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}