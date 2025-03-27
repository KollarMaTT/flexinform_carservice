<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'id',
        'client_id',
        'car_id',
        'type',
        'registered',
        'ownbrand',
        'accidents',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'car_id', 'car_id')
            ->where('client_id', $this->client_id);
    }

    public function getLastServiceAttribute()
    {
        return Service::where('car_id', $this->car_id)
                      ->where('client_id', $this->client_id)
                      ->orderByDesc('log_number')
                      ->first();
    }
}
