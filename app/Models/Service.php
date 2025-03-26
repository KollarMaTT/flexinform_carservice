<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'id',
        'client_id',
        'car_id',
        'log_number',
        'event',
        'event_time',
        'document_id',
    ];

    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'car_id')
            ->where('client_id', $this->client_id);
    }
}
