<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CapibaraCard extends Model
{
    // protected $table = 'capibara_cards'; cuando la tabla no sigue la convención de Laravel
    use HasFactory;

    /**
     * datos manipulables
     * protected $fillable = [
     * 
     * ]
     */

    // datos protegidos
    protected $guarded = [
        'id',
        'name',
        'description',
        'image',
        'state',
        'rarity'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cards_user', 'card_id', 'user_id');  
    }
}
