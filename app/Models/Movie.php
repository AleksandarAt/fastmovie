<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'poster_image',
        'trailer_url',
        'genre',
        'age_rating',
        'language',
        'country',
        'duration',
        'release_date',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function shows()
    {
        return $this->hasMany(Show::class);
    }

    // Scopes for searching
    public function scopeByGenre($query, $genre)
    {
        return $query->where('genre', $genre);
    }

    public function scopeByAgeRating($query, $ageRating)
    {
        return $query->where('age_rating', '<=', $ageRating);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    // Accessor for poster image
    public function getPosterUrlAttribute()
    {
        return $this->poster_image ? asset('storage/' . $this->poster_image) : null;
    }
}
