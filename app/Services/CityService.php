<?php

namespace App\Services;

use App\{Http\Resources\Cities\CityCollection, City};

class CityService extends BasicService
{
    /**
     * @return CityCollection
     */
    public function allCities($request, City $cities) {
        return new CityCollection(
            $cities->paginate($request->input('perPage', $cities->perPage))
        );
    }


}
