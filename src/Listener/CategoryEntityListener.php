<?php

namespace App\Listener;

use App\Entity\Categories;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Categories $categories, LifecycleEventArgs $event): void
    {
        $categories->computeSlug($this->slugger);
    }

    public function preUpdate(Categories $categories, LifecycleEventArgs $event): void
    {
        $categories->computeSlug($this->slugger);
    }
}
