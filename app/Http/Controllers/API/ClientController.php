<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\ClientSearchRequest;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::select('id', 'name', 'card_number')->paginate(10);
        return response()->json(['clients' => $clients]);
    }

    public function cars(Client $client)
    {
        $cars = $client->cars()
            ->select('id', 'car_id', 'client_id', 'type', 'registered', 'ownbrand', 'accidents')
            ->get();

        $cars->each(function ($car) {
            $service = $car->lastService;
            $car->last_service_event = $service ? $service->event : null;
            $car->last_service_event_time = $service ? $service->event_time : null;
        });

        return response()->json(['cars' => $cars]);
    }

    public function search(ClientSearchRequest $request)
    {

        $clients = Client::select('id', 'name', 'card_number')->withCount('cars')->withCount('services');

        if($request->name != null) {
            $clients->where('name', 'like', "%{$request->name}%");
        }

        if($request->card_number != null) {
            $clients->where('card_number',  $request->card_number);
        }

        $clients = $clients->paginate(10);

        return response()->json(['clients' => $clients]);
    }
}
