<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'section',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'custom_css',
        'custom_js',
        'is_active',
		'external_scripts',
		'layout',
		'banner_image'
    ];

    /**
     * Casts for specific attributes
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
}