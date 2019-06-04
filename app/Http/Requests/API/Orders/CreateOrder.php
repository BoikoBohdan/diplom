<?php

namespace App\Http\Requests\API\Orders;

use App\Http\Requests\API\BaseApiRequest;

class CreateOrder extends BaseApiRequest
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
            'GroupGuid' => 'nullable|uuid',
            'Reference' => 'required|string|unique:orders,reference_id',
            'PickupDate' => 'required|date',
            'DropoffDate' => 'required|date',
            'PickupTimeFrom' => 'required|date_format:H:i:s\Z',
            'PickupTimeTo' => 'nullable|date_format:H:i:s\Z',
            'DropoffTimeFrom' => 'required|date_format:H:i:s\Z',
            'DropoffTimeTo' => 'nullable|date_format:H:i:s\Z',
            'PickupNotes' => 'nullable|string',
            'DropoffNotes' => 'required|string',
            'EstimatedTimeLoading' => 'nullable|integer',
            'EstimatedTimeDroppingOff' => 'nullable|integer',
            'EstimatedBreakAfterDropoff' => 'nullable|integer',
            'LoadType' => 'nullable|integer',
            'Fee' => 'required|numeric',
            'Notes' => 'nullable|string',
            'PaymentInfo' => 'nullable|string',
            'PaymentType' => 'required|integer',
            'CustomerInfo' => 'nullable|string',
            'ASAP' => 'nullable|boolean',
            'ShipmentType' => 'required|integer',
            'Pickup.Reference' => 'required|string',
            'Pickup.Name' => 'required|string',
            'Pickup.Phone' => 'required|string',
            'Pickup.City' => 'required|string',
            'Pickup.CountryCode' => 'nullable|string',
            'Pickup.Postcode' => 'required|string',
            'Pickup.Streetaddress' => 'required|string',
            'Pickup.ContactName' => 'required|string',
            'Pickup.Note' => 'nullable|string',
            'Dropoff.Reference' => 'nullable|string',
            'Dropoff.Name' => 'nullable|string',
            'Dropoff.Phone' => 'required|string',
            'Dropoff.City' => 'required|string',
            'Dropoff.CountryCode' => 'nullable|string',
            'Dropoff.Postcode' => 'required|string',
            'Dropoff.Streetaddress' => 'required|string',
            'Dropoff.ContactName' => 'required|string',
            'Dropoff.Note' => 'nullable|string',
            'Products.*.Reference' => 'nullable|string',
            'Products.*.Name' => 'required|string',
            'Products.*.UnitType' => 'nullable|integer',
            'Products.*.Quantity' => 'nullable|integer',
            'Products.*.Note' => 'nullable|string',
            'Products.*.Fee' => 'required|numeric',
            'Products.*.ImageUrl' => 'nullable|string',
            'Products.*.Type' => 'nullable|integer'
        ];
    }

}
