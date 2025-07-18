<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Volume extends Model
{
    protected $table = 'volumes';

    protected $fillable = [
        'quote_id',
        'category',
        'amount',
        'unitary_weight',
        'price',
        'sku',
        'height',
        'width',
        'length',
    ];

    protected $casts = [
        'category'       => 'integer',
        'amount'         => 'integer',
        'unitary_weight' => 'decimal:2',
        'price'          => 'decimal:2',
        'height'         => 'decimal:2',
        'width'          => 'decimal:2',
        'length'         => 'decimal:2',
    ];

    /**
     * Relacionamento: um volume pertence a uma cotação (quote)
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
