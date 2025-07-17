<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = ['recipient_zipcode'];

    public function volumes()
    {
        return $this->hasMany(Volume::class);
    }

    public function carriers()
    {
        return $this->hasMany(Carrier::class);
    }
}
