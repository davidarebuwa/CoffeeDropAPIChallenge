<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = $this->read();

        foreach ($locations as $location) {
            $postcode = str_replace(' ', '', $location['postcode']);

            try {
                $response = Http::get('https://api.postcodes.io/postcodes/' . $postcode);
                $data = $response->json();
                $latitude = $data['result']['latitude'];
                $longitude = $data['result']['longitude'];
            } catch (\Exception $e) {
                $latitude = null;
                $longitude = null;
            }

            \App\Models\Location::factory()->create([
                'postcode' => $postcode,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'times' => [
                    'opening_times' => [
                        'monday' => $location['open_Monday'],
                        'tuesday' => $location['open_Tuesday'],
                        'wednesday' => $location['open_Wednesday'],
                        'thursday' => $location['open_Thursday'],
                        'friday' => $location['open_Friday'],
                        'saturday' => $location['open_Saturday'],
                        'sunday' => $location['open_Sunday'],
                    
                    ],
                    'closing_times' => [
                        'monday' => $location['closed_Monday'],
                        'tuesday' => $location['closed_Tuesday'],
                        'wednesday' => $location['closed_Wednesday'],
                        'thursday' => $location['closed_Thursday'],
                        'friday' => $location['closed_Friday'],
                        'saturday' => $location['closed_Saturday'],
                        'sunday' => $location['closed_Sunday'],
                    ]
                ]
            ]);
    }
    }



    protected function read()
    {
        $csv = Reader::createFromPath(storage_path('location_data.csv'), 'r');
        $csv->setHeaderOffset(0);
        $data = [];
        foreach ($csv->getRecords() as $record) {
            $data[] = $record;
        }
        return $data;
        
    }
}

