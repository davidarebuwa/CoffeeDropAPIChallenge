<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

 

class LocationQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'postcode',
        'latitude',
        'longitude',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    /**
     * Scope a query to only include locations within a given radius of a given postcode.
     *
     * @param  string  $postcode
     * @return \App\Models\LocationQuery
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function scopeWithinRadiusOfPostcode(Builder $query, string $postcode)
    {
        $postcode = str_replace(' ', '', $postcode);

        return $query->where('postcode', $postcode)->firstOr( function () use ($postcode) {
            $response = Http::get('https://api.postcodes.io/postcodes/' . $postcode)->throw();

            $data = $response->json();

            return $this->create([
                'postcode' => $postcode,
                'latitude' => $data['result']['latitude'],
                'longitude' => $data['result']['longitude'],
                'response' => $data['status'],
            ]);


        });
    }
}
