<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('App.Models.Admin.{id}', function ($user, $id) {
    info("incomming notification for admin id:". $id , [
        'admin' => $user
    ]);
    return (int) $user->id === (int) $id;
});

Broadcast::channel('orders.{device_id}', function(){
    return true;
});