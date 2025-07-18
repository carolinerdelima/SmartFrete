<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrier extends Model
{
    use HasFactory;

    protected $table = 'carriers';

    protected $fillable = [
        'quote_id',
        'name',
        'service',
        'deadline_days',
        'final_price',
        'carrier_code',
        'service_code',
        'original_price',
    ];

    protected $casts = [
        'final_price'     => 'decimal:2',
        'original_price'  => 'decimal:2',
        'deadline_days'   => 'integer',
    ];

    /**
     * Relacionamento: Carrier pertence a uma Quote
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
