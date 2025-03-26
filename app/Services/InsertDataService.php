<?php
namespace App\Services;

use App\Models\Car;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InsertDataService
{

    // Insert data into the database from json files if the database is empty
    public function execute()
    {
        if (Client::count() > 0 || Car::count() > 0 || Service::count() > 0) {
            return;
        }

        DB::transaction(function () {

            //Clients 
            $json = Storage::get('data/clients.json');
            
            $clients = json_decode($json, true);

            foreach ($clients as $client) {
                Client::create([
                    'id' => $client['id'],
                    'name' => $client['name'],
                    'card_number' => $client['idcard'],
                ]);
            }

            // Cars
            $json = Storage::get('data/cars.json');
            
            $cars = json_decode($json, true);

            foreach ($cars as $car) {
                Car::create([
                    'id' => $car['id'],
                    'client_id' => $car['client_id'],
                    'car_id' => $car['car_id'],
                    'type' => $car['type'],
                    'registered' => $car['registered'],
                    'ownbrand' => $car['ownbrand'],
                    'accidents' => $car['accident'],
                ]);
            }

            // Services
            $json = Storage::get('data/services.json');

            $services = json_decode($json, true);

            foreach ($services as $service) {
                Service::create([
                    'id' => $service['id'],
                    'client_id' => $service['client_id'],
                    'car_id' => $service['car_id'],
                    'log_number' => $service['lognumber'],
                    'event' => $service['event'],
                    'event_time' => $service['eventtime'],
                    'document_id' => $service['document_id'],
                ]);
            }
        });
    }
}