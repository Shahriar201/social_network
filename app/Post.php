<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'person_post_content', 'page_post_content'
    ];

    public function person() {
        return $this->belongsTo(Person::class, 'created_by', 'id');
    }

    public function page() {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }
}
