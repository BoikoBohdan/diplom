<?php

namespace App\Components\Traits;

use App\{Order as Orders, OrdersLocations};
use Illuminate\Support\{Carbon, Collection};

trait OrderTrait
{
    /**
     * Set export body for orders
     *
     * @param [type] $orders
     * @return array
     */
    protected static function exportBody ($orders, $body = [])
    {
        foreach ($orders as $order) {
            $transformer = [
                'GroupGuid' => (string)$order->group_guid,
                'Reference' => (string)$order->reference_id,
                'PickupDate' => (string)$order->pickup_date,
                'DropoffDate' => (string)$order->dropoff_date,
                'PickupTimeFrom' => (string)$order->pickup_time_from,
                'PickupTimeTo' => (string)$order->pickup_time_to,
                'DropoffTimeFrom' => (string)$order->dropoff_time_from,
                'DropoffTimeTo' => (string)$order->dropoff_time_to,
                'EnforceSignature' => (string)$order->enforce_signature,
                'EstimatedTimeLoading' => (string)$order->time_loading,
                'EstimatedTimeDroppingOff' => (string)$order->time_dropping,
                'EstimatedBreakAfterDropoff' => (string)$order->time_break,
                'LoadType' => (string)$order->load_type,
                'Fee' => (string)$order->fee,
                'Notes' => (string)$order->notes,
                'PaymentInfo' => (string)$order->payment_info,
                'PaymentType' => (string)$order->payment_type,
                'CustomerInfo' => (string)$order->customer_info,
                'ASAP' => (string)$order->asap,
                'ShipmentType' => (string)$order->shipment_type,
            ];

            $body[] = $transformer;
        }
        return $body;
    }

    protected static function exportLocationBody ($location)
    {
        return [
            'Reference' => (string)$location->reference_id,
            'Name' => (string)$location->name,
            'Phone' => (string)$location->phone,
            'City' => (string)1,
            'CountryCode' => (string)2,
            'Postcode' => (string)$location->postcode,
            'Streetaddress' => (string)3,
            'ContactName' => (string)$location->contact_name,
            'Note' => (string)$location->note,
        ];
    }

    /**
     * Transform incoming order request before storing to database
     *
     * @param Collection $order
     * @return Collection
     */
    public function transformOrder (Collection $order)
    {
        $pickup = collect($order['Pickup'])->merge(['Type' => OrdersLocations::LOCATION_TYPE['pickup']]);
        $dropoff = collect($order['Dropoff'])->merge(['Type' => OrdersLocations::LOCATION_TYPE['dropoff']]);

        $orderInfo = self::orderBody($order); //transform order info
        $locations = $this->transformLocations(collect([$pickup, $dropoff])); //transform order locations info
        $products = $this->transformProducts(collect($order['Products'])); //transform order products info

        return collect(
            [
                'order' => $orderInfo,
                'locations' => $locations,
                'products' => $products
            ]
        );
    }

    /**
     * Order body
     *
     * @param Collection $order
     * @return array
     */
    protected static function orderBody (Collection $order)
    {
        return [
            'group_guid' => $order['GroupGuid'] ?? null,
            'reference_id' => (string)$order['Reference'],
            'notes' => $order['Notes'] ?? null,
            'cutomer_info' => $order['CustomerInfo'] ?? null,
            'payment_info' => $order['PaymentInfo'] ?? null,
            'pickup_date' => (string)$order['PickupDate'],
            'pickup_time_from' => (string)Carbon::createFromFormat('H: i: s\Z', $order['PickupTimeFrom']),
            'pickup_time_to' => empty($order['PickupTimeTo'])
                ? null
                : Carbon::createFromFormat('H: i: s\Z', $order['PickupTimeTo']),
            'dropoff_date' => (string)$order['DropoffDate'],
            'dropoff_time_from' => (string)Carbon::createFromFormat('H: i: s\Z', $order['DropoffTimeFrom']),
            'dropoff_time_to' => empty($order['DropoffTimeTo'])
                ? null
                : Carbon::createFromFormat('H: i: s\Z', $order['DropoffTimeTo']),
            'fee' => (integer)convertFloatToInteger($order['Fee']),
            'time_loading' => $order['EstimatedTimeLoading'] ?? null,
            'time_dropping' => $order['EstimatedTimeDroppingOff'] ?? null,
            'time_break' => $order['EstimatedBreakAfterDropoff'] ?? null,
            'load_type' => $order['LoadType'] ?? null,
            'status' => (integer)Orders::STATUSES['not_assigned'],
            'payment_type' => (integer)$order['PaymentType'],
            'shipment_type' => (integer)$order['ShipmentType'],
            'asap' => $order['ASAP'] ?? null,
            'enforce_signature' => $order['EnforceSignature'] ?? null,
        ];
    }

    /**
     * Transform all incoming locations to new format
     *
     * @param Collection $locations
     * @return Collection
     */
    public function transformLocations (Collection $locations)
    {
        return $locations->map(function (Collection $item) {
            return self::locationBody($item);
        });
    }

    /**
     * Order location bidy
     *
     * @param Collection $location
     * @return array
     */
    protected static function locationBody (Collection $location)
    {
        return [
            'order_id' => null,
            'type' => (integer)$location['Type'],
            'postcode' => $location['Postcode'],
            'reference_id' => $location['Reference'] ?? null,
            'name' => $location['Name'] ?? null,
            'phone' => (string)$location['Phone'],
            'streetaddress' => (string)$location['Streetaddress'],
            'city' => (string)$location['City'],
            'country_code' => $location['CountryCode'] ?? null,
            'contact_name' => $location['ContactName'] ?? null,
            'note' => $location['Note'] ?? null
        ];
    }

    /**
     * Transform all incoming products to new format
     *
     * @param Collection $products
     * @return Collection
     */
    public function transformProducts (Collection $products)
    {
        return $products->map(function ($item) {
            return self::productBody(collect($item));
        });
    }

    /**
     * Order product body
     *
     * @param Collection $product
     * @return array
     */
    protected static function productBody (Collection $product)
    {
        return [
            'order_id' => null,
            'reference_id' => $product['Reference'] ?? null,
            'unit_type' => $product['UnitType'] ?? null,
            'name' => (string)$product['Name'],
            'quantity' => $product['Quantity'] ?? null,
            'note' => $product['Note'] ?? null,
            'fee' => (integer)convertFloatToInteger($product['Fee']),
            'image' => $product['ImageUrl'] ?? null,
            'type' => $product['Type'] ?? null
        ];
    }
}
