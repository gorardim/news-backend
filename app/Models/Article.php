<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'source_id',
        'author',
        'title',
        'description',
        'url',
        'url_to_image',
        'published_at',
        'content',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPublishedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y H:i');
    }

    public function setPublishedAtAttribute($value)
    {
        $this->attributes['published_at'] = \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
