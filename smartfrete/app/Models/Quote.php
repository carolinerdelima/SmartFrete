<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Quote
 *
 * Representa uma cotação realizada via Frete Rápido.
 *
 * @property int $id
 * @property string $uuid
 * @property string $recipient_zipcode
 * @property array $frete_rapido_request
 * @property array $frete_rapido_response
 * @property int $response_time_ms
 * @property string|null $status
 * @property string $payload_hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Volume[] $volumes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Carrier[] $carriers
 */
class Quote extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'quotes';

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'recipient_zipcode',
        'frete_rapido_request',
        'frete_rapido_response',
        'response_time_ms',
        'status',
        'payload_hash',
    ];

    /**
     * Casts dos atributos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'frete_rapido_request'  => 'array',
        'frete_rapido_response' => 'array',
        'response_time_ms'      => 'integer',
        'uuid'                  => 'string',
        'status'                => 'string',
    ];

    /**
     * Relacionamento: Quote possui muitos volumes.
     *
     * @return HasMany
     */
    public function volumes(): HasMany
    {
        return $this->hasMany(Volume::class);
    }

    /**
     * Relacionamento: Quote possui muitos carriers.
     *
     * @return HasMany
     */
    public function carriers(): HasMany
    {
        return $this->hasMany(Carrier::class);
    }
}
