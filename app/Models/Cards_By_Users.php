<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards_By_Users extends Model
{
    protected $table = 'cards_user';
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_id',
        'quantity'
    ];
}
