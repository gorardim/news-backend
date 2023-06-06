<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'name',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function getRouteKeyName()
    {
        return 'source_id';
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function scopeWithArticles($query)
    {
        return $query->withCount('articles')->orderBy('articles_count', 'desc');
    }

    public function scopeWithArticlesCount($query)
    {
        return $query->withCount('articles');
    }

    public function scopeWithArticlesCountAndName($query)
    {
        return $query->withCount('articles')->select('name');
    }

    public function scopeWithArticlesCountAndSourceId($query)
    {
        return $query->withCount('articles')->select('source_id');
    }

    public function scopeWithArticlesCountAndSourceIdAndName($query)
    {
        return $query->withCount('articles')->select('source_id', 'name');
    }
}
