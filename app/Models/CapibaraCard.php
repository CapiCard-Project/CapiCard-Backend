<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
