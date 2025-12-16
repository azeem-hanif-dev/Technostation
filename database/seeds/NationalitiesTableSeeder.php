<?php

use App\Models\StaffingCompany\Nationality;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class NationalitiesTableSeeder extends Seeder
{
    public function run()
    {
        $nationalities = [];
        $client = new Client();

        try {
            $response = $client->get('https://restcountries.com/v3.1/all');

            if ($response->getStatusCode() == 200)
            {
                $countries = json_decode($response->getBody(), true);

                foreach ($countries as $key => $country){
                    $nationalities[] = [
                        'name' => $country['name']['common']
                    ];
                }
            }
        } catch (RequestException $e) {
            echo "Request failed: " . $e->getMessage();
        }

        Nationality::insert($nationalities);
    }
}
