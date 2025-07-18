<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quote extends Model
{
    use HasFactory;

    protected $table = 'quotes';

    protected $fillable = [
        'recipient_zipcode',
        'uuid',
        'status',
        'frete_rapido_request',
        'frete_rapido_response',
        'response_time_ms',
    ];

    protected $casts = [
        'frete_rapido_request'  => 'array',
        'frete_rapido_response' => 'array',
        'response_time_ms'      => 'integer',
    ];

    /**
     * Relacionamento: Quote possui muitos volumes
     */
    public function volumes(): HasMany
    {
        return $this->hasMany(Volume::class);
    }

    /**
     * Relacionamento: Quote possui muitos carriers
     */
    public function carriers(): HasMany
    {
        return $this->hasMany(Carrier::class);
    }
}
