<?php

namespace App\Http\Controllers\API;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarController extends Controller
{
    public function services(Car $car)
    {
        $services = $car->services()
            ->select('id','car_id', 'client_id', 'event', 'log_number', 'event_time', 'document_id')
            ->get();
            
        return response()->json(['services' => $services]);
    }
}
