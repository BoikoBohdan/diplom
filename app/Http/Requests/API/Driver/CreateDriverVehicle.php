<?php

namespace App\Http\Requests\API\Driver;

use App\Http\Requests\API\BaseApiRequest;
use App\Rules\API\VehicleUniqueName;

class CreateDriverVehicle extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255',
                new VehicleUniqueName(null, $this->get('name'), $this->get('driver_id'), $this->getMethod())
            ],
            'vehicle_type_id' => 'required|integer|exists:vehicle_types,id',
            'driver_id' => 'required|integer|exists:drivers,id',
            'is_shift' => 'required|boolean'
        ];
    }

    public function messages ()
    {
        return
            [
                'vehicle_type_id.required' => 'The vehicle type is required',
                'vehicle_type_id.exists' => 'The selected vehicle type is invalid',
                'company_id.exists' => 'The selected company is invalid',
                'driver_id.required' => 'The driver is required',
                'driver_id.exists' => 'The selected driver is invalid',
                'is_shift.required' => 'The on shift field is required'
            ];
    }
}
