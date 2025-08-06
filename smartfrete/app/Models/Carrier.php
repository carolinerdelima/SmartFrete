<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Carrier
 *
 * Representa uma transportadora vinculada a uma cotação (Quote).
 *
 * @property int $id
 * @property int $quote_id
 * @property string $name
 * @property string $service
 * @property int $deadline_days
 * @property float $final_price
 * @property float $original_price
 * @property string|null $carrier_code
 * @property string|null $service_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Quote $quote
 */
class Carrier extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'carriers';

    /**
     * Atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
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

    /**
     * Casts dos atributos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'final_price'     => 'decimal:2',
        'original_price'  => 'decimal:2',
        'deadline_days'   => 'integer',
        'carrier_code'    => 'string',
        'service_code'    => 'string',
    ];

    /**
     * Relacionamento: Carrier pertence a uma Quote.
     *
     * @return BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
