<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volume extends Model
{
    protected $fillable = [
        'quote_id', 'category', 'amount', 'unitary_weight', 'price',
        'sku', 'height', 'width', 'length'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
