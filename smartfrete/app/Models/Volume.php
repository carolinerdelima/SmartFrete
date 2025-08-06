<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Volume
 *
 * Representa um volume associado a uma cotação.
 *
 * @property int $id
 * @property int $quote_id
 * @property int $category
 * @property int $amount
 * @property float $unitary_weight
 * @property float $price
 * @property string $sku
 * @property float $height
 * @property float $width
 * @property float $length
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Quote $quote
 */
class Volume extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'volumes';

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
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

    /**
     * Casts dos atributos para tipos nativos.
     *
     * @var array<string, string>
     */
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
     * Relacionamento: um volume pertence a uma cotação (quote).
     *
     * @return BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
