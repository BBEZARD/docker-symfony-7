<?php

namespace App\Doctrine\Listener;

use App\Entity\Articles;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticlesSlugListener
{
    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Articles $articles): void
    {
        if (empty($articles->getSlug())) {
            $articles->setSlug(strtolower($this->slugger->slug($articles->getTitle())));
        }
    }

    public function preFlush(Articles $articles): void
    {
        $articles->setSlug(strtolower($this->slugger->slug($articles->getTitle())));
    }
}
