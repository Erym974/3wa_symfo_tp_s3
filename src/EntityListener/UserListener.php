<?php

namespace App\EntityListener;

use App\Entity\User;

class UserListener
{

    public function __construct()
    {
    }

    public function preUpdate(User $user) 
    {
        $user->setUpdatedAt(new \DateTimeImmutable());
    }

}