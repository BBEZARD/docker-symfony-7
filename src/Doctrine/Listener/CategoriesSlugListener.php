<?php

namespace App\Doctrine\Listener;

use App\Entity\Categories;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesSlugListener
{
    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Categories $categories): void
    {
        if (empty($categories->getSlug())) {
            $categories->setSlug(strtolower($this->slugger->slug($categories->getName())));
        }
    }

    public function preFlush(Categories $categories): void
    {
        $categories->setSlug(strtolower($this->slugger->slug($categories->getName())));
    }
}
