<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    public $timestamps = true;
    protected $fillable = [
        'amount',
        'description',
        'status',
        'token',
        'order_id',
        'created_at',
        'updated_at',
    ];
}
