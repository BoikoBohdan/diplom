<?php

use App\Broadcasting\API\Chat\ChatRoomChannel;
use App\Broadcasting\API\Chat\UserChannel;
use App\Broadcasting\API\Orders\OrdersChannel;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('Orders', OrdersChannel::class);
Broadcast::channel('GodsEye', OrdersChannel::class);
Broadcast::channel('User.{id}', UserChannel::class);
Broadcast::channel('Chat.{room}', ChatRoomChannel::class);


