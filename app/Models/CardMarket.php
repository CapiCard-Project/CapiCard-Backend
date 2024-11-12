<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardMarket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_id',
        'price',
        'is_active',
        'time'
    ];

    /**
     *Realcion con la tabla de cartas
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(CapibaraCard::class, 'card_id');
    }

}
