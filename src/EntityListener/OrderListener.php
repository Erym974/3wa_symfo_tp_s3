<?php

namespace App\EntityListener;

use App\Entity\Order;

class OrderListener
{

    public function __construct()
    {
    }

    public function preUpdate(Order $order) 
    {
        $order->setUpdatedAt(new \DateTimeImmutable());
    }

}