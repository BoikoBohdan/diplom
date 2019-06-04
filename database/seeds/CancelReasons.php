<?php

use App\{CancelReason};
use Illuminate\Database\Seeder;

class CancelReasons extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        $fakeOrder = CancelReason::create([
            'info' => 'Fake order'
        ]);

        $fakeOrder->additionalCancelReason()->create([
            'info' => 'Customer doesn`t open doors and doesn`t answer calls'
        ]);

        $fakeOrder->additionalCancelReason()->create([
            'info' => 'Customer says to have ordered nothing'
        ]);

        $fakeOrder->additionalCancelReason()->create([
            'info' => 'Customer refuses best, too long waiting time'
        ]);

        $fakeOrder->additionalCancelReason()->create([
            'info' => 'Customer rejects the order, doesn`t want it anymore'
        ]);

        $fakeOrder->additionalCancelReason()->create([
            'info' => 'Person / Company doesn`t exist at specified address'
        ]);

        $fakeOrder->additionalCancelReason()->create([
            'info' => 'Obvious tampering'
        ]);

        $cancelOrder = CancelReason::create([
            'info' => 'Customer has cancelled order'
        ]);

        $cancelOrder->additionalCancelReason()->create([
            'info' => 'Customer has selected wrong delivery time'
        ]);

        $cancelOrder->additionalCancelReason()->create([
            'info' => 'Customer wants to order something else'
        ]);

        $cancelOrder->additionalCancelReason()->create([
            'info' => 'Customer no longer wants to order, shop is informed and agrees'
        ]);

        $cancelOrder->additionalCancelReason()->create([
            'info' => 'Customer doesn`t want to wait longer delivery time'
        ]);

        $cancelOrder->additionalCancelReason()->create([
            'info' => 'Customer wants to order with another payment method'
        ]);

        $cancelOrder->additionalCancelReason()->create([
            'info' => 'Customer has ordered twice'
        ]);

    }
}
