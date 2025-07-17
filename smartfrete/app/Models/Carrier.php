<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    protected $fillable = ['quote_id', 'name', 'service', 'deadline', 'price'];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}

