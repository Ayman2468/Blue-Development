<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'cover_image',
        'pinned',
        'removed'
    ];

    protected $hidden = [
        'pivot',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class,post_tag::class,'post_id','tag_id','id','id');
    }
}
