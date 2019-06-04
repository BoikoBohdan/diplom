<?php

namespace App\Http\Controllers\API\Admin;

use App\{Http\Controllers\Controller,
    Http\Requests\API\WorkLocations\WorkLocationCreate,
    Http\Requests\API\WorkLocations\WorkLocationUpdate,
    Services\WorkLocationService,
    UsersWorkLocations as Location};
use Illuminate\Http\Response;

class UsersWorkLocationsController extends Controller
{
    private $service;

    public function __construct (WorkLocationService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WorkLocationCreate $request
     * @param Location $location
     * @return Response
     */
    public function store (WorkLocationCreate $request, Location $workLocation)
    {
        $this->service->store($workLocation, $request->all());

        return $this->isSuccess();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Location $workLocation
     * @return \App\Http\Resources\WorkLocations\Index|\Illuminate\Database\Eloquent\Model
     */
    public function edit (Location $workLocation)
    {
        return $this->service->edit($workLocation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WorkLocationUpdate $request
     * @param Location $workLocation
     * @return Response
     */
    public function update (WorkLocationUpdate $request, Location $workLocation)
    {
        $this->service->update($workLocation, $request->all());

        return $this->isSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Location $workLocation
     * @return Response
     * @throws \Exception
     */
    public function destroy (Location $workLocation)
    {
        $this->service->destroy($workLocation);

        return $this->isSuccess();
    }
}
