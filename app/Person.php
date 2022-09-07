<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    public function person() {
        return $this->belongsTo(Person::class, 'created_by');
    }
}
