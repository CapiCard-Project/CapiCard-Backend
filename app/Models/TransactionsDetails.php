<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'status',
        'amount',
        'type_payment'
    ];
}
