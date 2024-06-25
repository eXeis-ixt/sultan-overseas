<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'sl',
        'name',
        'image',
        'passport_number',
        'passport_expiration_date',
        'status',
        'due',
        'total',
        'type',
        'embassy_date',
        'delivery_date',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
