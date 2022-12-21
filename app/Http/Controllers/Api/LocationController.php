<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\LocationQuery;
use App\Http\Resources\LocationResource;
use App\Http\Resources\LocationCollection;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postcode = request()->input('postcode', null);

        if ($postcode) {
            $locations = Location::when($postcode, function ($query) use ($postcode) {
                $location = LocationQuery::withinRadiusOfPostcode($postcode);

                return $query->haversine($location->latitude, $location->longitude)->orderBy('distance', 'asc');
            })->get();

            return new LocationCollection($locations);
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocationRequest $request)
    {
        $validated = $request->validated();
        $location = LocationQuery::withinRadiusOfPostcode($validated['postcode']);
        $location = Location::create([
            'postcode' => $validated['postcode'],
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'times' => [
                'opening_times' => $validated['opening_times'],
                'closing_times' => $validated['closing_times'],
            ]
        ]);

        return response()->json([
            'data' => $location,
        ], 201);
    }

    /**
     * Display the nearest resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function nearest(Request $request)
    {
        $validated_input = $request->validate([
            'postcode' => 'required|string',
        ]);
          $postcode = $validated_input['postcode'];


        $location = Location::when($postcode, function($query) use ($postcode) {
            $location = LocationQuery::withinRadiusOfPostcode($postcode);

           $query->haversine($location->latitude, $location->longitude)
                ->orderBy('distance', 'asc');

        })->first();

        \Log::info('Location: ' . $location);

    
        return new LocationResource($location);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        return response()->json([
            'data' => $location,
        ]);
    }
   


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocationRequest  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location->update($request->validated());
        return response()->json([
            'data' => $location,
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
    
        $location->delete();
        return response()->json([], 204);
    }

    
   
}


