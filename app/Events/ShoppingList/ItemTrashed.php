<?php

namespace App\Events\ShoppingList;

use App\Models\ShoppingList;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemTrashed implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $shoppingList;

    public function __construct(ShoppingList $shoppingList)
    {
        $this->shoppingList = $shoppingList;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('shoppinglist.' . $this->shoppingList->user_id);
    }
}
