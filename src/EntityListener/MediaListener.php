<?php

namespace App\EntityListener;

use App\Entity\Media;

class MediaListener
{

    public function __construct()
    {
    }

    public function preUpdate(Media $media) 
    {
        $media->setUpdatedAt(new \DateTimeImmutable());
    }

}