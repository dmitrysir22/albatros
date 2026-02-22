<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProfile extends Model
{
    // Разрешаем массовое заполнение всех полей
    protected $guarded = [];
    protected $table = 'candidate_profiles';

    // Приведение типов для корректной работы с JSON и датами
    protected $casts = [
        'jurisdictions' => 'array',
        'secondary_practice_areas' => 'array',
        'industry_sectors' => 'array',
        'skills_tags' => 'array',
        'pref_locations' => 'array',
        'ai_keywords' => 'array',
        'ai_regulatory_tags' => 'array',
        'cv_parsed_at' => 'datetime',
        'pqe_years' => 'integer',
    ];

    /**
     * Связь с пользователем (может быть NULL для гостей)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Профессиональный опыт (от новых к старым)
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(CandidateExperience::class)->orderBy('start_date', 'desc');
    }

    /**
     * Образование
     */
    public function educations(): HasMany
    {
        return $this->hasMany(CandidateEducation::class);
    }
}